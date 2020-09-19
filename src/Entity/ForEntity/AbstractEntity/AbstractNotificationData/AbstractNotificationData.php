<?php
declare(strict_types=1);

namespace App\Entity\ForEntity\AbstractEntity\AbstractNotificationData;

use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\Contract;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterId;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterNextCheckDate;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractNotificationData
{
    /**
     * @ORM\Column(name="contract", type="AbstractNotificationDataContractType",
     *     nullable=false
     * )
     */
    protected Contract $contract;

    /**
     * @ORM\Column(name="meter_id", type="AbstractNotificationDataMeterIdType",
     *     nullable=false
     * )
     */
    protected MeterId $meterId;

    /**
     * @ORM\Column(name="meter_next_check_date", type="AbstractNotificationDataMeterNextCheckDateType",
     *      nullable=false
     * )
     */
    protected MeterNextCheckDate $meterNextCheckDate;

    public function __construct(Contract $contract, MeterId $meterId, MeterNextCheckDate $meterNextCheckDate)
    {
        $this->contract = $contract;
        $this->meterId = $meterId;
        $this->meterNextCheckDate = $meterNextCheckDate;
    }

    public function getContract(): Contract
    {
        return $this->contract;
    }

    public function getMeterId(): MeterId
    {
        return $this->meterId;
    }

    public function getMeterNextCheckDate(): MeterNextCheckDate
    {
        return $this->meterNextCheckDate;
    }
}