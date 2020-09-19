<?php
declare(strict_types=1);

namespace App\Util\Common;

final class EnvParamsReceiver
{
    private ?string $daysSequenceBefore;

    private ?string $daysSequenceAfter;

    private bool $notifyInDateExpiration;

    private string $communicationModuleForCsvSendingUri;

    private string $communicationModuleToken;

    public function __construct(
        ?string $daysSequenceBefore,
        ?string $daysSequenceAfter,
        bool $notifyInDateExpiration,
        string $communicationModuleForCsvSendingUri,
        string $communicationModuleToken
    ) {
        $this->daysSequenceBefore = $daysSequenceBefore;
        $this->daysSequenceAfter = $daysSequenceAfter;
        $this->notifyInDateExpiration = $notifyInDateExpiration;
        $this->communicationModuleForCsvSendingUri = $communicationModuleForCsvSendingUri;
        $this->communicationModuleToken = $communicationModuleToken;
    }

    public function getDaysSequenceBefore(): ?string
    {
        return $this->daysSequenceBefore;
    }

    public function getDaysSequenceAfter(): ?string
    {
        return $this->daysSequenceAfter;
    }

    public function notifyInDateExpiration(): bool
    {
        return $this->notifyInDateExpiration;
    }

    public function getCommunicationModuleForCsvSendingUri(): string
    {
        return $this->communicationModuleForCsvSendingUri;
    }

    public function getCommunicationModuleToken(): string
    {
        return $this->communicationModuleToken;
    }
}
