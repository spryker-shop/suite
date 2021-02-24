<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductOfferDataImport\Business\Model\Step;

use Pyz\Zed\PriceProductOfferDataImport\Business\Model\DataSet\CombinedPriceProductOfferDataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\Step\PriceProductStoreWriterStep;

class CombinedPriceProductStoreWriterStep extends PriceProductStoreWriterStep
{
    protected const VALUE_NET = CombinedPriceProductOfferDataSetInterface::VALUE_NET;
    protected const VALUE_GROSS = CombinedPriceProductOfferDataSetInterface::VALUE_GROSS;
}
