<?php
declare(strict_types=1);

namespace App\Entity\ForEntity\EntityFeature\AbstractFeature;

abstract class AbstractDateTime extends AbstractDateTimeType
{
    protected function getFormat(): string
    {
       return self::DATE_TIME_FORMAT;
    }
}