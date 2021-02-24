<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductOfferValidityDataImport\Business\Model\Step;

use Pyz\Zed\ProductOfferValidityDataImport\Business\Model\DataSet\CombinedProductOfferValidityDataSetInterface;
use Spryker\Zed\ProductOfferValidityDataImport\Business\Step\ProductOfferValidityWriterStep;

class CombinedProductOfferValidityWriterStep extends ProductOfferValidityWriterStep
{
    protected const PRODUCT_VALID_FROM = CombinedProductOfferValidityDataSetInterface::VALID_FROM;
    protected const PRODUCT_VALID_TO = CombinedProductOfferValidityDataSetInterface::VALID_TO;
}
