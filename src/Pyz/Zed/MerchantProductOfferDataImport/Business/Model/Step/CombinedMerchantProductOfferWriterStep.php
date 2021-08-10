<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProductOfferDataImport\Business\Model\Step;

use Pyz\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\CombinedMerchantProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantProductOfferWriterStep;

class CombinedMerchantProductOfferWriterStep extends MerchantProductOfferWriterStep
{
    protected const PRODUCT_OFFER_REFERENCE = CombinedMerchantProductOfferDataSetInterface::PRODUCT_OFFER_REFERENCE;
    protected const CONCRETE_SKU = CombinedMerchantProductOfferDataSetInterface::CONCRETE_SKU;
    protected const MERCHANT_SKU = CombinedMerchantProductOfferDataSetInterface::MERCHANT_SKU;
    protected const MERCHANT_REFERENCE = CombinedMerchantProductOfferDataSetInterface::MERCHANT_REFERENCE;
    protected const IS_ACTIVE = CombinedMerchantProductOfferDataSetInterface::IS_ACTIVE;
    protected const APPROVAL_STATUS = CombinedMerchantProductOfferDataSetInterface::APPROVAL_STATUS;
}
