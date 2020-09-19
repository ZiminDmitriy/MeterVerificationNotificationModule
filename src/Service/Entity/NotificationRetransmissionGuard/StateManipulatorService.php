<?php
declare(strict_types=1);

namespace App\Service\Entity\NotificationRetransmissionGuard;

use App\Entity\Entity\NotificationRetransmissionGuard\ForNotificationRetransmissionGuard\Id;
use App\Entity\Entity\NotificationRetransmissionGuard\ForNotificationRetransmissionGuard\SendingDate;
use App\Entity\Entity\NotificationRetransmissionGuard\NotificationRetransmissionGuard;
use App\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractSendingDate;

final class StateManipulatorService
{
    public function create(AbstractSendingDate $sendingDate): NotificationRetransmissionGuard
    {
        return new NotificationRetransmissionGuard(Id::create(), new SendingDate($sendingDate->getDateTimeImmutableValue()));
    }
}