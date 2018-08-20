<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MultiCartPage;

use SprykerShop\Yves\MultiCartPage\MultiCartPageDependencyProvider as SprykerShopMultiCartPageDependencyProvider;
use SprykerShop\Yves\SharedCartWidget\Plugin\CartDeleteCompanyUsersListWidgetPlugin;

class MultiCartPageDependencyProvider extends SprykerShopMultiCartPageDependencyProvider
{
    /**
     * @return string[]
     */
    protected function getCartDeleteCompanyUsersListWidgetPlugins(): array
    {
        return [
            CartDeleteCompanyUsersListWidgetPlugin::class, #SharedCartFeature
        ];
    }
}
