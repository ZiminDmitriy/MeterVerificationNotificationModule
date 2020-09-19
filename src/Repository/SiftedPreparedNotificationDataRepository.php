<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\Entity\SiftedPreparedNotificationData\ForSiftedPreparedNotificationData\Id;
use App\Entity\Entity\SiftedPreparedNotificationData\ForSiftedPreparedNotificationData\SendingDate;
use App\Entity\Entity\SiftedPreparedNotificationData\SiftedPreparedNotificationData;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\FetchMode;
use Doctrine\Persistence\ManagerRegistry;

final class SiftedPreparedNotificationDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SiftedPreparedNotificationData::class);
    }

    public function deleteById(Id $id): void
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->delete()
            ->from($this->_entityName, 'e', null)
            ->where("e.id = :id")
            ->setParameter('id', $id);

        $queryBuilder->getQuery()->getResult();
    }

    public function deleteByMeterIdAndSendingDate(MeterId $meterId, SendingDate $sendingDate): void
    {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder
            ->delete()
            ->from($this->_entityName, 'e', null)
            ->where('e.meterId = :meterId')
            ->andWhere("e.sendingDate = :sendingDate")
            ->setParameter('meterId', $meterId)
            ->setParameter('sendingDate', $sendingDate);

        $queryBuilder->getQuery()->getResult();
    }

    public function getDistinctSendingDates(): ?array
    {
        $query = "SELECT DISTINCT sending_date FROM sifted_prepared_notification_data;";

        $statement = $this->_em->getConnection()->executeQuery($query);
        $statement->setFetchMode(FetchMode::ASSOCIATIVE, null, null);

        $sendingDateRegistry = $statement->fetchAll(null, null, null);

        return !empty($sendingDateRegistry) ? $sendingDateRegistry : null;
    }
}