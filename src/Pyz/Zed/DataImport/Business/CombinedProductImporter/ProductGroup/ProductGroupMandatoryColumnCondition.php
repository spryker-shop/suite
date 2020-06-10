<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProductImporter\ProductGroup;

use Pyz\Zed\DataImport\Business\CombinedProductImporter\DataSet\CombinedProductMandatoryColumnCondition;

class ProductGroupMandatoryColumnCondition extends CombinedProductMandatoryColumnCondition
{
    /**
     * @return string[]
     */
    protected function getMandatoryColumns(): array
    {
        return [
            ProductGroupWriter::COLUMN_PRODUCT_GROUP_KEY,
            ProductGroupWriter::COLUMN_POSITION,
        ];
    }
}
