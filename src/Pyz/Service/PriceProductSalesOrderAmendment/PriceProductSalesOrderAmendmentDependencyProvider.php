<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Service\PriceProductSalesOrderAmendment;

use Spryker\Service\PriceProductOfferSalesOrderAmendmentConnector\Plugin\PriceProductSalesOrderAmendment\ProductOfferOriginalSalesOrderItemPriceGroupKeyExpanderPlugin;
use Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentDependencyProvider as SprykerPriceProductSalesOrderAmendmentDependencyProvider;

class PriceProductSalesOrderAmendmentDependencyProvider extends SprykerPriceProductSalesOrderAmendmentDependencyProvider
{
    /**
     * @return list<\Spryker\Service\PriceProductSalesOrderAmendmentExtension\Dependency\Plugin\OriginalSalesOrderItemPriceGroupKeyExpanderPluginInterface>
     */
    protected function getOriginalSalesOrderItemPriceGroupKeyExpanderPlugins(): array
    {
        return [
            new ProductOfferOriginalSalesOrderItemPriceGroupKeyExpanderPlugin(),
        ];
    }
}
