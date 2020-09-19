<?php
declare(strict_types=1);

namespace App\Service\Entity\ExecutedNotificationData;

use App\Entity\Entity\ExecutedNotificationData\ExecutedNotificationData;
use App\Entity\Entity\ExecutedNotificationData\ForExecutedNotificationData\Id;
use App\Entity\Entity\NotificationJobId\NotificationJobId;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\Contract;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterId;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterNextCheckDate;

final class StateManipulatorService
{
    public function create(
        NotificationJobId $notificationJobId,
        Contract $contract,
        MeterId $meterId, 
        MeterNextCheckDate $meterNextCheckDate
    ): ExecutedNotificationData
    {
        return new ExecutedNotificationData($notificationJobId, Id::create(), $contract, $meterId, $meterNextCheckDate);
    }
}