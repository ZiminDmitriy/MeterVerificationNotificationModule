<?php
declare(strict_types=1);

namespace App\Entity\ForEntity\EntityFeature\AbstractFeature;

use DateTimeImmutable;

abstract class AbstractDate extends AbstractDateTimeType
{
    public function __construct(DateTimeImmutable $dateTime)
    {
        parent::__construct($dateTime->setTime(0, 0, 0, 0));
    }

    protected function getFormat(): string
    {
        return self::DATE_FORMAT;
    }
}