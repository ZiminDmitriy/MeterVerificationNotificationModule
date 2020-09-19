<?php
declare(strict_types=1);

namespace App\Messenger\MessageHandler;

use App\Messenger\Message\AbstractMessage;
use Exception;
use InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class AbstractMessageHandler implements MessageHandlerInterface            // registered with auto-configuration
{
    protected EventDispatcherInterface $eventDispatcher;

    protected MessageBusInterface $messageBus;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        MessageBusInterface $messageBus
    )
    {
        $this->messageBus = $messageBus;
        $this->eventDispatcher = $eventDispatcher;
    }

    abstract protected function handle(AbstractMessage $message): void;

    abstract protected function handleCatchingException(Exception $exception, AbstractMessage $message): void;

    final protected function safelyHandle(AbstractMessage $message, string $validMessageType): void
    {
        $this->validateMessageType($message, $validMessageType);

        try {
            $this->handle($message);
        } catch (Exception $exception) {
            $this->handleCatchingException($exception, $message);
        }
    }

    private function validateMessageType(AbstractMessage $message, string $validMessageType): void
    {
        if (!($message instanceof $validMessageType)) {
            throw new InvalidArgumentException(
                sprintf('Instance must be of type %s, %s given', $validMessageType, get_class($message)), 0, null
            );
        }
    }
}