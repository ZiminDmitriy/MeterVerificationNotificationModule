<?php
declare(strict_types=1);

namespace App\Service\Entity\NotificationRetransmissionGuard;

use App\Entity\Entity\NotificationRetransmissionGuard\ForNotificationRetransmissionGuard\SendingDate;
use App\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractSendingDate;
use App\Repository\NotificationRetransmissionGuardRepository;

final class GetterService
{
    private NotificationRetransmissionGuardRepository $notificationRetransmissionGuardRepository;
    
    public function __construct(NotificationRetransmissionGuardRepository $notificationRetransmissionGuardRepository)
    {
        $this->notificationRetransmissionGuardRepository = $notificationRetransmissionGuardRepository;
    }

    public function hasBySendingDate(AbstractSendingDate $sendingDate): bool
    {
        return
            !empty(
                $this->notificationRetransmissionGuardRepository->findBy(
                    ['sendingDate' => new SendingDate($sendingDate->getDateTimeImmutableValue())], null, null, null
                )
            );
    }
}