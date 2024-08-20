<?php

use Monolog\Logger;
use Spryker\Shared\Api\ApiConstants;
use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\ErrorHandler\ErrorHandlerConstants;
use Spryker\Shared\ErrorHandler\ErrorRenderer\ApiDebugErrorRenderer;
use Spryker\Shared\ErrorHandler\ErrorRenderer\ApiErrorRenderer;
use Spryker\Shared\ErrorHandler\ErrorRenderer\WebExceptionErrorRenderer;
use Spryker\Shared\ErrorHandler\ErrorRenderer\WebHtmlErrorRenderer;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\GlueApplication\GlueApplicationConstants;
use Spryker\Shared\Log\LogConstants;
use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\PropelOrm\PropelOrmConstants;
use Spryker\Shared\ZedRequest\ZedRequestConstants;
use SprykerShop\Shared\CalculationPage\CalculationPageConstants;
use SprykerShop\Shared\ErrorPage\ErrorPageConstants;
use SprykerShop\Shared\ShopApplication\ShopApplicationConstants;

require 'config_default-docker.prod.php';

// >>> Debug

$config[ApplicationConstants::ENABLE_APPLICATION_DEBUG]
    = $config[ShopApplicationConstants::ENABLE_APPLICATION_DEBUG]
    = (bool)getenv('SPRYKER_DEBUG_ENABLED');

$config[ApiConstants::ENABLE_API_DEBUG] = (bool)getenv('SPRYKER_DEBUG_ENABLED');
$config[PropelConstants::PROPEL_DEBUG] = (bool)getenv('SPRYKER_DEBUG_PROPEL_ENABLED');
$config[CalculationPageConstants::ENABLE_CART_DEBUG] = (bool)getenv('SPRYKER_DEBUG_ENABLED');
$config[ErrorPageConstants::ENABLE_ERROR_404_STACK_TRACE] = (bool)getenv('SPRYKER_DEBUG_ENABLED');
$config[GlueApplicationConstants::GLUE_APPLICATION_REST_DEBUG] = (bool)getenv('SPRYKER_DEBUG_ENABLED');

// >>> Error handler

$config[ErrorHandlerConstants::DISPLAY_ERRORS] = true;
$config[ErrorHandlerConstants::ERROR_RENDERER] = getenv('SPRYKER_DEBUG_ENABLED') ? WebExceptionErrorRenderer::class : WebHtmlErrorRenderer::class;
$config[ErrorHandlerConstants::API_ERROR_RENDERER] = getenv('SPRYKER_DEBUG_ENABLED') ? ApiDebugErrorRenderer::class : ApiErrorRenderer::class;
$config[ErrorHandlerConstants::ERROR_LEVEL] = getenv('SPRYKER_DEBUG_DEPRECATIONS_ENABLED') ? E_ALL : $config[ErrorHandlerConstants::ERROR_LEVEL];

// >>> LOGGER

$config[EventConstants::LOGGER_ACTIVE] = true;
$config[PropelOrmConstants::PROPEL_SHOW_EXTENDED_EXCEPTION] = true;
$config[LogConstants::LOG_LEVEL] = getenv('SPRYKER_DEBUG_ENABLED') ? Logger::INFO : Logger::DEBUG;

// >>> ZED REQUEST

$config[ZedRequestConstants::TRANSFER_DEBUG_SESSION_FORWARD_ENABLED] = (bool)getenv('SPRYKER_DEBUG_ENABLED');
$config[ZedRequestConstants::SET_REPEAT_DATA] = (bool)getenv('SPRYKER_DEBUG_ENABLED');
