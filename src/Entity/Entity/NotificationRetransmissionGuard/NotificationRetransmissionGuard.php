<?php
declare(strict_types=1);

namespace App\Entity\Entity\NotificationRetransmissionGuard;

use App\Entity\Entity\NotificationRetransmissionGuard\ForNotificationRetransmissionGuard\SendingDate;
use App\Entity\Entity\NotificationRetransmissionGuard\ForNotificationRetransmissionGuard\Id;
use App\Entity\Entity\NotificationJobId\NotificationJobId;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationRetransmissionGuardRepository",
 *     readOnly=true
 * )
 * @ORM\Table(name="notification_retransmission_guard",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="sending_date_unique", columns={"sending_date"})
 *     }
 * )
 * @ORM\ChangeTrackingPolicy(value="DEFERRED_IMPLICIT")
 */
class NotificationRetransmissionGuard
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="NotificationRetransmissionGuardIdType",
     *      nullable=false
     * )
     */
    private Id $id;

    /**
     * @ORM\Column(name="sending_date", nullable=false,
     *     type="NotificationRetransmissionGuardSendingDateType"
     * )
     */
    private SendingDate $sendingDate;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Entity\NotificationJobId\NotificationJobId",
     *     mappedBy="notificationRetransmissionGuard", fetch="EAGER", cascade={"persist"}
     * )
     */
    private NotificationJobId $notificationJobId;

    public function __construct(Id $id, SendingDate $sendingDate)
    {
        $this->id = $id;
        $this->sendingDate = $sendingDate;
    }

    public function setNotificationJobId(NotificationJobId $notificationJobId): self
    {
        $this->notificationJobId = $notificationJobId;

        return $this;
    }

    public function getSendingDate(): SendingDate
    {
        return $this->sendingDate;
    }

    public function getNotificationJobId(): NotificationJobId
    {
        return $this->notificationJobId;
    }
}