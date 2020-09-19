<?php
declare(strict_types=1);

namespace App\Messenger\RabbitMQ;

final class AbstractInformer
{
    public const COMMON_NORMAL_BINDING_KEY = 'normalKey';

    public const COMMON_FAILURE_BINDING_KEY = 'failureKey';

    public const FLAG = AMQP_DURABLE;

    public const ATTRIBUTES = [];

    private function __construct()
    {
    }
}