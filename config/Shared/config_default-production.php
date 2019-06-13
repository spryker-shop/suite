<?php

/**
 * This is the global runtime configuration for Yves and Generated_Yves_Zed in a production environment.
 */

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Session\SessionConstants;
use Spryker\Shared\Setup\SetupConstants;
use Spryker\Shared\Twig\TwigConstants;
use SprykerShop\Shared\ShopApplication\ShopApplicationConstants;

$CURRENT_STORE = Store::getInstance()->getStoreName();

// ---------- Auto-loader
$config[KernelConstants::AUTO_LOADER_UNRESOLVABLE_CACHE_ENABLED] = true;

// ---------- Twig
$config[TwigConstants::YVES_PATH_CACHE_ENABLED] = true;
$config[TwigConstants::ZED_PATH_CACHE_ENABLED] = true;

// ---------- Setup
$config[SetupConstants::ENABLE_SCHEDULER] = false;

// ----------- Application
$config[ApplicationConstants::TWIG_ENVIRONMENT_NAME] =
$config[ShopApplicationConstants::TWIG_ENVIRONMENT_NAME] = 'production';

// ---------- Session
$config[SessionConstants::SESSION_ENVIRONMENT_NAME] = 'production';
