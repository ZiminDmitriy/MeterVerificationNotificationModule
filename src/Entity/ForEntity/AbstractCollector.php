<?php
declare(strict_types=1);

namespace App\Entity\ForEntity;

use ArrayIterator;

abstract class AbstractCollector extends ArrayIterator
{
    protected function __construct(array $values)
    {
        parent::__construct($values, ArrayIterator::STD_PROP_LIST);
    }

    /**
     * @return static
     */
    public static function createEmptyCollector(): self
    {
        return new static([]);
    }

    // for overload
    abstract public static function createFulledCollector(): self;

    final public function isEmpty(): bool
    {
        return $this->count() == 0;
    }
}