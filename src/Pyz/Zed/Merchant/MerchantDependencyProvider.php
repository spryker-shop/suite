<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Merchant;

use Spryker\Zed\AclMerchantPortal\Communication\Plugin\Merchant\MerchantAclEntitiesMerchantPostCreatePlugin;
use Spryker\Zed\Merchant\MerchantDependencyProvider as SprykerMerchantDependencyProvider;
use Spryker\Zed\MerchantCategory\Communication\Plugin\Merchant\MerchantCategoryMerchantBulkExpanderPlugin;
use Spryker\Zed\MerchantProfile\Communication\Plugin\Merchant\MerchantProfileMerchantBulkExpanderPlugin;
use Spryker\Zed\MerchantProfile\Communication\Plugin\Merchant\MerchantProfileMerchantPostCreatePlugin;
use Spryker\Zed\MerchantProfile\Communication\Plugin\Merchant\MerchantProfileMerchantPostUpdatePlugin;
use Spryker\Zed\MerchantStock\Communication\Plugin\Merchant\MerchantStockMerchantBulkExpanderPlugin;
use Spryker\Zed\MerchantStock\Communication\Plugin\Merchant\MerchantStockMerchantPostCreatePlugin;
use Spryker\Zed\MerchantUser\Communication\Plugin\Merchant\SyncMerchantUsersStatusMerchantPostUpdatePlugin;

class MerchantDependencyProvider extends SprykerMerchantDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostCreatePluginInterface>
     */
    protected function getMerchantPostCreatePlugins(): array
    {
        return [
            new MerchantProfileMerchantPostCreatePlugin(),
            new MerchantStockMerchantPostCreatePlugin(),
            new MerchantAclEntitiesMerchantPostCreatePlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantPostUpdatePluginInterface>
     */
    protected function getMerchantPostUpdatePlugins(): array
    {
        return [
            new MerchantProfileMerchantPostUpdatePlugin(),
            new SyncMerchantUsersStatusMerchantPostUpdatePlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\MerchantExtension\Dependency\Plugin\MerchantBulkExpanderPluginInterface>
     */
    protected function getMerchantBulkExpanderPlugins(): array
    {
        return [
            new MerchantProfileMerchantBulkExpanderPlugin(),
            new MerchantStockMerchantBulkExpanderPlugin(),
            new MerchantCategoryMerchantBulkExpanderPlugin(),
        ];
    }
}
