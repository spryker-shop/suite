<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantProductOfferDataImport\Business\Model\Step;

use Pyz\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\CombinedMerchantProductOfferDataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step\MerchantReferenceToIdMerchantStep;

class CombinedMerchantReferenceToIdMerchantStep extends MerchantReferenceToIdMerchantStep
{
    protected const MERCHANT_REFERENCE = CombinedMerchantProductOfferDataSetInterface::MERCHANT_REFERENCE;
}
