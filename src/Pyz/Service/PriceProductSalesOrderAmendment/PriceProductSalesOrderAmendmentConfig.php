<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Service\PriceProductSalesOrderAmendment;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Service\PriceProductSalesOrderAmendment\PriceProductSalesOrderAmendmentConfig as SprykerPriceProductSalesOrderAmendmentConfig;

class PriceProductSalesOrderAmendmentConfig extends SprykerPriceProductSalesOrderAmendmentConfig
{
    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer|null $quoteTransfer
     *
     * @return bool
     */
    public function useBestPriceBetweenOriginalAndSalesOrderItemPrice(?QuoteTransfer $quoteTransfer = null): bool
    {
        if ($quoteTransfer && $quoteTransfer->getStore()?->getName() === 'AT') {
            return false;
        }

        return true;
    }
}
