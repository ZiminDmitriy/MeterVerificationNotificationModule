<?php
declare(strict_types=1);

namespace App\Util\Common;

use Exception;
use DateTimeImmutable;

final class DateTimeFormatChecker
{
    public function isValidFormat(string $dateTime): bool
    {
        try {
            new DateTimeImmutable($dateTime, null);

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}
