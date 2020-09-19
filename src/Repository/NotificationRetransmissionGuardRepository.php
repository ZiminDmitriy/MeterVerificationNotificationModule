<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entity\NotificationRetransmissionGuard\NotificationRetransmissionGuard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class NotificationRetransmissionGuardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NotificationRetransmissionGuard::class);
    }
}