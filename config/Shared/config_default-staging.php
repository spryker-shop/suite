<?php

/**
 * This is the global runtime configuration for Yves and Generated_Yves_Zed in a staging environment.
 */

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Session\SessionConstants;
use SprykerShop\Shared\ShopApplication\ShopApplicationConstants;

// ---------- General
$config[ApplicationConstants::ENABLE_APPLICATION_DEBUG]
    = $config[ShopApplicationConstants::ENABLE_APPLICATION_DEBUG]
    = true;

// ---------- Auto-loader
$config[KernelConstants::AUTO_LOADER_UNRESOLVABLE_CACHE_ENABLED] = true;

// ----------- Application
$config[ApplicationConstants::TWIG_ENVIRONMENT_NAME]
    = $config[ShopApplicationConstants::TWIG_ENVIRONMENT_NAME]
    = 'staging';

// ---------- Session
$config[SessionConstants::SESSION_ENVIRONMENT_NAME] = 'staging';
