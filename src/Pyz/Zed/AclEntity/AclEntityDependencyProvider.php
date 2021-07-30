<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AclEntity;

use Spryker\Communication\Plugin\AclEntity\AclEntityEnablerPlugin;
use Spryker\Zed\AclEntity\AclEntityDependencyProvider as SprykerAclEntityDependencyProvider;
use Spryker\Zed\AclEntityDummyProduct\Communication\DummyProductAclEntityMetadataConfigExpanderPlugin;
use Spryker\Zed\AclMerchantPortal\Communication\Plugin\AclEntity\MerchantPortalAclEntityMetadataConfigExpanderPlugin;
use Spryker\Zed\Console\Communication\Plugin\AclEntity\ConsoleAclEntityDisablerPlugin;
use Spryker\Zed\SecuritySystemUser\Communication\Plugin\AclEntity\SecuritySystemUserAclEntityDisablerPlugin;

class AclEntityDependencyProvider extends SprykerAclEntityDependencyProvider
{
    /**
     * @return \Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityMetadataConfigExpanderPluginInterface[]
     */
    protected function getAclEntityMetadataCollectionExpanderPlugins(): array
    {
        return [
            new DummyProductAclEntityMetadataConfigExpanderPlugin(),
            new MerchantPortalAclEntityMetadataConfigExpanderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityDisablerPluginInterface[]
     */
    protected function getAclEntityDisablerPlugins(): array
    {
        return [
            new SecuritySystemUserAclEntityDisablerPlugin(),
            new ConsoleAclEntityDisablerPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityEnablerPluginInterface[]
     */
    protected function getAclEntityEnablerPlugins(): array
    {
        return [
            new AclEntityEnablerPlugin(),
        ];
    }
}
