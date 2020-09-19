<?php
declare(strict_types=1);

namespace App\Entity\Entity\NotificationJobId;

use App\Entity\ForEntity\AbstractCollector;

final class NotificationJobIdCollector extends AbstractCollector
{
    public static function createFulledCollector(NotificationJobId ...$notificationJobIds): self
    {
        return new self($notificationJobIds);
    }
}