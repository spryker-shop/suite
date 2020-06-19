<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstract;

use Pyz\Zed\DataImport\Business\CombinedProduct\DataSet\CombinedProductMandatoryColumnCondition;

class CombinedProductAbstractMandatoryColumnCondition extends CombinedProductMandatoryColumnCondition
{
    /**
     * @return string[]
     */
    protected function getMandatoryColumns(): array
    {
        return [
                CombinedProductAbstractHydratorStep::COLUMN_CATEGORY_KEY,
                CombinedProductAbstractHydratorStep::COLUMN_CATEGORY_PRODUCT_ORDER,
                CombinedProductAbstractHydratorStep::COLUMN_URL,
                CombinedProductAbstractHydratorStep::COLUMN_IS_FEATURED,
                CombinedProductAbstractHydratorStep::COLUMN_COLOR_CODE,
                CombinedProductAbstractHydratorStep::COLUMN_TAX_SET_NAME,
                CombinedProductAbstractHydratorStep::COLUMN_META_TITLE,
                CombinedProductAbstractHydratorStep::COLUMN_META_KEYWORDS,
                CombinedProductAbstractHydratorStep::COLUMN_META_DESCRIPTION,
                CombinedProductAbstractHydratorStep::COLUMN_NEW_FROM,
                CombinedProductAbstractHydratorStep::COLUMN_NEW_TO,
                CombinedProductAbstractHydratorStep::COLUMN_ASSIGNED_PRODUCT_TYPE, // is it mandatory column?
        ];
    }
}
