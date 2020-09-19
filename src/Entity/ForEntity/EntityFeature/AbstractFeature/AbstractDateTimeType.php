<?php
declare(strict_types=1);

namespace App\Entity\ForEntity\EntityFeature\AbstractFeature;

use App\Entity\ForEntity\EntityFeature\Context\AbstractStringNotNullContext;
use DateTimeImmutable;

abstract class AbstractDateTimeType extends AbstractStringNotNullContext
{
    public const DATE_FORMAT = 'Y-m-d';

    public const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    public function __construct(DateTimeImmutable $dateTimeImmutable)
    {
        parent::__construct($dateTimeImmutable->format($this->getFormat()));
    }

    final public function getDateTimeImmutableValue(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->value);
    }

    /**
     * @return static
     */
    final public static function createFromString(string $dateTime): self
    {
        return new static(new DateTimeImmutable($dateTime, null));
    }

    abstract protected function getFormat(): string;
}