<?php
declare(strict_types=1);

namespace App\Entity\Entity\NotificationJobId\ForNotificationJobId;

use App\Entity\ForEntity\EntityFeature\Context\AbstractStringNotNullContext;

final class ExecutionStatus extends AbstractStringNotNullContext
{
    public const IN_EXECUTION_STATUS = 'in_execution';

    public const COMPLETED_STATUS = 'completed';

    public static function createInExecutionStatus(): self
    {
        return new self(self::IN_EXECUTION_STATUS);
    }

    public static function createCompletedStatus(): self
    {
        return new self(self::COMPLETED_STATUS);
    }
}