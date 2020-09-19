<?php
declare(strict_types=1);

namespace App\Entity\Entity\ExecutedNotificationData;

use App\Entity\Entity\ExecutedNotificationData\ForExecutedNotificationData\Id;
use App\Entity\Entity\NotificationJobId\NotificationJobId;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\AbstractNotificationData;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\Contract;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterId;
use App\Entity\ForEntity\AbstractEntity\AbstractNotificationData\ForAbstractNotificationData\MeterNextCheckDate;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExecutedNotificationDataRepository",
 *     readOnly=true
 * )
 * @ORM\Table(name="executed_notification_data",
 *     indexes={
 *         @ORM\Index(name="contract_index", columns={"contract"})
 *     }
 * )
 * @ORM\ChangeTrackingPolicy(value="DEFERRED_IMPLICIT")
 */
class ExecutedNotificationData extends AbstractNotificationData
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="ExecutedNotificationDataIdType", nullable=false)
     */
    private Id $id;

    /**
     * @ORM\ManyToOne(
     *     targetEntity="App\Entity\Entity\NotificationJobId\NotificationJobId",
     *     inversedBy="id", fetch="LAZY", cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="notification_jobid_id", referencedColumnName="id",
     *     nullable=false, onDelete="CASCADE"
     * )
     */
    private NotificationJobId $notificationJobId;

    public function __construct(
        NotificationJobId $notificationJobId,
        Id $id,
        Contract $contract,
        MeterId $meterId,
        MeterNextCheckDate $meterNextCheckDate
    )
    {
        $this->notificationJobId = $notificationJobId;
        $this->id = $id;

        parent::__construct($contract, $meterId, $meterNextCheckDate);
    }

    public function getNotificationJobId(): NotificationJobId
    {
        return $this->notificationJobId;
    }
}