<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Shared\MarketplacePayment;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class MarketplacePaymentConfig extends AbstractBundleConfig
{
    public const PAYMENT_PROVIDER_NAME = 'marketplacePayment';
    public const PAYMENT_METHOD_MARKETPLACE_PAYMENT_INVOICE = 'marketplacePaymentInvoice';
}
