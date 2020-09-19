<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entity\ExecutedNotificationData\ExecutedNotificationData;
use App\Entity\Entity\NotificationJobId\NotificationJobId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ExecutedNotificationDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExecutedNotificationData::class);
    }

    public function getByNotificationJobId(NotificationJobId $notificationJobId, int $limit, int $offset): array
    {
        return $this->findBy(['notificationJobId' => $notificationJobId], null, $limit, $offset);
    }

}