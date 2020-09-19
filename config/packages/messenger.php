<?php
declare(strict_types=1);

namespace Config\Packages\messenger;

use App\Messenger\Message\AbstractAsyncMessage;
use App\Messenger\Message\AbstractSyncMessage;
use App\Messenger\RabbitMQ\AbstractInformer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $containerConfigurator) {
    $containerConfigurator->extension('framework', [
            'messenger' => [
                'failure_transport' => $failureTransport = 'deadLetterCommonCommand',
                'transports' => [
                    $syncTransport = 'immediatelyHandled' => 'sync://',
                    $asyncTransport = 'commonCommand' => [
                        'dsn' => '%env(MESSENGER_TRANSPORT_DSN)%',
                        'options' => [
                            'exchange' => [
                                'type' => 'direct',
                                'name' => 'CommonCommandExchange'
                            ],
                            'queues' => [
                                'commonCommandQueue' => [
                                    'binding_keys' => [AbstractInformer::COMMON_NORMAL_BINDING_KEY],
                                    'flags' => AbstractInformer::FLAG,
                                    'arguments' => AbstractInformer::ATTRIBUTES,
                                ]
                            ],
                            'persistent' => false,
                            'auto_setup' => false   // important
                        ],
                        'retry_strategy' => $retryStrategy = [
                            'max_retries' => 5,
                            'delay' => 0,
                        ],
                    ],
                    $failureTransport => [
                        'dsn' => '%env(MESSENGER_TRANSPORT_DSN)%',
                        'options' => [
                            'exchange' => [
                                'type' => 'direct',
                                'name' => 'failureCommonCommandExchange'
                            ],
                            'queues' => [
                                'failureCommonCommandQueue' => [
                                    'binding_keys' => [AbstractInformer::COMMON_FAILURE_BINDING_KEY],
                                    'flags' => AbstractInformer::FLAG,
                                    'arguments' => AbstractInformer::ATTRIBUTES,
                                ]
                            ],
                            'persistent' => false,
                            'auto_setup' => false       // important
                        ],
                        'retry_strategy' => $retryStrategy
                    ]
                ],
                'routing' => [
                    AbstractSyncMessage::class => $syncTransport,
                    AbstractAsyncMessage::class => $asyncTransport,
                ]
            ]
        ]
    );
};