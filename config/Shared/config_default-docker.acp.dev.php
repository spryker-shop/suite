<?php

use Spryker\Shared\AppCatalogGui\AppCatalogGuiConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Store\StoreConstants;

require 'config_default-docker.dev.php';

// ----------------------------------------------------------------------------
// ------------------------------ ACP -----------------------------------------
// ----------------------------------------------------------------------------

$config[AppCatalogGuiConstants::APP_CATALOG_SCRIPT_URL] = 'http://yves.registry.spryker.local/loader';

$config[StoreConstants::STORE_NAME_REFERENCE_MAP] = [
    'DE' => 'tenant-154de332-b74c-41e3-a029-a3210b8a86f5',
    'AT' => 'tenant-154de332-b74c-41e3-a029-a3210b8a86f5',
    'US' => 'tenant-154de332-b74c-41e3-a029-a3210b8a86f5',
];

$config[KernelConstants::DOMAIN_WHITELIST][] = 'yves.registry.spryker.local';
$config[KernelConstants::DOMAIN_WHITELIST][] = 'stripe.spryker.local';

// ----------------------------------------------------------------------------
// ------------------------------ MessageBroker -------------------------------
// ----------------------------------------------------------------------------

require sprintf('%s/acp-toolbox/config/config_message-broker.php', APPLICATION_ROOT_DIR);
