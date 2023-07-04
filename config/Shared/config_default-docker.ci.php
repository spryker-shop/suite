<?php

// ############################################################################
// ############################## CI CONFIGURATION ############################
// ############################################################################

use Spryker\Shared\MessageBroker\MessageBrokerConstants;
use Spryker\Shared\MessageBrokerAws\MessageBrokerAwsConstants;
use Spryker\Shared\Product\ProductConstants;
use Spryker\Shared\StorageDatabase\StorageDatabaseConstants;
use Spryker\Shared\Store\StoreConstants;
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
$config[StoreConstants::STORE_NAME_REFERENCE_MAP] = ['DE' => 'dev-DE'];

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

// ----------------------------------------------------------------------------
// ------------------------------ MessageBroker -----------------------------------------
// ----------------------------------------------------------------------------
$config[MessageBrokerConstants::CHANNEL_TO_TRANSPORT_MAP] =
$config[MessageBrokerAwsConstants::CHANNEL_TO_SENDER_TRANSPORT_MAP] =
$config[MessageBrokerAwsConstants::CHANNEL_TO_RECEIVER_TRANSPORT_MAP] = [
    'payment' => 'in-memory',
    'assets' => 'in-memory',
    'product' => 'in-memory',
    'reviews' => 'in-memory',
];

$config[MessageBrokerConstants::IS_ENABLED] = true;
