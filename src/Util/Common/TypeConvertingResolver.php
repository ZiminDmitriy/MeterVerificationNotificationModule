<?php
declare(strict_types=1);

namespace App\Util\Common;

final class TypeConvertingResolver
{
    public function canConvertToInteger(string $convertingInteger): bool
    {
        if (($convertingInteger !== '0' && (int) $convertingInteger == 0)
            || \strlen($convertingInteger) !== \strlen((string)((int)$convertingInteger))
        ) {
            return false;
        }

        return true;
    }

    public function canConvertToBoolean(string $convertingToBoolean): bool
    {
        // '0' and ''(empty string) string values will be converted to bool(false), others string values will
        // be converted to bool(true), but we need to convert to bool(true) only '1' string value
        $valuesConvertedToString = ['1', '0', ''];

        return in_array($convertingToBoolean, $valuesConvertedToString, true);
    }
}
