<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Payment\Communication\Plugin\Payment;

use ArrayObject;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Payment\Dependency\Plugin\Payment\PaymentMethodFilterPluginInterface;

class SkipFirstPaymentMethodFilterPlugin implements PaymentMethodFilterPluginInterface
{
    /**
     * Specification:
     * - Returns filtered by set of plugins array object of payments
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function filterPaymentMethods(PaymentMethodsTransfer $paymentMethodsTransfer, QuoteTransfer $quoteTransfer)
    {
        $paymentMethodsTransfer->setMethods(new ArrayObject(array_slice($paymentMethodsTransfer->getMethods()->getArrayCopy(), 1)));

        return $paymentMethodsTransfer;
    }
}
