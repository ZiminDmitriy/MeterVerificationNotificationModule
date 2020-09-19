<?php
declare(strict_types=1);

namespace App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData;

use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\AbstractNotificationData;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\Contract;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterId;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterNextCheckDate;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\Email;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\MeterFactoryNumber;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\MeterModel;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\Phone;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractPreparedNotificationData extends AbstractNotificationData
{
    /**
     * @ORM\Column(name="abonent_email", type="AbstractPreparedNotificationDataEmailType",
     *      nullable=true
     * )
     */
    protected Email $email;

    /**
     * @ORM\Column(name="abonent_phone_number", type="AbstractPreparedNotificationDataPhoneType",
     *      nullable=true
     * )
     */
    protected Phone $phone;

    /**
     * @ORM\Column(name="meter_factory_number", type="AbstractPreparedNotificationDataMeterFactoryNumberType",
     *     nullable=true
     * )
     */
    protected MeterFactoryNumber $meterFactoryNumber;

    /**
     * @ORM\Column(name="meter_model", type="PreparedNotificationDataMeterModelType",
     *     nullable=true
     * )
     */
    protected MeterModel $meterModel;

    public function __construct(
        Contract $contract,
        MeterId $meterId,
        MeterNextCheckDate $meterNextCheckDate,
        Email $email,
        Phone $phone,
        MeterFactoryNumber $meterFactoryNumber,
        MeterModel $meterModel
    )
    {
        $this->email = $email;
        $this->phone = $phone;
        $this->meterFactoryNumber = $meterFactoryNumber;
        $this->meterModel = $meterModel;

        parent::__construct($contract, $meterId, $meterNextCheckDate);
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPhone(): Phone
    {
        return $this->phone;
    }

    public function getMeterFactoryNumber(): MeterFactoryNumber
    {
        return $this->meterFactoryNumber;
    }

    public function getMeterModel(): MeterModel
    {
        return $this->meterModel;
    }
}