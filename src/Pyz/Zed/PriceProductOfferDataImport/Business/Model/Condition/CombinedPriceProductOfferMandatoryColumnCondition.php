<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductOfferDataImport\Business\Model\Condition;

use Pyz\Zed\DataImport\Business\CombinedProduct\DataSet\CombinedProductMandatoryColumnCondition;
use Pyz\Zed\PriceProductOfferDataImport\Business\Model\DataSet\CombinedPriceProductOfferDataSetInterface;

class CombinedPriceProductOfferMandatoryColumnCondition extends CombinedProductMandatoryColumnCondition
{
    /**
     * @return array<string>
     */
    protected function getMandatoryColumns(): array
    {
        return [
            CombinedPriceProductOfferDataSetInterface::PRICE_TYPE,
            CombinedPriceProductOfferDataSetInterface::STORE,
            CombinedPriceProductOfferDataSetInterface::CURRENCY,
            CombinedPriceProductOfferDataSetInterface::VALUE_NET,
            CombinedPriceProductOfferDataSetInterface::VALUE_GROSS,
        ];
    }
}
