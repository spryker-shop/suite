<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantSalesOrder\Communication;

use Pyz\Zed\MerchantSalesOrder\MerchantSalesOrderDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToSalesFacadeInterface;

class MerchantSalesOrderCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\MerchantSalesOrder\Dependency\Facade\MerchantSalesOrderToSalesFacadeInterface
     */
    public function getSalesFacade(): MerchantSalesOrderToSalesFacadeInterface
    {
        return $this->getProvidedDependency(MerchantSalesOrderDependencyProvider::FACADE_SALES);
    }
}
