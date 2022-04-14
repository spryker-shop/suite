<?php

use Monolog\Logger;
use Pyz\Shared\Console\ConsoleConstants;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Shared\DocumentationGeneratorRestApi\DocumentationGeneratorRestApiConstants;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\ErrorHandler\ErrorRenderer\WebExceptionErrorRenderer;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\GlueApplication\GlueApplicationConstants;
use Spryker\Shared\Http\HttpConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\MerchantPortalApplication\MerchantPortalConstants;
use Spryker\Shared\Newsletter\NewsletterConstants;
use Spryker\Shared\ProductManagement\ProductManagementConstants;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\RabbitMq\RabbitMqEnv;
use Spryker\Shared\Router\RouterConstants;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Shared\SessionRedis\SessionRedisConfig;
use Spryker\Shared\SessionRedis\SessionRedisConstants;
use Spryker\Shared\StorageRedis\StorageRedisConstants;
use Spryker\Shared\Testify\TestifyConstants;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use SprykerShop\Shared\ErrorPage\ErrorPageConstants;

// ############################################################################
// ############################## TESTING IN DEVVM ############################
// ############################################################################

$domain = getenv('VM_PROJECT') ?: 'suite-nonsplit';
$storeLowerCase = strtolower(APPLICATION_STORE);
$stores = array_combine(Store::getInstance()->getAllowedStores(), Store::getInstance()->getAllowedStores());
$backendApiHost = sprintf('backend-api-test.%s.%s.local', $storeLowerCase, $domain);
$backendGatewayHost = sprintf('backend-gateway-test.%s.%s.local', $storeLowerCase, $domain);
$backofficeHost = sprintf('backoffice-test.%s.%s.local', $storeLowerCase, $domain);
$merchantPortalHost = sprintf('mp-test.%s.%s.local', $storeLowerCase, $domain);
$glueHost = sprintf('glue-test.de.%s.local', $domain);
$yvesHost = sprintf('www-test.%s.%s.local', $storeLowerCase, $domain);

// ----------------------------------------------------------------------------
// ------------------------------ CODEBASE ------------------------------------
// ----------------------------------------------------------------------------

// >>> Debug

$config[GlueApplicationConstants::GLUE_APPLICATION_REST_DEBUG] = true;

// >>> Dev tools
$config[KernelConstants::ENABLE_CONTAINER_OVERRIDING] = true;
$config[ConsoleConstants::ENABLE_DEVELOPMENT_CONSOLE_COMMANDS] = true;
$config[DocumentationGeneratorRestApiConstants::ENABLE_REST_API_DOCUMENTATION_GENERATION] = true;

// >>> ErrorHandler
$config[ErrorPageConstants::ENABLE_ERROR_404_STACK_TRACE] = true;
$config[ErrorHandlerConstants::DISPLAY_ERRORS] = true;
$config[ErrorHandlerConstants::ERROR_RENDERER] = WebExceptionErrorRenderer::class;
$config[ErrorHandlerConstants::IS_PRETTY_ERROR_HANDLER_ENABLED] = true;

// ----------------------------------------------------------------------------
// ------------------------------ SECURITY ------------------------------------
// ----------------------------------------------------------------------------

$config[SessionConstants::ZED_SSL_ENABLED]
    = $config[SessionConstants::YVES_SSL_ENABLED]
    = $config[RouterConstants::YVES_IS_SSL_ENABLED]
    = $config[RouterConstants::ZED_IS_SSL_ENABLED]
    = $config[HttpConstants::ZED_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED]
    = $config[HttpConstants::YVES_HTTP_STRICT_TRANSPORT_SECURITY_ENABLED]
    = false;

$trustedHosts
    = $config[HttpConstants::ZED_TRUSTED_HOSTS]
    = $config[HttpConstants::YVES_TRUSTED_HOSTS]
    = [
    $yvesHost,
    $backofficeHost,
    $backendGatewayHost,
    $backendApiHost,
    'localhost',
];

$config[KernelConstants::DOMAIN_WHITELIST] = array_merge($trustedHosts, $config[KernelConstants::DOMAIN_WHITELIST]);

// ----------------------------------------------------------------------------
// ------------------------------ AUTHENTICATION ------------------------------
// ----------------------------------------------------------------------------

require 'common/config_oauth-devvm.php';

// ----------------------------------------------------------------------------
// ------------------------------ SERVICES ------------------------------------
// ----------------------------------------------------------------------------

require 'common/config_services-devvm.php';
require 'common/config_logs-files.php';
require 'common/config_logs-ci-errors.php';

// >>> DATABASE
$config[PropelConstants::ZED_DB_USERNAME] = 'devtest';
$config[PropelConstants::ZED_DB_PASSWORD] = 'mate20mg';
$config[PropelConstants::ZED_DB_DATABASE] = sprintf('%s_devtest_zed', APPLICATION_CODE_BUCKET);

// >>> STORAGE
$config[StorageRedisConstants::STORAGE_REDIS_DATABASE] = 3;

// >>> SESSION
$config[SessionConstants::YVES_SESSION_SAVE_HANDLER] = SessionRedisConfig::SESSION_HANDLER_REDIS;

$config[SessionRedisConstants::YVES_SESSION_REDIS_DATABASE]
    = $config[SessionRedisConstants::ZED_SESSION_REDIS_DATABASE] = 5;

// >>> QUEUE

$config[EventConstants::EVENT_CHUNK] = 5000;

$config[RabbitMqEnv::RABBITMQ_API_VIRTUAL_HOST]
    = $config[RabbitMqEnv::RABBITMQ_VIRTUAL_HOST]
    = sprintf('/%s_devtest_zed', APPLICATION_STORE);

$config[RabbitMqEnv::RABBITMQ_USERNAME] = sprintf('%s_devtest', APPLICATION_STORE);

$config[RabbitMqEnv::RABBITMQ_CONNECTIONS] = array_map(static function ($storeName) {
    return [
        RabbitMqEnv::RABBITMQ_CONNECTION_NAME => $storeName . '-connection',
        RabbitMqEnv::RABBITMQ_HOST => 'localhost',
        RabbitMqEnv::RABBITMQ_PORT => '5672',
        RabbitMqEnv::RABBITMQ_PASSWORD => 'mate20mg',
        RabbitMqEnv::RABBITMQ_USERNAME => $storeName . '_devtest',
        RabbitMqEnv::RABBITMQ_VIRTUAL_HOST => '/' . $storeName . '_devtest_zed',
        RabbitMqEnv::RABBITMQ_STORE_NAMES => [$storeName],
        RabbitMqEnv::RABBITMQ_DEFAULT_CONNECTION => $storeName === APPLICATION_STORE,
    ];
}, $stores);

// ---------- LOGGER

$config[LogConstants::LOG_LEVEL] = Logger::CRITICAL;

// ----------------------------------------------------------------------------
// ------------------------------ ZED (Gateway) -------------------------------
// ----------------------------------------------------------------------------

$config[ZedRequestConstants::ZED_API_SSL_ENABLED] = false;
$config[ZedRequestConstants::HOST_ZED_API]
    = $backendGatewayHost;

$config[SessionConstants::ZED_SESSION_COOKIE_NAME]
    = $config[SessionConstants::ZED_SESSION_COOKIE_DOMAIN]
    = $backofficeHost;

$config[ZedRequestConstants::BASE_URL_ZED_API] = sprintf(
    'http://%s',
    $backendGatewayHost,
);
$config[ZedRequestConstants::BASE_URL_SSL_ZED_API] = sprintf(
    'https://%s',
    $backendGatewayHost,
);

// ----------------------------------------------------------------------------
// ------------------------------ BACKOFFICE ----------------------------------
// ----------------------------------------------------------------------------

$config[ApplicationConstants::BASE_URL_ZED] = sprintf(
    'http://%s',
    $backofficeHost,
);

// ----------------------------------------------------------------------------
// ------------------------------ MERCHANT PORTAL -----------------------------
// ----------------------------------------------------------------------------

$config[MerchantPortalConstants::BASE_URL_MP] = sprintf(
    'http://%s',
    $merchantPortalHost,
);

// ----------------------------------------------------------------------------
// ------------------------------ FRONTEND ------------------------------------
// ----------------------------------------------------------------------------

$config[ApplicationConstants::HOST_YVES]
    = $config[SessionConstants::YVES_SESSION_COOKIE_NAME]
    = $config[SessionConstants::YVES_SESSION_COOKIE_DOMAIN]
    = $yvesHost;

$config[ApplicationConstants::BASE_URL_YVES]
    = $config[CustomerConstants::BASE_URL_YVES]
    = $config[ProductManagementConstants::BASE_URL_YVES]
    = $config[NewsletterConstants::BASE_URL_YVES]
    = sprintf(
        'http://%s',
        $yvesHost,
    );

// ----------------------------------------------------------------------------
// ------------------------------ API -----------------------------------------
// ----------------------------------------------------------------------------

$config[GlueApplicationConstants::GLUE_APPLICATION_DOMAIN]
    = sprintf(
        'http://%s',
        $glueHost,
    );

if (class_exists(TestifyConstants::class)) {
    $config[TestifyConstants::GLUE_APPLICATION_DOMAIN] = $config[GlueApplicationConstants::GLUE_APPLICATION_DOMAIN];
}

$config[GlueApplicationConstants::GLUE_APPLICATION_CORS_ALLOW_ORIGIN] = '*';

// ----------------------------------------------------------------------------
// ------------------------------ OMS -----------------------------------------
// ----------------------------------------------------------------------------

require 'common/config_oms-development.php';

// ----------------------------------------------------------------------------
// ------------------------------ PAYMENTS ------------------------------------
// ----------------------------------------------------------------------------

// >>> PAYONE

require 'common/config_payone-development.php';
