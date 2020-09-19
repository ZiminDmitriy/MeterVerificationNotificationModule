<?php
declare(strict_types=1);

namespace App\Service\Entity\SiftedPreparedNotificationData;

use App\Entity\Entity\SiftedPreparedNotificationData\ForSiftedPreparedNotificationData\SendingDate;
use App\Entity\Entity\SiftedPreparedNotificationData\ForSiftedPreparedNotificationData\SendingDateCollector;
use App\Entity\Entity\SiftedPreparedNotificationData\SiftedPreparedNotificationDataCollector;
use App\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractSendingDate;
use App\Repository\SiftedPreparedNotificationDataRepository;

final class GetterService
{
    private SiftedPreparedNotificationDataRepository $siftedPreparedNotificationDataRepository;

    public function __construct(SiftedPreparedNotificationDataRepository $siftedPreparedNotificationDataRepository)
    {
        $this->siftedPreparedNotificationDataRepository = $siftedPreparedNotificationDataRepository;
    }

    public function getAllBySendingDate(
        AbstractSendingDate $sendingDate,
        int $limit,
        int $offset
    ): SiftedPreparedNotificationDataCollector
    {
        $siftedPreparedNotificationDataRegistry = $this->siftedPreparedNotificationDataRepository->findBy(
            ['sendingDate' => new SendingDate($sendingDate->getDateTimeImmutableValue())], [], $limit, $offset
        );

        return
            !empty($siftedPreparedNotificationDataRegistry) ?
                SiftedPreparedNotificationDataCollector::createFulledCollector(...$siftedPreparedNotificationDataRegistry) :
                SiftedPreparedNotificationDataCollector::createEmptyCollector();
    }

    public function getSendingDates(): SendingDateCollector
    {
        $sendingDateRegistry = $this->siftedPreparedNotificationDataRepository->getDistinctSendingDates();

        if (is_null($sendingDateRegistry)) {
            $sendingDateCollector = SendingDateCollector::createEmptyCollector();
        } else {
            $sendingDateCollector = SendingDateCollector::createFulledCollector(
                ...array_map(
                    static function (array $data): SendingDate {
                        return SendingDate::createFromString($data['sending_date']);
                    },
                    $sendingDateRegistry
                )
            );
        }

        return $sendingDateCollector;
    }

    public function countBySendingDate(AbstractSendingDate $sendingDate): int
    {
        return $this->siftedPreparedNotificationDataRepository->count(
            ['sendingDate' => new SendingDate($sendingDate->getDateTimeImmutableValue())]
        );
    }
}