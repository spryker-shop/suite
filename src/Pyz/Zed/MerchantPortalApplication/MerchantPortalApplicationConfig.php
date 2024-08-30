<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantPortalApplication;

use Spryker\Zed\MerchantPortalApplication\MerchantPortalApplicationConfig as SprykerMerchantPortalApplicationConfig;

class MerchantPortalApplicationConfig extends SprykerMerchantPortalApplicationConfig
{
    /**
     * @see \Spryker\Zed\DashboardMerchantPortalGui\Communication\Controller\DashboardController::indexAction()
     *
     * @return string
     */
    public function getHomePageUrl(): string
    {
        return '/dashboard-merchant-portal-gui/dashboard';
    }
}
