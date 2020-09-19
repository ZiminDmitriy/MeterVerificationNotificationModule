<?php
declare(strict_types=1);

namespace App\Messenger\MessageHandler\Entity\PreparedNotificationData\UseCases\Delete;

use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterId;
use App\Messenger\Message\AbstractMessage;
use App\Messenger\Message\Entity\PreparedNotificationData\UseCases\Delete\Message as DeleteMessage;
use App\Messenger\MessageHandler\AbstractAsyncMessageHandler;
use App\Service\Entity\PreparedNotificationData\StateManipulatorService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessageHandler extends AbstractAsyncMessageHandler
{
    private StateManipulatorService $stateManipulatorService;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        MessageBusInterface $messageBus,
        StateManipulatorService $stateManipulatorService
    )
    {
        $this->stateManipulatorService = $stateManipulatorService;

        parent::__construct($eventDispatcher, $messageBus);
    }

    public function __invoke(DeleteMessage $message)
    {
        $this->safelyHandle($message, DeleteMessage::class);
    }

    /**
     * @param DeleteMessage $message
     */
    protected function handle(AbstractMessage $message): void
    {
        $this->stateManipulatorService->deleteByMeterId(new MeterId($message->getMeterId()));
    }
}