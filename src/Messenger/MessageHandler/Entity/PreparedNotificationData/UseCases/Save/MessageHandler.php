<?php
declare(strict_types=1);

namespace App\Messenger\MessageHandler\Entity\PreparedNotificationData\UseCases\Save;

use App\Entity\Entity\PreparedNotificationData\PreparedNotificationData;
use App\EventDispatcher\Event\LoggerEvent;
use App\Messenger\Message\AbstractMessage;
use App\Messenger\Message\Entity\PreparedNotificationData\UseCases\Save\Message as SaveMessage;
use App\Messenger\MessageHandler\AbstractAsyncMessageHandler;
use App\Service\Entity\PreparedNotificationData\StateManipulatorService;
use App\Util\Common\DateTimeFormatChecker;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessageHandler extends AbstractAsyncMessageHandler
{
    private EntityManagerInterface $entityManager;

    private StateManipulatorService $stateManipulatorService;

    private DateTimeFormatChecker $dateTimeFormatChecker;

    public function __construct(
        EntityManagerInterface $entityManager,
        EventDispatcherInterface $eventDispatcher,
        MessageBusInterface $messageBus,
        StateManipulatorService $stateManipulatorService,
        DateTimeFormatChecker $dateTimeFormatChecker
    )
    {
        $this->entityManager = $entityManager;
        $this->stateManipulatorService = $stateManipulatorService;
        $this->dateTimeFormatChecker = $dateTimeFormatChecker;

        parent::__construct($eventDispatcher, $messageBus);
    }

    public function __invoke(SaveMessage $message)
    {
        $this->safelyHandle($message, SaveMessage::class);
    }

    /**
     * @param SaveMessage $message
     */
    protected function handle(AbstractMessage $message): void
    {
        if (!$this->isFulledData($preparedNotificationData = $message->getPreparedNotificationData())) {
            $this->eventDispatcher->dispatch(
                new LoggerEvent('PreparedNotificationData is not fulled', $preparedNotificationData),
                LoggerEvent::EVENT_NAME
            );

            return;
        }

        $preparedNotificationData = $message->getPreparedNotificationData();
        $preparedNotificationData = $this->stateManipulatorService->create(
            $preparedNotificationData['contract'],
            $preparedNotificationData['meterId'],
            $preparedNotificationData['meterNextCheckDate'],
            $preparedNotificationData['email'],
            $preparedNotificationData['phone'],
            $preparedNotificationData['meterFactoryNumber'],
            $preparedNotificationData['meterModel']
        );

        $this->safelySavePND($preparedNotificationData);
    }

    private function isFulledData(array $preparedNotificationData): bool
    {
        if (!key_exists('contract', $preparedNotificationData) || !key_exists('meterId', $preparedNotificationData)
            || !key_exists('meterNextCheckDate', $preparedNotificationData) || !key_exists('email', $preparedNotificationData)
            || !key_exists('phone', $preparedNotificationData) || !key_exists('meterFactoryNumber', $preparedNotificationData)
            || !key_exists('meterModel', $preparedNotificationData)
            || !$this->dateTimeFormatChecker->isValidFormat($preparedNotificationData['meterNextCheckDate'])) {
            return false;
        }

        return true;
    }

    private function safelySavePND(PreparedNotificationData $preparedNotificationData): void
    {
        $this->entityManager->persist($preparedNotificationData);

        $this->entityManager->beginTransaction();
        try {
            $this->stateManipulatorService->delete($preparedNotificationData);

            $this->entityManager->flush();
            $this->entityManager->commit();
        } catch (Exception $exception) {
            $this->entityManager->rollback();

            throw $exception;
        }

        $this->entityManager->clear(null);
    }
}