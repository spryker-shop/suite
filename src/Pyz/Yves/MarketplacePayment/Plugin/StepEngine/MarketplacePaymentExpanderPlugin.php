<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MarketplacePayment\Plugin\StepEngine;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\StepEngine\Dependency\Plugin\Handler\StepHandlerPluginInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Yves\MarketplacePayment\MarketplacePaymentFactory getFactory()
 */
class MarketplacePaymentExpanderPlugin extends AbstractPlugin implements StepHandlerPluginInterface
{
    /**
     * {@inheritdoc}
     * - Expands `PaymentTransfer` with payment provider and payment selection.
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function addToDataClass(Request $request, AbstractTransfer $quoteTransfer): QuoteTransfer
    {
        return $this->getFactory()
            ->createMarketplacePaymentExpander()
            ->addPaymentToQuote($request, $quoteTransfer);
    }
}
