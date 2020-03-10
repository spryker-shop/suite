<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MarketplacePayment\Expander;

use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Shared\MarketplacePayment\MarketplacePaymentConfig;
use Symfony\Component\HttpFoundation\Request;

class MarketplacePaymentExpander implements MarketplacePaymentExpanderInterface
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addPaymentToQuote(Request $request, QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $quoteTransfer
            ->getPayment()
            ->setPaymentProvider(MarketplacePaymentConfig::PAYMENT_PROVIDER_NAME)
            ->setPaymentMethod($quoteTransfer->getPayment()->getPaymentSelection());

        return $quoteTransfer;
    }
}
