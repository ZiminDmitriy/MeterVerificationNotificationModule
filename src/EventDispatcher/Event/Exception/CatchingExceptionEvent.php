<?php
declare(strict_types=1);

namespace App\EventDispatcher\Event\Exception;

use App\EventDispatcher\Event\LoggerEvent;
use Throwable;

final class CatchingExceptionEvent extends LoggerEvent
{
    private Throwable $exception;

    public function __construct(Throwable $exception, array $context)
    {
        $this->exception = $exception;

        parent::__construct('', $context);
    }

    public function getException(): Throwable
    {
        return $this->exception;
    }
}