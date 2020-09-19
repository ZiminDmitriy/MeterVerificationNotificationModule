<?php
declare(strict_types=1);

namespace App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData;

use App\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractDate;

final class MeterNextCheckDate extends AbstractDate
{
    public function __toString(): string
    {
        return $this->value;
    }
}