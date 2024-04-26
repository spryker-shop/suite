<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProfileMerchantPortalGui;

use Spryker\Zed\MerchantProfileMerchantPortalGui\MerchantProfileMerchantPortalGuiDependencyProvider as SprykerMerchantProfileMerchantPortalGuiDependencyProvider;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Plugin\MerchantProfileMerchantPortalGui\IsOpenForRelationRequestOnlineProfileMerchantProfileFormExpanderPlugin;

class MerchantProfileMerchantPortalGuiDependencyProvider extends SprykerMerchantProfileMerchantPortalGuiDependencyProvider
{
    /**
     * @return list<\Spryker\Zed\MerchantProfileMerchantPortalGuiExtension\Dependency\Plugin\OnlineProfileMerchantProfileFormExpanderPluginInterface>
     */
    protected function getOnlineProfileMerchantProfileFormExpanderPlugins(): array
    {
        return [
            new IsOpenForRelationRequestOnlineProfileMerchantProfileFormExpanderPlugin(),
        ];
    }
}
