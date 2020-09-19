<?php
declare(strict_types=1);

namespace App\Entity\ForEntity\EntityFeature\Context;

abstract class AbstractFloatNullableContext
{
    protected ?float $value;

    public function __construct(?float $value)
    {
        $this->value = $value;
    }

    final public function getValue(): ?float
    {
        return $this->value;
    }
}