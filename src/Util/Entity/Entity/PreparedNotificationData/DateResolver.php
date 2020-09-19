<?php
declare(strict_types=1);

namespace App\Util\Entity\Entity\PreparedNotificationData;

use App\Entity\Entity\PreparedNotificationData\PreparedNotificationData;
use App\Util\Common\EnvParamsReceiver;
use App\Util\Common\TypeConvertingResolver;
use DateTimeImmutable;
use InvalidArgumentException;

final class DateResolver
{
    private EnvParamsReceiver $envParamsReceiver;

    private TypeConvertingResolver $typeConvertingResolver;

    public function __construct(EnvParamsReceiver $envParamsReceiver)
    {
        $this->envParamsReceiver = $envParamsReceiver;
        $this->typeConvertingResolver = new TypeConvertingResolver();
    }

    public function shouldToNotify(PreparedNotificationData $preparedNotificationData, DateTimeImmutable $dateTimePoint): bool
    {
        $meterNextCheckDateDTI = $preparedNotificationData->getMeterNextCheckDate()->getDateTimeImmutableValue()->setTime(0, 0, 0, 0);

        $dateTimePoint = $dateTimePoint->setTime(0, 0, 0, 0);

        if ($dateTimePoint->getTimestamp() < $meterNextCheckDateDTI->getTimestamp()) {
            return $this->resolveByDaysSequences(
                $meterNextCheckDateDTI->diff($dateTimePoint, true)->days,
                $this->envParamsReceiver->getDaysSequenceBefore()
            );
        }

        if ($dateTimePoint->getTimestamp() == $meterNextCheckDateDTI->getTimestamp()
            && $this->envParamsReceiver->notifyInDateExpiration()) {
            return true;
        }

        if ($dateTimePoint->getTimestamp() > $meterNextCheckDateDTI->getTimestamp()) {
            return $this->resolveByDaysSequences(
                $dateTimePoint->diff($meterNextCheckDateDTI, true)->days,
                $this->envParamsReceiver->getDaysSequenceAfter()
            );
        }
    }

    private function resolveByDaysSequences(int $daysDifference, ?string $daysSequences): bool
    {
        if (($daysSequences === 'null')) {
            return false;
        }

        $daysDifferenceRegistry = $this->convertDaysSequence($daysSequences);

        if (in_array($daysDifference, $daysDifferenceRegistry)) {
            return true;
        }

        return false;
    }

    private function convertDaysSequence(string $daysSequence): array
    {
        $typeConvertingResolver = $this->typeConvertingResolver;

        return array_map(
            static function (string $daysDifference) use ($typeConvertingResolver): int {
                if (!$typeConvertingResolver->canConvertToInteger($daysDifference)) {
                    throw new InvalidArgumentException(sprintf('"%s" can not convert to Int', $daysDifference), 0, null);
                }

                return (int)$daysDifference;
            },
            explode(',', $daysSequence)
        );
    }
}
