<?php
declare(strict_types=1);

namespace App\Handler\Command\Entity\PreparedNotificationData;

use App\Entity\Entity\NotificationJobId\NotificationJobId;
use App\Entity\Entity\PreparedNotificationData\PreparedNotificationData;
use App\Entity\Entity\SiftedPreparedNotificationData\ForSiftedPreparedNotificationData\SendingDate;
use App\Entity\Entity\SiftedPreparedNotificationData\SiftedPreparedNotificationData;
use App\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractSendingDate;
use App\EventDispatcher\Event\LoggerEvent;
use App\Messenger\Message\Entity\ExecutedNotificationData\UseCases\Save\Message as ExecutedNotificationDataSaveMessage;
use App\Messenger\Message\Entity\NotificationJobId\UseCases\Save\Message as SaveMessage;
use App\Messenger\RabbitMQ\AbstractInformer;
use App\Service\Entity\NotificationJobId\StateManipulatorService as NJIStateManipulatorService;
use App\Service\Entity\NotificationRetransmissionGuard\StateManipulatorService as NRGStateManipulatorService;
use App\Service\Entity\PreparedNotificationData\GetterService as PNDGetterService;
use App\Service\Entity\SiftedPreparedNotificationData\GetterService as SPNDGetterService;
use App\Service\Entity\SiftedPreparedNotificationData\SenderService as SPNDSenderService;
use App\Service\Entity\SiftedPreparedNotificationData\StateManipulatorService as SPNDStateManipulatorService;
use App\Util\Common\TypeConvertingResolver;
use App\Util\Entity\Entity\NotificationRetransmissionGuard\NotificationRetransmissionGuardResolver;
use App\Util\Entity\Entity\PreparedNotificationData\DateResolver;
use App\Util\Entity\Entity\SiftedPreparedNotificationData\GzipCsvCreator;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use LogicException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;

final class SendDataToCommunicationModuleCommandHandler
{
    private EntityManagerInterface $entityManager;

    private EventDispatcherInterface $eventDispatcher;

    private MessageBusInterface $messageBus;

    private SPNDGetterService $spndGetterService;

    private SPNDStateManipulatorService $spndStateManipulatorService;

    private SPNDSenderService $spndSenderService;

    private PNDGetterService $pndGetterService;

    private NRGStateManipulatorService $nrgStateManipulatorService;

    private NJIStateManipulatorService $njiStateManipulatorService;

    private NotificationRetransmissionGuardResolver $notificationRetransmissionGuardResolver;

    private DateResolver $dateResolver;

    private GzipCsvCreator $gzipCsvCreator;

    private TypeConvertingResolver $typeConvertingResolver;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        MessageBusInterface $messageBus,
        SPNDGetterService $spndGetterService,
        SPNDStateManipulatorService $spndStateManipulatorService,
        SPNDSenderService $spndSenderService,
        PNDGetterService $pndGetterService,
        NRGStateManipulatorService $nrgStateManipulatorService,
        NJIStateManipulatorService $njiStateManipulatorService,
        NotificationRetransmissionGuardResolver $notificationRetransmissionGuardResolver,
        DateResolver $dateResolver,
        GzipCsvCreator $gzipCsvCreator,
        TypeConvertingResolver $typeConvertingResolver
    )
    {
        $this->entityManager = $entityManager;
        $this->eventDispatcher = $eventDispatcher;
        $this->messageBus = $messageBus;
        $this->spndGetterService = $spndGetterService;
        $this->spndStateManipulatorService = $spndStateManipulatorService;
        $this->spndSenderService = $spndSenderService;
        $this->pndGetterService = $pndGetterService;
        $this->nrgStateManipulatorService = $nrgStateManipulatorService;
        $this->njiStateManipulatorService = $njiStateManipulatorService;
        $this->notificationRetransmissionGuardResolver = $notificationRetransmissionGuardResolver;
        $this->dateResolver = $dateResolver;
        $this->gzipCsvCreator = $gzipCsvCreator;
        $this->typeConvertingResolver = $typeConvertingResolver;
    }

    public function sendData(): void
    {
        $sendingDate = new SendingDate($sendingDateTimeImmutable = new DateTimeImmutable('now', null));

        $sendingDateCollector = $this->spndGetterService->getSendingDates();
        if (!$sendingDateCollector->hasSendingDate($sendingDate)) {
            $sendingDateCollector->add($sendingDate);
        }

        /** @var SendingDate $sendingDate */
        foreach ($sendingDateCollector as $sendingDate) {
            if (!$this->notificationRetransmissionGuardResolver->isUnderGuard($sendingDate)) {
                $this->createAndSaveSiftedPreparedNotificationData($sendingDate);

                if (!$this->needToSend($sendingDate)) {
                    return;
                }

                $response = $this->sendNotificationData($sendingDate);
                $this->checkResponseDataForNeededKeys($response);

                if ((bool)$response['success']) {
                    try {
                        $this->initializeInformationCollectingProcess(
                            $id = (int)$response['data']['jobId'], $sendingDate, $sendingDateTimeImmutable
                        );
                    } catch (Exception $exception) {
                        $this->messageBus->dispatch(
                            new SaveMessage($id, $sendingDate, $sendingDateTimeImmutable),
                            [new AmqpStamp(AbstractInformer::COMMON_NORMAL_BINDING_KEY, AbstractInformer::FLAG, AbstractInformer::ATTRIBUTES)]
                        );

                        $this->eventDispatcher->dispatch(
                            new LoggerEvent(
                                'An exception has been thrown while initializeInformationCollectingProcess process, so the process moved to the AMQP-queue',
                                [$exception]
                            ),
                            LoggerEvent::EVENT_NAME
                        );

                        return;
                    }
                } else {
                    continue;
                }
            }
        }
    }

    private function createAndSaveSiftedPreparedNotificationData(AbstractSendingDate $sendingDate): void
    {
        $count = $this->pndGetterService->countOfAll();
        $limit = 1000;
        for ($offset = 0; $offset <= ($count - 1); $offset += $limit) {
            $preparedNotificationDataCollector = $this->pndGetterService->getAll($limit, $offset);

            /** @var PreparedNotificationData $preparedNotificationData */
            foreach ($preparedNotificationDataCollector as $preparedNotificationData) {
                if ($this->dateResolver->shouldToNotify($preparedNotificationData, $sendingDate->getDateTimeImmutableValue())) {
                    $siftedPreparedNotificationData = $this->spndStateManipulatorService->create($preparedNotificationData, $sendingDate);

                    $this->safelySaveSPND($siftedPreparedNotificationData, $sendingDate);
                }
            }
        }
    }

    private function safelySaveSPND(SiftedPreparedNotificationData $siftedPreparedNotificationData, AbstractSendingDate $sendingDate): void
    {
        $this->entityManager->persist($siftedPreparedNotificationData);

        $this->entityManager->beginTransaction();
        try {
            $this->spndStateManipulatorService->deleteByMeterIdAndSendingDate($siftedPreparedNotificationData->getMeterId(), $sendingDate);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }

        $this->entityManager->clear(null);
    }

    private function needToSend(AbstractSendingDate $sendingDate): bool
    {
        return $this->spndGetterService->countBySendingDate($sendingDate) > 0 ? true : false;
    }

    private function sendNotificationData(AbstractSendingDate $sendingDate): array
    {
        $this->gzipCsvCreator->prepareSendingData($sendingDate);
        $sendingData = $this->gzipCsvCreator->getPreparedData();

        return $this->spndSenderService->sendToCommunicationModule($sendingData);
    }

    private function checkResponseDataForNeededKeys(array $response): void
    {
        if (!key_exists('success', $response)
            || (!is_string($success = $response['success']) && !$this->typeConvertingResolver->canConvertToBoolean($success))
            || !key_exists('data', $response) || !is_array($response['data']) || !key_exists('jobId', $response['data'])
            || (!is_string($jobId = $response['data']['jobId']) && !$this->typeConvertingResolver->canConvertToInteger($jobId))) {
            new LogicException('Bad params in response', 0, null);
        }
    }

    public function initializeInformationCollectingProcess(
        int $notificationJobIdId, AbstractSendingDate $sendingDate, DateTimeImmutable $sendingDateTimeImmutable
    ): void
    {
        $notificationJobId = $this->createAndSaveNotificationJobId($notificationJobIdId, $sendingDate, $sendingDateTimeImmutable);

        $this->createExecutedNotificationData($notificationJobId, $sendingDate);
    }

    private function createAndSaveNotificationJobId(
        int $id, AbstractSendingDate $sendingDate, DateTimeImmutable $sendingDateTimeImmutable
    ): NotificationJobId
    {
        $notificationRetransmissionGuard = $this->nrgStateManipulatorService->create($sendingDate);

        $commonMessagesQuantity = $this->spndGetterService->countBySendingDate($sendingDate);

        $notificationJobId = $this->njiStateManipulatorService->create(
            $notificationRetransmissionGuard, $id, $sendingDateTimeImmutable, $commonMessagesQuantity
        );

        $this->entityManager->persist($notificationJobId);
        $this->entityManager->flush();

        $this->entityManager->clear(null);

        return $notificationJobId;
    }

    private function createExecutedNotificationData(
        NotificationJobId $notificationJobId, AbstractSendingDate $sendingDate
    ): void
    {
        $this->messageBus->dispatch(
            new ExecutedNotificationDataSaveMessage($notificationJobId->getId(), $sendingDate),
            [new AmqpStamp(AbstractInformer::COMMON_NORMAL_BINDING_KEY, AbstractInformer::FLAG, AbstractInformer::ATTRIBUTES)]
        );
    }
}