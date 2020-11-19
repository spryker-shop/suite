<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantSalesOrderMerchantUserGui;

use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\MerchantSalesOrderMerchantUserGui\MerchantSalesOrderMerchantUserGuiDependencyProvider as SprykerMerchantSalesOrderMerchantUserGuiDependencyProvider;
use Spryker\Zed\ShipmentGui\Communication\Plugin\Form\ItemFormTypePlugin;
use Spryker\Zed\ShipmentGui\Communication\Plugin\Form\ShipmentFormTypePlugin;

class MerchantSalesOrderMerchantUserGuiDependencyProvider extends SprykerMerchantSalesOrderMerchantUserGuiDependencyProvider
{
    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getShipmentFormTypePlugin(): FormTypeInterface
    {
        return new ShipmentFormTypePlugin();
    }

    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    public function getItemFormTypePlugin(): FormTypeInterface
    {
        return new ItemFormTypePlugin();
    }
}
