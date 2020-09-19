<?php
declare(strict_types=1);

namespace App\EventDispatcher\Event\Exception;

use App\EventDispatcher\Event\LoggerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class NotCatchingExceptionEvent extends LoggerEvent
{
    private ExceptionEvent $exceptionEvent;

    public function __construct(ExceptionEvent $exceptionEvent, array $context)
    {
        $this->exceptionEvent = $exceptionEvent;

        parent::__construct('', $context);
    }

    public function getExceptionEvent(): ExceptionEvent
    {
        return $this->exceptionEvent;
    }
}