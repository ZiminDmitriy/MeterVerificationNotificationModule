<?php
declare(strict_types=1);

namespace App\Entity\Entity\SiftedPreparedNotificationData\ForSiftedPreparedNotificationData;

use App\Entity\ForEntity\AbstractCollector;
use App\Entity\ForEntity\EntityFeature\AbstractFeature\AbstractSendingDate;

final class SendingDateCollector extends AbstractCollector
{
    public static function createFulledCollector(SendingDate ...$sendingDates): self
    {
        return new self($sendingDates);
    }

    public function add(AbstractSendingDate $sendingDate): self
    {
        if (!$this->hasSendingDate($sendingDate)) {
            $this->append(new SendingDate($sendingDate->getDateTimeImmutableValue()));
        }

        return $this;
    }

    public function hasSendingDate(AbstractSendingDate $sendingDate): bool
    {
        if (!$this->isEmpty()) {
            /** @var SendingDate $existingSendingDate */
            foreach ($this->getArrayCopy() as $existingSendingDate) {
                if ($existingSendingDate->getValue() === $sendingDate->getValue()) {
                    return true;
                }
            }
        }

        return false;
    }
}