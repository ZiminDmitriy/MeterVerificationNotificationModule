<?php
declare(strict_types=1);

namespace App\Service\Entity\NotificationJobId;

use App\Entity\Entity\NotificationJobId\ForNotificationJobId\ExecutionStatus;
use App\Entity\Entity\NotificationJobId\ForNotificationJobId\Id;
use App\Entity\Entity\NotificationJobId\ForNotificationJobId\MessagesQuantity;
use App\Entity\Entity\NotificationJobId\ForNotificationJobId\SendingDateTime;
use App\Entity\Entity\NotificationJobId\NotificationJobId;
use App\Entity\Entity\NotificationRetransmissionGuard\NotificationRetransmissionGuard;
use DateTimeImmutable;

final class StateManipulatorService
{
    public function create(
        NotificationRetransmissionGuard $notificationRetransmissionGuard,
        int $jobId,
        DateTimeImmutable $sendingDateTime,
        int $commonMessagesQuantity
    ): NotificationJobId
    {
        return new NotificationJobId(
            $notificationRetransmissionGuard,
            new Id($jobId),
            new SendingDateTime($sendingDateTime),
            ExecutionStatus::createInExecutionStatus(),
            new MessagesQuantity($commonMessagesQuantity)
        );
    }
}