<?php
declare(strict_types=1);

namespace App\Messenger\Message\Entity\NotificationJobId\UseCases\Save;

use App\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractSendingDate;
use App\Messenger\Message\AbstractAsyncMessage;
use DateTimeImmutable;

final class Message extends AbstractAsyncMessage
{
    private int $id;

    private AbstractSendingDate $sendingDate;

    private DateTimeImmutable $sendingDateTimeImmutable;

    public function __construct(int $id, AbstractSendingDate $sendingDate, DateTimeImmutable $sendingDateTimeImmutable)
    {
        $this->id = $id;
        $this->sendingDate = $sendingDate;
        $this->sendingDateTimeImmutable = $sendingDateTimeImmutable;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSendingDate(): AbstractSendingDate
    {
        return $this->sendingDate;
    }

    public function getSendingDateTimeImmutable(): DateTimeImmutable
    {
        return $this->sendingDateTimeImmutable;
    }
}