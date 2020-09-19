<?php
declare(strict_types=1);

namespace App\Messenger\MessageHandler;

use App\EventDispatcher\Event\Exception\CatchingExceptionEvent;
use App\Messenger\Message\AbstractMessage;
use App\Messenger\RabbitMQ\AbstractInformer;
use Exception;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;

abstract class AbstractAsyncMessageHandler extends AbstractMessageHandler
{
    final protected function handleCatchingException(Exception $exception, AbstractMessage $message): void
    {
        $this->eventDispatcher->dispatch(new CatchingExceptionEvent($exception, []), CatchingExceptionEvent::EVENT_NAME);

        sleep(1);

        $this->messageBus->dispatch(
            $message,
            [new AmqpStamp(AbstractInformer::COMMON_NORMAL_BINDING_KEY, AbstractInformer::FLAG, AbstractInformer::ATTRIBUTES)]
        );
    }
}