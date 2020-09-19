<?php
declare(strict_types=1);

namespace App\Entity\ForEntity\EntityFeature\Context;

use LogicException;

abstract class AbstractIntegerNullableContext extends AbstractIntegerContext
{
    protected ?int $value;

    public function __construct(?int $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        if (is_null($this->value)) {
            throw new LogicException('Should not convert null-value to String', 0, null);
        }

        return (string)$this->value;
    }

    final public function getValue(): ?int
    {
        return $this->value;
    }
}