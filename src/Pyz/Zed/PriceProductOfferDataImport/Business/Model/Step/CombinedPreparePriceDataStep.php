<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductOfferDataImport\Business\Model\Step;

use Pyz\Zed\PriceProductOfferDataImport\Business\Model\DataSet\CombinedPriceProductOfferDataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\PreparePriceDataStep;

class CombinedPreparePriceDataStep extends PreparePriceDataStep
{
    protected const PRICE_DATA_VOLUME_PRICES = CombinedPriceProductOfferDataSetInterface::PRICE_DATA_VOLUME_PRICES;
}
