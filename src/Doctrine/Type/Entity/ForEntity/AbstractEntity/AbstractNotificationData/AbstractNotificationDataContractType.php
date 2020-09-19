<?php
declare(strict_types=1);

namespace App\Doctrine\Type\Entity\ForEntity\AbstractEntity\AbstractNotificationData;

use App\Doctrine\Type\Entity\ForEntity\EntityFeature\Context\AbstractStringNotNullContextType;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\Contract;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use InvalidArgumentException;

final class AbstractNotificationDataContractType extends AbstractStringNotNullContextType
{
    public function convertToPHPValue($value, AbstractPlatform $platform): Contract
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException(
                sprintf('$value in %s must be of type String', get_called_class()), 0, null
            );
        }

        return new Contract($value);
    }
}