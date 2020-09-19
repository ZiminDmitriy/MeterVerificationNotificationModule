<?php
declare(strict_types=1);

namespace App\Handler\Controller\Api\Api\Entity\PreparedNotificationData\CommonController;

use App\Exception\Controller\Api\ApiException;
use App\Messenger\Message\Entity\PreparedNotificationData\UseCases\Delete\Message as DeleteMessage;
use App\Messenger\RabbitMQ\AbstractInformer;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;

final class DeleteHandler
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function delete(array $meterIdRegister): void
    {
        $amqpStamp = new AmqpStamp(AbstractInformer::COMMON_NORMAL_BINDING_KEY, AbstractInformer::FLAG, AbstractInformer::ATTRIBUTES);

        foreach ($meterIdRegister as $meterId) {
            if (!is_string($meterId)) {
                throw new ApiException('$meterId value should be of type String', 400, null);
            }

            $this->messageBus->dispatch(new DeleteMessage($meterId), [$amqpStamp]);
        }
    }
}