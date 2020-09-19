<?php
declare(strict_types=1);

namespace App\Handler\Controller\Api\Api\Entity\PreparedNotificationData\CommonController;

use App\Exception\Controller\Api\ApiException;
use App\Messenger\Message\Entity\PreparedNotificationData\UseCases\Save\Message as SaveMessage;
use App\Messenger\RabbitMQ\AbstractInformer;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Transport\AmqpExt\AmqpStamp;

final class AddHandler
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function add(array $preparedNotificationDataRegistry): void
    {
        $amqpStamp = new AmqpStamp(AbstractInformer::COMMON_NORMAL_BINDING_KEY, AbstractInformer::FLAG, AbstractInformer::ATTRIBUTES);

        foreach ($preparedNotificationDataRegistry as &$preparedNotificationData) {
            if (!is_array($preparedNotificationData)) {
                throw new ApiException('$preparedNotificationData value should be of type Array', 400, null);
            }

            $this->messageBus->dispatch(new SaveMessage($preparedNotificationData), [$amqpStamp]);
        }
    }
}