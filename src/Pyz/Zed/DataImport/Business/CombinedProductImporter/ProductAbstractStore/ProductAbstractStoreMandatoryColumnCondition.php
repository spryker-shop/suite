<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProductImporter\ProductAbstractStore;

use Pyz\Zed\DataImport\Business\CombinedProductImporter\DataSet\CombinedProductMandatoryColumnCondition;

class ProductAbstractStoreMandatoryColumnCondition extends CombinedProductMandatoryColumnCondition
{
    /**
     * @return string[]
     */
    protected function getMandatoryColumns(): array
    {
        return [
        ];
    }
}
