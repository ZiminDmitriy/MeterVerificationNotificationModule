<?php
declare(strict_types=1);

namespace App\Entity\ForEntity\EntityFeature\AbstractFeature;

use App\Entity\ForEntity\EntityFeature\Context\AbstractStringNotNullContext;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractId extends AbstractStringNotNullContext
{
    public function __construct(UuidInterface $id)
    {
        parent::__construct($id->toString());
    }

    /**
     * @return static
     */
    final public static function createFromString(string $id): self
    {
        return new static (Uuid::fromString($id));
    }

    /**
     * @return static
     */
    final public static function create(): self
    {
        return new static(Uuid::uuid4());
    }
}