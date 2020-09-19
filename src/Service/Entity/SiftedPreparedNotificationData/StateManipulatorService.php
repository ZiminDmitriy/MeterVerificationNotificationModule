<?php
declare(strict_types=1);

namespace App\Service\Entity\SiftedPreparedNotificationData;

use App\Entity\Entity\PreparedNotificationData\PreparedNotificationData;
use App\Entity\Entity\SiftedPreparedNotificationData\ForSiftedPreparedNotificationData\Id;
use App\Entity\Entity\SiftedPreparedNotificationData\ForSiftedPreparedNotificationData\SendingDate;
use App\Entity\Entity\SiftedPreparedNotificationData\SiftedPreparedNotificationData;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterId;
use App\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractSendingDate;
use App\Repository\SiftedPreparedNotificationDataRepository;

final class StateManipulatorService
{
    private SiftedPreparedNotificationDataRepository $siftedPreparedNotificationDataRepository;

    public function __construct(SiftedPreparedNotificationDataRepository $siftedPreparedNotificationDataRepository)
    {
        $this->siftedPreparedNotificationDataRepository = $siftedPreparedNotificationDataRepository;
    }

    public function create(
        PreparedNotificationData $preparedNotificationData,
        AbstractSendingDate $sendingDate
    ): SiftedPreparedNotificationData
    {
        return new SiftedPreparedNotificationData(
            Id::create(),
            new SendingDate($sendingDate->getDateTimeImmutableValue()),
            $preparedNotificationData->getContract(),
            $preparedNotificationData->getMeterId(),
            $preparedNotificationData->getMeterNextCheckDate(),
            $preparedNotificationData->getEmail(),
            $preparedNotificationData->getPhone(),
            $preparedNotificationData->getMeterFactoryNumber(),
            $preparedNotificationData->getMeterModel()
        );
    }

    public function deleteById(Id $id): void
    {
        $this->siftedPreparedNotificationDataRepository->deleteById($id);
    }

    public function deleteByMeterIdAndSendingDate(MeterId $meterId, AbstractSendingDate $sendingDate): void
    {
        $this->siftedPreparedNotificationDataRepository->deleteByMeterIdAndSendingDate(
            $meterId, new SendingDate($sendingDate->getDateTimeImmutableValue())
        );
    }
}