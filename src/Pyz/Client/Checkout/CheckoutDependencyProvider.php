<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Checkout;

use Spryker\Client\Checkout\CheckoutDependencyProvider as SprykerCheckoutDependencyProvider;
use Spryker\Client\QuoteApproval\Plugin\Quote\QuoteApprovalProceedCheckoutCheckPlugin;

class CheckoutDependencyProvider extends SprykerCheckoutDependencyProvider
{
    /**
     * @return \Spryker\Client\CheckoutExtension\Dependency\Plugin\QuoteProceedCheckoutCheckPluginInterface[]
     */
    protected function getQuoteProceedCheckoutCheckPlugins(): array
    {
        return [
            new QuoteApprovalProceedCheckoutCheckPlugin(),
        ];
    }
}
