<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ShopCmsSlot;

use SprykerShop\Yves\CmsSlotBlockWidget\Plugin\CmsSlotBlockWidgetCmsSlotContentPlugin;
use SprykerShop\Yves\ShopCmsSlot\ShopCmsSlotDependencyProvider as SprykerShopShopCmsSlotDependencyProvider;
use SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface;

class ShopCmsSlotDependencyProvider extends SprykerShopShopCmsSlotDependencyProvider
{
    /**
     * @return \SprykerShop\Yves\ShopCmsSlotExtension\Dependency\Plugin\CmsSlotContentPluginInterface
     */
    protected function getCmsSlotContentPlugin(): CmsSlotContentPluginInterface
    {
        return new CmsSlotBlockWidgetCmsSlotContentPlugin();
    }
}
