<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantRelationshipMerchantPortalGui;

use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Plugin\MerchantRelationshipMerchantPortalGui\MerchantRelationRequestMerchantRelationshipMerchantDashboardCardExpanderPlugin;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\MerchantRelationshipMerchantPortalGuiDependencyProvider as SprykerMerchantRelationshipMerchantPortalGuiDependencyProvider;

class MerchantRelationshipMerchantPortalGuiDependencyProvider extends SprykerMerchantRelationshipMerchantPortalGuiDependencyProvider
{
    /**
     * @return list<\Spryker\Zed\MerchantRelationshipMerchantPortalGuiExtension\Dependency\Plugin\MerchantRelationshipMerchantDashboardCardExpanderPluginInterface>
     */
    protected function getMerchantRelationshipMerchantDashboardCardExpanderPlugins(): array
    {
        return [
            new MerchantRelationRequestMerchantRelationshipMerchantDashboardCardExpanderPlugin(),
        ];
    }
}
