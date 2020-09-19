<?php
declare(strict_types=1);

namespace App\EventDispatcher\Event;

use Symfony\Contracts\EventDispatcher\Event;

class LoggerEvent extends Event
{
    public const EVENT_NAME = 'want.to.log';

    private array $context;

    private string $message;

    public function __construct(string $message, array $context)
    {
        $this->message = $message;
        $this->context = $context;
    }

    final public function getContext(): array
    {
        return $this->context;
    }

    final public function getMessage(): string
    {
        return $this->message;
    }
}