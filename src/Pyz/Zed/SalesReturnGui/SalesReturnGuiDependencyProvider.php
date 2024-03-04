<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesReturnGui;

use Spryker\Zed\MerchantSalesReturnGui\Communication\Plugin\SalesReturnGui\MerchantSalesReturnCreateFormHandlerPlugin;
use Spryker\Zed\ProductBundle\Communication\Plugin\SalesReturnGui\ProductBundleReturnCreateFormHandlerPlugin;
use Spryker\Zed\SalesReturnGui\SalesReturnGuiDependencyProvider as SprykerSalesReturnGuiDependencyProvider;

class SalesReturnGuiDependencyProvider extends SprykerSalesReturnGuiDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\SalesReturnGuiExtension\Dependency\Plugin\ReturnCreateFormHandlerPluginInterface>
     */
    protected function getReturnCreateFormHandlerPlugins(): array
    {
        return [
            new ProductBundleReturnCreateFormHandlerPlugin(),
            new MerchantSalesReturnCreateFormHandlerPlugin(),
        ];
    }
}
