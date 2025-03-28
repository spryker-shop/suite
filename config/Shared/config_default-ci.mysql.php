<?php

declare(strict_types = 1);

use Spryker\Shared\Propel\PropelConstants;
use Spryker\Shared\StorageDatabase\StorageDatabaseConfig;
use Spryker\Shared\StorageDatabase\StorageDatabaseConstants;
use Spryker\Zed\Propel\PropelConfig;

require 'config_default-ci.php';

$config[PropelConstants::ZED_DB_ENGINE] = PropelConfig::DB_ENGINE_MYSQL;
$config[PropelConstants::ZED_DB_USERNAME] = 'root';
$config[PropelConstants::ZED_DB_PASSWORD] = getenv('DB_PASSWORD') ?: '';
$config[PropelConstants::ZED_DB_DATABASE] = 'DE_test_zed';
$config[PropelConstants::ZED_DB_HOST] = getenv('DB_HOST') ?: '127.0.0.1';
$config[PropelConstants::USE_SUDO_TO_MANAGE_DATABASE] = false;
$config[PropelConstants::ZED_DB_PORT] = 3306;

$config[StorageDatabaseConstants::DB_ENGINE] = StorageDatabaseConfig::DB_ENGINE_MYSQL;
$config[StorageDatabaseConstants::DB_PORT] = 3306;
$config[StorageDatabaseConstants::DB_USERNAME] = 'root';
