<?php
declare(strict_types=1);

namespace App\Serializer\NameConverter\Entity\Entity\SiftedPreparedNotificationData;

use App\Entity\Entity\SiftedPreparedNotificationData\SiftedPreparedNotificationData;
use InvalidArgumentException;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;

final class NameConverter implements NameConverterInterface
{
    private const METHOD_POSTFIX = 'Value';

    public function normalize(string $propertyName): string
    {
        if (substr($propertyName, (-1) * strlen(self::METHOD_POSTFIX)) == self::METHOD_POSTFIX) {
            return substr($propertyName, 0, (-1) * strlen(self::METHOD_POSTFIX));
        } else {
            throw new InvalidArgumentException(
                sprintf(
                    'Method name "get%s" is not supported in %s for %s',
                    ucfirst($propertyName), SiftedPreparedNotificationData::class, get_called_class()
                ), 0, null
            );
        }
    }

    public function denormalize(string $propertyName): string
    {
        return sprintf('%s%s', $propertyName, self::METHOD_POSTFIX);
    }
}