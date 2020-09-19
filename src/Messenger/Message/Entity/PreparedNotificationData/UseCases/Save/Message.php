<?php
declare(strict_types=1);

namespace App\Messenger\Message\Entity\PreparedNotificationData\UseCases\Save;

use App\Messenger\Message\AbstractAsyncMessage;

final class Message extends AbstractAsyncMessage
{
    private array $preparedNotificationData;

    public function __construct(array $preparedNotificationData)
    {
        $this->preparedNotificationData = $preparedNotificationData;
    }

    public function getPreparedNotificationData(): array
    {
        return $this->preparedNotificationData;
    }
}