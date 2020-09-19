<?php
declare(strict_types=1);

namespace App\Entity\Entity\SiftedPreparedNotificationData;

use App\Entity\ForEntity\AbstractCollector;

final class SiftedPreparedNotificationDataCollector extends AbstractCollector
{
    public static function createFulledCollector(
        SiftedPreparedNotificationData ...$siftedPreparedNotificationData
    ): self
    {
       return new self($siftedPreparedNotificationData);
    }
}