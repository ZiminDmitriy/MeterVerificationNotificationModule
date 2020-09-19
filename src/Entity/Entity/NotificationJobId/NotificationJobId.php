<?php
declare(strict_types=1);

namespace App\Entity\Entity\NotificationJobId;

use App\Entity\Entity\NotificationJobId\ForNotificationJobId\ExecutionStatus;
use App\Entity\Entity\NotificationJobId\ForNotificationJobId\Id;
use App\Entity\Entity\NotificationJobId\ForNotificationJobId\MessagesQuantity;
use App\Entity\Entity\NotificationJobId\ForNotificationJobId\SendingDateTime;
use App\Entity\Entity\NotificationRetransmissionGuard\NotificationRetransmissionGuard;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use LogicException;

/**
 * @ORM\Entity(repositoryClass="App\Repository\NotificationJobIdRepository",
 *     readOnly=false
 * )
 * @ORM\Table(name="notification_jobid")
 * @ORM\ChangeTrackingPolicy(value="DEFERRED_IMPLICIT")
 */
class NotificationJobId
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="NotificationJobIdIdType", nullable=false)
     */
    private Id $id;

    /**
     * @ORM\Column(name="sending_date_time", type="NotificationJobIdSendingDateTimeType",
     *     nullable=false
     * )
     */
    private SendingDateTime $sendingDate;

    /**
     * @ORM\Column(name="execution_status", type="NotificationJobIdExecutionStatusType",
     *     nullable=false
     * )
     */
    private ExecutionStatus $executionStatus;

    /**
     * @ORM\Column(name="common_messages_quantity", type="NotificationJobIdMessagesQuantityType",
     *     nullable=false
     * )
     */
    private MessagesQuantity $commonMessagesQuantity;

    /**
     * @ORM\OneToOne(inversedBy="notificationJobId", cascade={"persist"}, fetch="EAGER",
     *     targetEntity="App\Entity\Entity\NotificationRetransmissionGuard\NotificationRetransmissionGuard"
     * )
     * @ORM\JoinColumn(name="notification_retransmission_guard_id", referencedColumnName="id",
     *      nullable=false
     * )
     */
    private NotificationRetransmissionGuard $notificationRetransmissionGuard;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Entity\ExecutedNotificationData\ExecutedNotificationData",
     *     mappedBy="notificationJobId", cascade={"persist"}, fetch="LAZY", orphanRemoval=false
     * )
     */
    private Collection $executedNotificationData;

    public function __construct(
        NotificationRetransmissionGuard $notificationRetransmissionGuard,
        Id $jobId,
        SendingDateTime $sendingDate,
        ExecutionStatus $executionStatus,
        MessagesQuantity $commonMessagesQuantity
    )
    {
        $this->notificationRetransmissionGuard = $notificationRetransmissionGuard;
        $this->id = $jobId;
        $this->sendingDate = $sendingDate;
        $this->executionStatus = $executionStatus;
        $this->commonMessagesQuantity = $commonMessagesQuantity;
        $this->executedNotificationData = new ArrayCollection();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getSendingDate(): SendingDateTime
    {
        return $this->sendingDate;
    }

    public function getCommonMessagesQuantity(): MessagesQuantity
    {
        return $this->commonMessagesQuantity;
    }

    public function getNotificationRetransmissionGuard(): NotificationRetransmissionGuard
    {
        return $this->notificationRetransmissionGuard;
    }

    public function getExecutionStatus(): ExecutionStatus
    {
        return $this->executionStatus;
    }

    public function changeExecutionStatus(ExecutionStatus $executionStatus): self
    {
        if ($this->executionStatus->getValue() == $executionStatus->getValue()) {
            throw new LogicException('The ExecutionStatus has not changed', 0, null);
        }

        $this->executionStatus = $executionStatus;

        return $this;
    }
}
