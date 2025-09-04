<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\DashboardMerchantPortalGui;

use Spryker\Zed\DashboardMerchantPortalGui\DashboardMerchantPortalGuiDependencyProvider as SprykerDashboardMerchantPortalGuiDependencyProvider;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Plugin\DashboardMerchantPortalGui\MerchantRelationshipMerchantDashboardCardPlugin;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Plugin\DashboardMerchantPortalGui\ProductsMerchantDashboardCardPlugin;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Plugin\DashboardMerchantPortalGui\OffersMerchantDashboardCardPlugin;
use Spryker\Zed\SalesMerchantPortalGui\Communication\Plugin\DashboardMerchantPortalGui\OrdersMerchantDashboardCardPlugin;

class DashboardMerchantPortalGuiDependencyProvider extends SprykerDashboardMerchantPortalGuiDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\DashboardMerchantPortalGuiExtension\Dependency\Plugin\MerchantDashboardCardPluginInterface>
     */
    protected function getDashboardCardPlugins(): array
    {
        return [
            new ProductsMerchantDashboardCardPlugin(),
            new OffersMerchantDashboardCardPlugin(),
            new OrdersMerchantDashboardCardPlugin(),
            new MerchantRelationshipMerchantDashboardCardPlugin(),
        ];
    }
}
