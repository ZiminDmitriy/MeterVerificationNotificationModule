<?php
declare(strict_types=1);

namespace App\Service\Entity\PreparedNotificationData;

use App\Entity\Entity\PreparedNotificationData\ForPreparedNotificationData\Id;
use App\Entity\Entity\PreparedNotificationData\PreparedNotificationData;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\Contract;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterId;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterNextCheckDate;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\Email;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\MeterFactoryNumber;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\MeterModel;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\Phone;
use App\Repository\PreparedNotificationDataRepository;
use DateTimeImmutable;

final class StateManipulatorService
{
    private PreparedNotificationDataRepository $preparedNotificationDataRepository;

    public function __construct(PreparedNotificationDataRepository $preparedNotificationDataRepository)
    {
        $this->preparedNotificationDataRepository = $preparedNotificationDataRepository;
    }

    public function create(
        string $contract,
        string $meterId,
        string $meterNextCheckDate,
        ?string $email,
        ?string $phone,
        ?string $meterFactoryNumber,
        ?string $meterModel
    ): PreparedNotificationData
    {
        return new PreparedNotificationData(
            Id::create(),
            new Contract($contract),
            new MeterId($meterId),
            new MeterNextCheckDate(new DateTimeImmutable($meterNextCheckDate, null)),
            new Email($email),
            new Phone($phone),
            new MeterFactoryNumber($meterFactoryNumber),
            new MeterModel($meterModel)
        );
    }

    public function deleteByMeterId(MeterId $meterId): void
    {
        $this->preparedNotificationDataRepository->deleteByMeterId($meterId);
    }

    public function delete(PreparedNotificationData $preparedNotificationData): void
    {
        $this->deleteByMeterId($preparedNotificationData->getMeterId());
    }
}