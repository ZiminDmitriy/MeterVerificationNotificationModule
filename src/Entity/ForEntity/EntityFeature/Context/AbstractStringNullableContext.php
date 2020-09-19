<?php
declare(strict_types=1);

namespace App\Entity\ForEntity\EntityFeature\Context;

use LogicException;

abstract class AbstractStringNullableContext extends AbstractStringContext
{
    protected ?string $value;

    public function __construct(?string $value)
    {
        $this->value = $value;
    }

    public function __toString(): string
    {
        if (is_null($this->value)) {
            throw new LogicException('Should not convert null-value to String', 0, null);
        }

        return $this->value;
    }

    final public function getValue(): ?string
    {
        return $this->value;
    }
}