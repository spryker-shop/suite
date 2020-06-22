<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductConcrete;

use Pyz\Zed\DataImport\Business\CombinedProduct\DataSet\CombinedProductMandatoryColumnCondition;

class CombinedProductConcreteMandatoryColumnCondition extends CombinedProductMandatoryColumnCondition
{
    /**
     * @return string[]
     */
    protected function getMandatoryColumns(): array
    {
        return [
                CombinedProductConcreteHydratorStep::COLUMN_OLD_SKU,
                CombinedProductConcreteHydratorStep::COLUMN_IS_SEARCHABLE,
                CombinedProductConcreteHydratorStep::COLUMN_BUNDLES,
                CombinedProductConcreteHydratorStep::COLUMN_IS_QUANTITY_SPLITTABLE,
        ];
    }
}
