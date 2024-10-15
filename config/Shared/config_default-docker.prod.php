<?php

use Spryker\Shared\Application\ApplicationConstants;
use SprykerShop\Shared\ShopApplication\ShopApplicationConstants;

// ############################################################################
// ############################## TESTING/STAGING CONFIGURATION ###################
// ############################################################################

// ----------------------------------------------------------------------------
// ------------------------------ OMS -----------------------------------------
// ----------------------------------------------------------------------------

require 'common/config_oms-development.php';

$config[ApplicationConstants::ENABLE_APPLICATION_DEBUG]
    = $config[ShopApplicationConstants::ENABLE_APPLICATION_DEBUG]
    = (bool)getenv('SPRYKER_DEBUG_ENABLED');
