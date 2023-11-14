<?php

// ############################################################################
// ############################## CI CONFIGURATION ############################
// ############################################################################

use Spryker\Shared\GlueBackendApiApplication\GlueBackendApiApplicationConstants;
use Spryker\Shared\GlueJsonApiConvention\GlueJsonApiConventionConstants;
use Spryker\Shared\GlueStorefrontApiApplication\GlueStorefrontApiApplicationConstants;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Shared\StorageDatabase\StorageDatabaseConstants;
use Spryker\Zed\Propel\PropelConfig;

require 'config_default-docker.dev.php';

// ----------------------------------------------------------------------------
// ------------------------------ SERVICES ------------------------------------
// ----------------------------------------------------------------------------

require 'common/config_logs-ci-errors.php';
require 'common/config_logs-ci-info.php';

//-----------------------------------------------------------------------------
//----------------------------------- ACP -------------------------------------
//-----------------------------------------------------------------------------
$config[ProductConstants::PUBLISHING_TO_MESSAGE_BROKER_ENABLED] = false;

//-----------------------------------------------------------------------------
//------------------------------- RDS Storage ---------------------------------
//-----------------------------------------------------------------------------
$config[StorageDatabaseConstants::DB_ENGINE] = strtolower(getenv('SPRYKER_DB_ENGINE') ?: '') ?: PropelConfig::DB_ENGINE_MYSQL;
$config[StorageDatabaseConstants::DB_HOST] = getenv('SPRYKER_DB_HOST');
$config[StorageDatabaseConstants::DB_PORT] = getenv('SPRYKER_DB_PORT');
$config[StorageDatabaseConstants::DB_DATABASE] = getenv('SPRYKER_DB_DATABASE');
$config[StorageDatabaseConstants::DB_USERNAME] = getenv('SPRYKER_DB_USERNAME');
$config[StorageDatabaseConstants::DB_PASSWORD] = getenv('SPRYKER_DB_PASSWORD');
$config[StorageDatabaseConstants::DB_DEBUG] = false;

$sprykerGlueStorefrontHost = getenv('SPRYKER_GLUE_STOREFRONT_HOST');
$config[GlueStorefrontApiApplicationConstants::GLUE_STOREFRONT_API_HOST] = $sprykerGlueStorefrontHost;
$sprykerGlueBackendHost = getenv('SPRYKER_GLUE_BACKEND_HOST');
$config[GlueBackendApiApplicationConstants::GLUE_BACKEND_API_HOST] = $sprykerGlueBackendHost;
$config[GlueJsonApiConventionConstants::GLUE_DOMAIN] = sprintf(
    'http://%s',
    $sprykerGlueStorefrontHost ?: $sprykerGlueBackendHost ?: 'localhost',
);
