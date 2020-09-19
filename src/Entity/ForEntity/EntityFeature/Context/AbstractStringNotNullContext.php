<?php
declare(strict_types=1);

namespace App\Entity\ForEntity\EntityFeature\Context;

abstract class AbstractStringNotNullContext extends AbstractStringContext
{
    protected string $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }

    final public function getValue(): string
    {
        return $this->value;
    }
}