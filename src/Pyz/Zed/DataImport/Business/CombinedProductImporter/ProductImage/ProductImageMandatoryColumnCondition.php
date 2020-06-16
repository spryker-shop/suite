<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProductImporter\ProductImage;

use Pyz\Zed\DataImport\Business\CombinedProductImporter\DataSet\CombinedProductMandatoryColumnCondition;

class ProductImageMandatoryColumnCondition extends CombinedProductMandatoryColumnCondition
{
    /**
     * @return string[]
     */
    protected function getMandatoryColumns(): array
    {
        return [
            ProductImageHydratorStep::COLUMN_IMAGE_SET_NAME,
            ProductImageHydratorStep::COLUMN_EXTERNAL_URL_LARGE,
            ProductImageHydratorStep::COLUMN_EXTERNAL_URL_SMALL,
            ProductImageHydratorStep::COLUMN_LOCALE,
            ProductImageHydratorStep::COLUMN_SORT_ORDER,
            ProductImageHydratorStep::COLUMN_PRODUCT_IMAGE_KEY,
            ProductImageHydratorStep::COLUMN_PRODUCT_IMAGE_SET_KEY,
            ProductImageHydratorStep::COLUMN_ASSIGNED_PRODUCT_TYPE,
        ];
    }
}
