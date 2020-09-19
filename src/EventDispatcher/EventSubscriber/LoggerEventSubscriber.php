<?php
declare(strict_types=1);

namespace App\EventDispatcher\EventSubscriber;

use App\EventDispatcher\Event\Exception\CatchingExceptionEvent;
use App\EventDispatcher\Event\Exception\NotCatchingExceptionEvent;
use App\EventDispatcher\Event\LoggerEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class LoggerEventSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoggerEvent::EVENT_NAME => 'writeLog'
        ];
    }

    public function writeLog(LoggerEvent $event, string $name, EventDispatcherInterface $eventDispatcher): void
    {

        $exception = null;
        if ($event instanceof NotCatchingExceptionEvent) {
            $exception = $event->getExceptionEvent()->getThrowable();
        }
        if ($event instanceof CatchingExceptionEvent) {
            $exception = $event->getException();
        }
        if ($exception) {
            $this->logger->error($exception->getMessage(),[$exception]);

            return;
        }

        $this->logger->info($event->getMessage(), $event->getContext());
    }
}