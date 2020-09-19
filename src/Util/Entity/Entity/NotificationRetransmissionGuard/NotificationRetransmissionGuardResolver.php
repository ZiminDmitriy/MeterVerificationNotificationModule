<?php
declare(strict_types=1);

namespace App\Util\Entity\Entity\NotificationRetransmissionGuard;

use App\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractSendingDate;
use App\Service\Entity\NotificationRetransmissionGuard\GetterService;

final class NotificationRetransmissionGuardResolver
{
    private GetterService $getterService;

    public function __construct(GetterService $getterService)
    {
        $this->getterService = $getterService;
    }

    public function isUnderGuard(AbstractSendingDate $sendingDate): bool
    {

        return $this->getterService->hasBySendingDate($sendingDate);
    }
}