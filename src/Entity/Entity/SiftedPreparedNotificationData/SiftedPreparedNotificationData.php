<?php
declare(strict_types=1);

namespace App\Entity\Entity\SiftedPreparedNotificationData;

use App\Entity\Entity\SiftedPreparedNotificationData\ForSiftedPreparedNotificationData\Id;
use App\Entity\Entity\SiftedPreparedNotificationData\ForSiftedPreparedNotificationData\SendingDate;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\Contract;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterId;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterNextCheckDate;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\AbstractPreparedNotificationData;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\Email;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\MeterFactoryNumber;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\MeterModel;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\Phone;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SiftedPreparedNotificationDataRepository",
 *      readOnly=false
 * )
 * @ORM\Table(name="sifted_prepared_notification_data",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="meter_id_unique", columns={"meter_id"})
 *     },
 *     indexes={
 *         @ORM\Index(name="sending_date_index", columns={"sending_date"})
 *     }
 * )
 * @ORM\ChangeTrackingPolicy(value="DEFERRED_IMPLICIT")
 */
class SiftedPreparedNotificationData extends AbstractPreparedNotificationData
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="SiftedPreparedNotificationDataIdType", nullable=false)
     */
    private Id $id;

    /**
     * @ORM\Column(name="sending_date", type="SiftedPreparedNotificationDataSendingDateType",
     *     nullable=false
     * )
     */
    private SendingDate $sendingDate;
    
    public function __construct(
        Id $id,
        SendingDate $sendingDate,
        Contract $contract,
        MeterId $meterId,
        MeterNextCheckDate $meterNextCheckDate,
        Email $email,
        Phone $phone,
        MeterFactoryNumber $meterFactoryNumber,
        MeterModel $meterModel
    )
    {
        $this->id = $id;
        $this->sendingDate = $sendingDate;

        parent::__construct($contract, $meterId, $meterNextCheckDate, $email, $phone, $meterFactoryNumber, $meterModel);
    }

    public function getId(): Id
    {
        return $this->id;
    }

    /**
     * @Groups({"send_to_communicationModule"})
     */
    final public function getContractValue(): string
    {
        return $this->contract->getValue();
    }

    /**
     * @Groups({"send_to_communicationModule"})
     */
    final public function getMeterIdValue(): string
    {
        return $this->meterId->getValue();
    }

    /**
     * @Groups({"send_to_communicationModule"})
     */
    final public function getMeterNextCheckDateValue(): string
    {
        return $this->meterNextCheckDate->getValue();
    }

    /**
     * @Groups({"send_to_communicationModule"})
     */
    final public function getMeterFactoryNumberValue(): ?string
    {
        return $this->meterFactoryNumber->getValue();
    }

    /**
     * @Groups({"send_to_communicationModule"})
     */
    final public function getMeterModelValue(): ?string
    {
        return $this->meterModel->getValue();
    }

    /**
     * @Groups({"send_to_communicationModule"})
     */
    final public function getEmailValue(): ?string
    {
        return $this->email->getValue();
    }

    /**
     * @Groups({"send_to_communicationModule"})
     */
    final public function getPhoneValue(): ?string
    {
        return $this->phone->getValue();
    }
}