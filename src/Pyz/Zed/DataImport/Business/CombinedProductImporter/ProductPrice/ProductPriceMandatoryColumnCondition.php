<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProductImporter\ProductPrice;

use Pyz\Zed\DataImport\Business\CombinedProductImporter\DataSet\CombinedProductMandatoryColumnCondition;

class ProductPriceMandatoryColumnCondition extends CombinedProductMandatoryColumnCondition
{
    /**
     * @return string[]
     */
    protected function getMandatoryColumns(): array
    {
        return [
            ProductPriceHydratorStep::COLUMN_CURRENCY,
            ProductPriceHydratorStep::COLUMN_STORE,
            ProductPriceHydratorStep::COLUMN_PRICE_NET,
            ProductPriceHydratorStep::COLUMN_PRICE_GROSS,
            ProductPriceHydratorStep::COLUMN_PRICE_DATA,
            ProductPriceHydratorStep::COLUMN_PRICE_DATA_CHECKSUM,
            ProductPriceHydratorStep::COLUMN_PRICE_TYPE,
            ProductPriceHydratorStep::COLUMN_ASSIGNED_PRODUCT_TYPE,
        ];
    }
}
