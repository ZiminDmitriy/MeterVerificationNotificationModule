<?php
declare(strict_types=1);

namespace App\Service\Entity\NotificationJobId;

use App\Entity\Entity\NotificationJobId\ForNotificationJobId\Id;
use App\Entity\Entity\NotificationJobId\NotificationJobId;
use App\Exception\Entity\Entity\NotificationJobId\NotificationJobIdDoesNotExistException;
use App\Repository\NotificationJobIdRepository;

final class GetterService
{
    private NotificationJobIdRepository $notificationJobIdRepository;

    public function __construct(NotificationJobIdRepository $notificationJobIdRepository)
    {
        $this->notificationJobIdRepository = $notificationJobIdRepository;
    }

    public function getById(Id $id): NotificationJobId
    {
        $notificationJobId = $this->notificationJobIdRepository->find($id);

        if (!$notificationJobId) {
            throw new NotificationJobIdDoesNotExistException('NotificationJobId does not exist', 0, null);
        }

        return $notificationJobId;
    }
}