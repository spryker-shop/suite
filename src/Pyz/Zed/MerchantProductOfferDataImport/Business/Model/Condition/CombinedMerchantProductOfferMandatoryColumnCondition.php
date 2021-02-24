<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProductOfferDataImport\Business\Model\Condition;

use Pyz\Zed\DataImport\Business\CombinedProduct\DataSet\CombinedProductMandatoryColumnCondition;
use Pyz\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\CombinedMerchantProductOfferDataSetInterface;

class CombinedMerchantProductOfferMandatoryColumnCondition extends CombinedProductMandatoryColumnCondition
{
    /**
     * @return string[]
     */
    protected function getMandatoryColumns(): array
    {
        return [
            CombinedMerchantProductOfferDataSetInterface::MERCHANT_SKU,
            CombinedMerchantProductOfferDataSetInterface::MERCHANT_REFERENCE,
            CombinedMerchantProductOfferDataSetInterface::IS_ACTIVE,
            CombinedMerchantProductOfferDataSetInterface::CONCRETE_SKU,
            CombinedMerchantProductOfferDataSetInterface::APPROVAL_STATUS,
        ];
    }
}
