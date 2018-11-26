<?php

/**
 * This is the global runtime configuration for Yves and Generated_Yves_Zed in a development environment.
 */

use Monolog\Logger;
use Spryker\Shared\Acl\AclConstants;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Config\ConfigConstants;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\ErrorHandler\ErrorRenderer\WebExceptionErrorRenderer;
use Spryker\Shared\GlueApplication\GlueApplicationConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\Oauth\OauthConstants;
use Spryker\Shared\OauthCustomerConnector\OauthCustomerConnectorConstants;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\PropelOrm\PropelOrmConstants;
use Spryker\Shared\PropelQueryBuilder\PropelQueryBuilderConstants;
use Spryker\Shared\RabbitMq\RabbitMqEnv;
use Spryker\Shared\Session\SessionConfig;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Shared\Setup\SetupConstants;
use Spryker\Shared\Storage\StorageConstants;
use Spryker\Shared\WebProfiler\WebProfilerConstants;
use Spryker\Shared\ZedNavigation\ZedNavigationConstants;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use Spryker\Shared\Search\SearchConstants;
use Spryker\Shared\Collector\CollectorConstants;
use Spryker\Shared\Queue\QueueConstants;
use Spryker\Shared\Event\EventConstants;

$CURRENT_STORE = Store::getInstance()->getStoreName();

// ---------- General environment
$config[KernelConstants::SPRYKER_ROOT] = APPLICATION_ROOT_DIR . '/vendor/spryker';
$config[KernelConstants::STORE_PREFIX] = 'DEV';
$config[ApplicationConstants::ENABLE_APPLICATION_DEBUG] = true;
$config[WebProfilerConstants::ENABLE_WEB_PROFILER]
    = $config[ConfigConstants::ENABLE_WEB_PROFILER]
    = true;

$config[ApplicationConstants::ZED_SSL_ENABLED] = false;
$config[ApplicationConstants::YVES_SSL_ENABLED] = false;

// ---------- Propel
$config[PropelConstants::PROPEL_DEBUG] = false;
$config[PropelOrmConstants::PROPEL_SHOW_EXTENDED_EXCEPTION] = true;
$config[PropelConstants::ZED_DB_DATABASE] = getenv('DB_NAME', 'DE_development_zed');
$config[PropelConstants::ZED_DB_USERNAME] = getenv('DB_USERNAME');
$config[PropelConstants::ZED_DB_PASSWORD] = getenv('DB_PASSWORD');
$config[PropelConstants::ZED_DB_HOST] = getenv('DB_HOST');
$config[PropelConstants::ZED_DB_PORT] = getenv('DB_PORT', 5432);
$config[PropelConstants::ZED_DB_ENGINE] = $config[PropelConstants::ZED_DB_ENGINE_PGSQL];
$config[PropelQueryBuilderConstants::ZED_DB_ENGINE] = $config[PropelConstants::ZED_DB_ENGINE_PGSQL];

// ---------- Redis
$config[StorageConstants::STORAGE_REDIS_PROTOCOL] = 'tcp';
$config[StorageConstants::STORAGE_REDIS_HOST] = getenv('REDIS_HOST', 'redis');
$config[StorageConstants::STORAGE_REDIS_PORT] = getenv('REDIS_PORT', '10009');
$config[StorageConstants::STORAGE_REDIS_PASSWORD] = false;
$config[StorageConstants::STORAGE_REDIS_DATABASE] = "0";

// ---------- RabbitMQ
$config[RabbitMqEnv::RABBITMQ_API_HOST] = getenv('RABBIT_HOST', 'rabbit');
$config[RabbitMqEnv::RABBITMQ_API_PORT] = getenv('RABBIT_PORT', '15672');
$config[RabbitMqEnv::RABBITMQ_API_USERNAME] = getenv('RABBIT_USERNAME', 'admin');
$config[RabbitMqEnv::RABBITMQ_API_PASSWORD] = getenv('RABBIT_PASSWORD', 'mate20mg');
$config[ApplicationConstants::ZED_RABBITMQ_VHOST] = getenv('RABBIT_VHOST', '/DE_development_zed');

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
// var_dump($config[RabbitMqEnv::RABBITMQ_API_PORT]);
// var_dump($config[RabbitMqEnv::RABBITMQ_API_HOST]);
// var_dump($config[RabbitMqEnv::RABBITMQ_API_USERNAME]);
// var_dump($config[RabbitMqEnv::RABBITMQ_API_PASSWORD]);
// exit;
###$config[RabbitMqEnv::RABBITMQ_API_VIRTUAL_HOST] = '/DE_development_zed';
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


// ---------- Session
$config[SessionConstants::YVES_SESSION_COOKIE_SECURE] = false;
$config[SessionConstants::YVES_SESSION_REDIS_PROTOCOL] = $config[StorageConstants::STORAGE_REDIS_PROTOCOL];
$config[SessionConstants::YVES_SESSION_REDIS_HOST] = $config[StorageConstants::STORAGE_REDIS_HOST];
$config[SessionConstants::YVES_SESSION_REDIS_PORT] = $config[StorageConstants::STORAGE_REDIS_PORT];
$config[SessionConstants::YVES_SESSION_REDIS_PASSWORD] = $config[StorageConstants::STORAGE_REDIS_PASSWORD];
$config[SessionConstants::YVES_SESSION_REDIS_DATABASE] = 1;
$config[SessionConstants::ZED_SESSION_COOKIE_SECURE] = false;
$config[SessionConstants::ZED_SESSION_REDIS_PROTOCOL] = $config[SessionConstants::YVES_SESSION_REDIS_PROTOCOL];
$config[SessionConstants::ZED_SESSION_REDIS_HOST] = $config[SessionConstants::YVES_SESSION_REDIS_HOST];
$config[SessionConstants::ZED_SESSION_REDIS_PORT] = $config[SessionConstants::YVES_SESSION_REDIS_PORT];
$config[SessionConstants::ZED_SESSION_REDIS_PASSWORD] = $config[SessionConstants::YVES_SESSION_REDIS_PASSWORD];
$config[SessionConstants::ZED_SESSION_REDIS_DATABASE] = 2;
$config[SessionConstants::ZED_SESSION_TIME_TO_LIVE] = SessionConfig::SESSION_LIFETIME_1_YEAR;

// ---------- Jenkins
$config[SetupConstants::JENKINS_BASE_URL] = getenv('JENKINS_BASE_URL', 'http://localhost:10007/');
$config[SetupConstants::JENKINS_DIRECTORY] = getenv('JENKINS_DIRECTORY', '/data/shop/development/shared/data/common/jenkins');

// ---------- Zed request
$config[ZedRequestConstants::TRANSFER_DEBUG_SESSION_FORWARD_ENABLED] = true;
$config[ZedRequestConstants::SET_REPEAT_DATA] = true;
$config[ZedRequestConstants::YVES_REQUEST_REPEAT_DATA_PATH] = APPLICATION_ROOT_DIR . '/data/' . Store::getInstance()->getStoreName() . '/' . APPLICATION_ENV . '/yves-requests';

// ---------- Navigation
$config[ZedNavigationConstants::ZED_NAVIGATION_CACHE_ENABLED] = true;

// ---------- Error handling
$config[ErrorHandlerConstants::DISPLAY_ERRORS] = true;
$config[ErrorHandlerConstants::ERROR_RENDERER] = WebExceptionErrorRenderer::class;

// ---------- ACL
$config[AclConstants::ACL_USER_RULE_WHITELIST][] = [
    'bundle' => 'wdt',
    'controller' => '*',
    'action' => '*',
    'type' => 'allow',
];

// ---------- Auto-loader
$config[KernelConstants::AUTO_LOADER_UNRESOLVABLE_CACHE_ENABLED] = false;

// ---------- Logging
$config[LogConstants::LOG_LEVEL] = Logger::INFO;

$baseLogFilePath = sprintf('%s/data/%s/logs', APPLICATION_ROOT_DIR, $CURRENT_STORE);

$config[LogConstants::EXCEPTION_LOG_FILE_PATH_YVES] = $baseLogFilePath . '/YVES/exception.log';
$config[LogConstants::EXCEPTION_LOG_FILE_PATH_ZED] = $baseLogFilePath . '/ZED/exception.log';

// ----------- Glue Application
$config[GlueApplicationConstants::GLUE_APPLICATION_REST_DEBUG] = true;

// ----------- OAUTH
$config[OauthConstants::PRIVATE_KEY_PATH] = 'file://' . APPLICATION_ROOT_DIR . '/config/Zed/dev_only_private.key';
$config[OauthConstants::PUBLIC_KEY_PATH] = 'file://' . APPLICATION_ROOT_DIR . '/config/Zed/dev_only_public.key';
$config[OauthConstants::ENCRYPTION_KEY] = getenv('OAUTH_CLIENT_IDENTIFIER', 'lxZFUEsBCJ2Yb14IF2ygAHI5N4+ZAUXXaSeeJm6+twsUmIen');

// ----------- AuthRestApi
$config[OauthCustomerConnectorConstants::OAUTH_CLIENT_IDENTIFIER] = getenv('OAUTH_CLIENT_IDENTIFIER', 'frontend');
$config[OauthCustomerConnectorConstants::OAUTH_CLIENT_SECRET] = getenv('OAUTH_CLIENT_SECRET', 'abc123');
