<?php

/**
 * This is the global runtime configuration for Yves and Generated_Yves_Zed in a staging environment.
 */

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\SetupFrontend\SetupFrontendConstants;
use SprykerShop\Shared\ShopApplication\ShopApplicationConstants;

$CURRENT_STORE = Store::getInstance()->getStoreName();

// ---------- General
$config[ApplicationConstants::ENABLE_APPLICATION_DEBUG]
    = $config[ShopApplicationConstants::ENABLE_APPLICATION_DEBUG]
    = true;

// ---------- Auto-loader
$config[KernelConstants::AUTO_LOADER_UNRESOLVABLE_CACHE_ENABLED] = true;

// ----------- Yves assets
$config[SetupFrontendConstants::YVES_BUILD_COMMAND] = sprintf('npm run yves:production %s', $CURRENT_STORE);
