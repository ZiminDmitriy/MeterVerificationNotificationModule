<?php
declare(strict_types=1);

namespace App\Messenger\Message\Entity\PreparedNotificationData\UseCases\Delete;

use App\Messenger\Message\AbstractAsyncMessage;

final class Message extends AbstractAsyncMessage
{
    private string $meterId;

    public function __construct(string $meterId)
    {
        $this->meterId = $meterId;
    }

    public function getMeterId(): string
    {
        return $this->meterId;
    }
}