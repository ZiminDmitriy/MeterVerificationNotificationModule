<?php
declare(strict_types=1);

namespace App\Doctrine\Type\Entity\ForEntity\AbstractEntity\AbstractNotificationData;

use App\Doctrine\Type\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractDateType;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterNextCheckDate;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;

final class AbstractNotificationDataMeterNextCheckDateType extends AbstractDateType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): MeterNextCheckDate
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException(
                sprintf('$value in %s must be of type String', get_called_class()), 0, null
            );
        }

        return MeterNextCheckDate::createFromString($value);
    }
}