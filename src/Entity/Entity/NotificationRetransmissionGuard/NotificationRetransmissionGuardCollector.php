<?php
declare(strict_types=1);

namespace App\Entity\Entity\NotificationRetransmissionGuard;

use App\Entity\ForEntity\AbstractCollector;

final class NotificationRetransmissionGuardCollector extends AbstractCollector
{
    public static function createFulledCollector(NotificationRetransmissionGuard ...$notificationRetransmissionGuards): self
    {
        return new self($notificationRetransmissionGuards);
    }
}