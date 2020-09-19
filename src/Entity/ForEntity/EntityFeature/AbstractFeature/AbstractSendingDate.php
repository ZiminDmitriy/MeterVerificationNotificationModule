<?php
declare(strict_types=1);

namespace App\Entity\ForEntity\EntityFeature\AbstractFeature;

abstract class AbstractSendingDate extends AbstractDate
{
    public function __toString(): string
    {
        return $this->value;
    }
}