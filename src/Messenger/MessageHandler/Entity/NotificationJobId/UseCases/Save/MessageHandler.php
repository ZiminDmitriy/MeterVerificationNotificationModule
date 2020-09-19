<?php
declare(strict_types=1);

namespace App\Messenger\MessageHandler\Entity\NotificationJobId\UseCases\Save;

use App\Handler\Command\Entity\PreparedNotificationData\SendDataToCommunicationModuleCommandHandler;
use App\Messenger\Message\AbstractMessage;
use App\Messenger\Message\Entity\NotificationJobId\UseCases\Save\Message;
use App\Messenger\MessageHandler\AbstractAsyncMessageHandler;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessageHandler extends AbstractAsyncMessageHandler
{
    private SendDataToCommunicationModuleCommandHandler $sendDataToCommunicationModuleCommandHandler;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        MessageBusInterface $messageBus,
        SendDataToCommunicationModuleCommandHandler $sendDataToCommunicationModuleCommandHandler
    )
    {
        $this->sendDataToCommunicationModuleCommandHandler = $sendDataToCommunicationModuleCommandHandler;

        parent::__construct($eventDispatcher, $messageBus);
    }

    public function __invoke(Message $message)
    {
        $this->safelyHandle($message, Message::class);
    }

    /**
     * @param Message $message
     */
    protected function handle(AbstractMessage $message): void
    {
        $this->sendDataToCommunicationModuleCommandHandler->initializeInformationCollectingProcess(
            $message->getId(), $message->getSendingDate(), $message->getSendingDateTimeImmutable()
        );
    }
}