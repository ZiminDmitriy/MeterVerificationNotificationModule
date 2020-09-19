<?php
declare(strict_types=1);

namespace App\Entity\Entity\PreparedNotificationData;

use App\Entity\Entity\PreparedNotificationData\ForPreparedNotificationData\Id;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\Contract;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterId;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterNextCheckDate;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\AbstractPreparedNotificationData;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\Email;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\MeterFactoryNumber;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\MeterModel;
use App\Entity\ForEntity\AbstractEntity\AbstractPreparedNotificationData\ForAbstractPreparedNotificationData\Phone;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PreparedNotificationDataRepository", readOnly=false)
 * @ORM\Table(name="prepared_notification_data",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="meter_id_unique", columns={"meter_id"})
 *     }
 * )
 * @ORM\ChangeTrackingPolicy(value="DEFERRED_IMPLICIT")
 *
 */
class PreparedNotificationData extends AbstractPreparedNotificationData
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="PreparedNotificationDataIdType", nullable=false)
     */
    private Id $id;

    public function __construct(
        Id $id,
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

        parent::__construct($contract, $meterId, $meterNextCheckDate, $email, $phone, $meterFactoryNumber, $meterModel);
    }
}