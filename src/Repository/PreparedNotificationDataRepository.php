<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterId;
use App\Entity\Entity\PreparedNotificationData\PreparedNotificationData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class PreparedNotificationDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PreparedNotificationData::class);
    }

    public function deleteByMeterId(MeterId $meterId): void
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->delete()
            ->from($this->_entityName, 'e', null)
            ->where("e.meterId = :meterId")
            ->setParameter('meterId', $meterId);

        $queryBuilder->getQuery()->getResult();
    }
}