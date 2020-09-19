<?php
declare(strict_types=1);

namespace App\Entity\ForEntity\EntityFeature\Context;

abstract class AbstractBooleanNotNullContext extends AbstractBooleanContext
{
    protected bool $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    final public function getValue(): bool
    {
        return $this->value;
    }
}