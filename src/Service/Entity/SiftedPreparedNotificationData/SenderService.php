<?php
declare(strict_types=1);

namespace App\Service\Entity\SiftedPreparedNotificationData;

use App\Util\Common\EnvParamsReceiver;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class SenderService
{
    private HttpClientInterface $httpClient;

    private EnvParamsReceiver $envParamsReceiver;

    public function __construct(HttpClientInterface $client, EnvParamsReceiver $envParamsReceiver)
    {
        $this->httpClient = $client;
        $this->envParamsReceiver = $envParamsReceiver;
    }

    public function sendToCommunicationModule(string &$sendingData): array
    {
        $response = $this->httpClient->request(
                'POST',
                $this->envParamsReceiver->getCommunicationModuleForCsvSendingUri(),
                [
                    'headers' => [
                        'token' => $this->envParamsReceiver->getCommunicationModuleToken(),
                        'content-type' => 'application/gzip',
                        'accept' => 'application/json'
                    ],
                    'body' => $sendingData
                ]
            );

        return $response->toArray(true);
    }
}