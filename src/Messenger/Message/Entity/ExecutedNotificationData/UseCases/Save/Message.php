<?php
declare(strict_types=1);

namespace App\Messenger\Message\Entity\ExecutedNotificationData\UseCases\Save;

use App\Entity\Entity\NotificationJobId\ForNotificationJobId\Id;
use App\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractSendingDate;
use App\Messenger\Message\AbstractAsyncMessage;

final class Message extends AbstractAsyncMessage
{
    private Id $id;

    private AbstractSendingDate $sendingDate;

    public function __construct(Id $id, AbstractSendingDate $abstractSendingDate)
    {
        $this->id = $id;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getSendingDate(): AbstractSendingDate
    {
        return $this->sendingDate;
    }
}