<?php
declare(strict_types=1);

namespace App\Messenger\MessageHandler;

use App\EventDispatcher\Event\Exception\CatchingExceptionEvent;
use App\Messenger\Message\AbstractMessage;
use Exception;

abstract class AbstractSyncMessageHandler extends AbstractMessageHandler
{
    final protected function handleCatchingException(Exception $exception, AbstractMessage $message): void
    {
        $this->eventDispatcher->dispatch(new CatchingExceptionEvent($exception, []), CatchingExceptionEvent::EVENT_NAME);

        throw $exception;
    }
}