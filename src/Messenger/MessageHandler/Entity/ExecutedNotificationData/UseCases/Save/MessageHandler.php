<?php
declare(strict_types=1);

namespace App\Messenger\MessageHandler\Entity\ExecutedNotificationData\UseCases\Save;

use App\Entity\Entity\NotificationJobId\ForNotificationJobId\ExecutionStatus;
use App\Entity\Entity\NotificationJobId\ForNotificationJobId\Id;
use App\Entity\Entity\SiftedPreparedNotificationData\SiftedPreparedNotificationData;
use App\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractSendingDate;
use App\Exception\Entity\Entity\NotificationJobId\NotificationJobIdDoesNotExistException;
use App\Messenger\Message\AbstractMessage;
use App\Messenger\Message\Entity\ExecutedNotificationData\UseCases\Save\Message as SaveMessage;
use App\Messenger\MessageHandler\AbstractAsyncMessageHandler;
use App\Service\Entity\ExecutedNotificationData\StateManipulatorService as ENDStateManipulatorService;
use App\Service\Entity\NotificationJobId\GetterService as NJIGetterService;
use App\Service\Entity\SiftedPreparedNotificationData\GetterService as SPNDGetterService;
use App\Service\Entity\SiftedPreparedNotificationData\StateManipulatorService as SPNDStateManipulatorService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use LogicException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessageHandler extends AbstractAsyncMessageHandler
{
    private EntityManagerInterface $entityManager;

    private ENDStateManipulatorService $endStateManipulatorService;

    private NJIGetterService $njiGetterService;

    private SPNDStateManipulatorService $spndStateManipulatorService;

    private SPNDGetterService $spndGetterService;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        MessageBusInterface $messageBus,
        ENDStateManipulatorService $endStateManipulatorService,
        NJIGetterService $njiGetterService,
        SPNDStateManipulatorService $spndStateManipulatorService,
        SPNDGetterService $spndGetterService
    )
    {
        $this->entityManager = $entityManager;
        $this->endStateManipulatorService = $endStateManipulatorService;
        $this->njiGetterService = $njiGetterService;
        $this->spndStateManipulatorService = $spndStateManipulatorService;
        $this->spndGetterService = $spndGetterService;

        parent::__construct($eventDispatcher, $messageBus);
    }

    public function __invoke(SaveMessage $message)
    {
        $this->safelyHandle($message, SaveMessage::class);
    }

    /**
     * @param SaveMessage $message
     */
    public function handle(AbstractMessage $message): void
    {
        $sendingDate = $message->getSendingDate();
        $id = $message->getId();

        while ($this->spndGetterService->countBySendingDate($sendingDate) > 0) {
            $this->transformSiftedNDToExecutedND($id, $sendingDate);
        }

        $this->changeExecutionStatusInNotificationJobId($id);
    }

    private function transformSiftedNDToExecutedND(Id $id, AbstractSendingDate $sendingDate): void
    {
        try {
            $notificationJobId = $this->njiGetterService->getById($id);
        } catch (NotificationJobIdDoesNotExistException $exception) {
            throw new LogicException($exception->getMessage(), 0, $exception);
        }

        $siftedPreparedNotificationDataCollector = $this->spndGetterService->getAllBySendingDate($sendingDate, 1000, 0);

        /** @var SiftedPreparedNotificationData $siftedPreparedNotificationData */
        foreach ($siftedPreparedNotificationDataCollector as $siftedPreparedNotificationData) {
            $executedNotificationData = $this->endStateManipulatorService->create(
                $notificationJobId,
                $siftedPreparedNotificationData->getContract(),
                $siftedPreparedNotificationData->getMeterId(),
                $siftedPreparedNotificationData->getMeterNextCheckDate()
            );

            $this->entityManager->persist($executedNotificationData);

            $this->entityManager->beginTransaction();
            try {
                $this->spndStateManipulatorService->deleteById($siftedPreparedNotificationData->getId());

                $this->entityManager->flush();

                $this->entityManager->commit();
            } catch (Exception $exception) {
                $this->entityManager->rollback();

                throw $exception;
            }
            $this->entityManager->clear(null);
        }

        unset($siftedPreparedNotificationDataCollector);
        gc_collect_cycles();
    }

    private function changeExecutionStatusInNotificationJobId(Id $id): void
    {
        try {
            $notificationJobId = $this->njiGetterService->getById($id);
        } catch (NotificationJobIdDoesNotExistException $exception) {
            throw new LogicException($exception->getMessage(), 0, $exception);
        }
        $notificationJobId->changeExecutionStatus(ExecutionStatus::createCompletedStatus());

        $this->entityManager->persist($notificationJobId);
        $this->entityManager->flush();

        $this->entityManager->clear(null);
    }
}