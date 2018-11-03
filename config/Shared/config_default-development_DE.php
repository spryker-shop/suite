<?php

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Collector\CollectorConstants;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\Mail\MailConstants;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\Queue\QueueConstants;
use Spryker\Shared\RabbitMq\RabbitMqEnv;
use Spryker\Shared\Search\SearchConstants;

// // ---------- Propel
// $config[PropelConstants::ZED_DB_DATABASE] = 'DE_development_zed';

// // ---------- MailCatcher
// $config[MailConstants::MAILCATCHER_GUI] = sprintf('http://%s:1080', $config[ApplicationConstants::HOST_ZED]);

// // ---------- Elasticsearch
$ELASTICA_INDEX_NAME = 'de_search';
$config[SearchConstants::ELASTICA_PARAMETER__INDEX_NAME] = $ELASTICA_INDEX_NAME;
$config[CollectorConstants::ELASTICA_PARAMETER__INDEX_NAME] = $ELASTICA_INDEX_NAME;

// // ---------- Queue
$config[QueueConstants::QUEUE_WORKER_INTERVAL_MILLISECONDS] = 1000;
$config[QueueConstants::QUEUE_WORKER_LOG_ACTIVE] = false;
$config[QueueConstants::QUEUE_WORKER_OUTPUT_FILE_NAME] = 'data/DE/logs/ZED/queue.out';

// // ---------- Event
$config[EventConstants::MAX_RETRY_ON_FAIL] = 5;

// ---------- RabbitMQ
$config[RabbitMqEnv::RABBITMQ_CONNECTIONS]['DE'][RabbitMqEnv::RABBITMQ_DEFAULT_CONNECTION] = true;
$config[RabbitMqEnv::RABBITMQ_API_VIRTUAL_HOST] = '/DE_development_zed';

// ---------- RabbitMQ
$config[RabbitMqEnv::RABBITMQ_API_HOST] = getenv('RABBIT_HOST', 'rabbit');
$config[RabbitMqEnv::RABBITMQ_API_PORT] = getenv('RABBIT_PORT', '15672');
$config[RabbitMqEnv::RABBITMQ_API_USERNAME] = getenv('RABBIT_USERNAME', 'admin');
$config[RabbitMqEnv::RABBITMQ_API_PASSWORD] = getenv('RABBIT_PASSWORD', 'mate20mg');
$config[ApplicationConstants::ZED_RABBITMQ_VHOST] = getenv('RABBIT_VHOST', '/DE_development_zed');

$config[RabbitMqEnv::RABBITMQ_API_VIRTUAL_HOST] = '/DE_development_zed';
$config[RabbitMqEnv::RABBITMQ_CONNECTIONS] = [
    'DE' => [
        RabbitMqEnv::RABBITMQ_CONNECTION_NAME => 'DE-connection',
        RabbitMqEnv::RABBITMQ_HOST => getenv('RABBIT_HOST', 'rabbit'),
        RabbitMqEnv::RABBITMQ_PORT => getenv('RABBIT_PORT', '15672'),
        RabbitMqEnv::RABBITMQ_PASSWORD => getenv('RABBIT_PASSWORD', 'mate20mg'),
        RabbitMqEnv::RABBITMQ_USERNAME => getenv('RABBIT_USERNAME', 'admin'),
        RabbitMqEnv::RABBITMQ_VIRTUAL_HOST => getenv('RABBIT_VHOST', '/DE_development_zed'),
        RabbitMqEnv::RABBITMQ_STORE_NAMES => ['DE'],
    ],
];
