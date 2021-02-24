<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferValidityDataImport\Business\Model\Condition;

use Pyz\Zed\DataImport\Business\CombinedProduct\DataSet\CombinedProductMandatoryColumnCondition;
use Pyz\Zed\ProductOfferValidityDataImport\Business\Model\DataSet\CombinedProductOfferValidityDataSetInterface;

class CombinedProductOfferValidityMandatoryColumnCondition extends CombinedProductMandatoryColumnCondition
{
    /**
     * @return string[]
     */
    protected function getMandatoryColumns(): array
    {
        return [
            CombinedProductOfferValidityDataSetInterface::VALID_FROM,
            CombinedProductOfferValidityDataSetInterface::VALID_TO,
        ];
    }
}
