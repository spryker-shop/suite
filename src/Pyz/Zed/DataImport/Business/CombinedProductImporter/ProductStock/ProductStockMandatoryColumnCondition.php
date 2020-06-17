<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProductImporter\ProductStock;

use Pyz\Zed\DataImport\Business\CombinedProductImporter\DataSet\CombinedProductMandatoryColumnCondition;

class ProductStockMandatoryColumnCondition extends CombinedProductMandatoryColumnCondition
{
    /**
     * @return string[]
     */
    protected function getMandatoryColumns(): array
    {
        return [
            ProductStockHydratorStep::COLUMN_NAME,
            ProductStockHydratorStep::COLUMN_QUANTITY,
            ProductStockHydratorStep::COLUMN_IS_NEVER_OUT_OF_STOCK,
            ProductStockHydratorStep::COLUMN_IS_BUNDLE,
        ];
    }
}
