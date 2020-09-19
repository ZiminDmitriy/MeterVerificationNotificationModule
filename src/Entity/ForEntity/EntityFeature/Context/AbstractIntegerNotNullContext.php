<?php
declare(strict_types=1);

namespace App\Entity\ForEntity\EntityFeature\Context;

abstract class AbstractIntegerNotNullContext extends AbstractIntegerContext
{
    protected int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        return (string)$this->value;
    }

    final public function getValue(): int
    {
        return $this->value;
    }
}