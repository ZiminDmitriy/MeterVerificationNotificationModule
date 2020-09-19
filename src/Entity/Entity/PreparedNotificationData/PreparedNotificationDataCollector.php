<?php
declare(strict_types=1);

namespace App\Entity\Entity\PreparedNotificationData;

use App\Entity\ForEntity\AbstractCollector;

final class PreparedNotificationDataCollector extends AbstractCollector
{
    public static function createFulledCollector(PreparedNotificationData ...$preparedNotificationData): self
    {
        return new self($preparedNotificationData);
    }
}