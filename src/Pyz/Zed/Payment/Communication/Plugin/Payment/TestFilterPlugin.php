<?php

namespace Pyz\Zed\Payment\Communication\Plugin\Payment;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Payment\Dependency\Plugin\Payment\PaymentMethodFilterPluginInterface;

class TestFilterPlugin implements PaymentMethodFilterPluginInterface
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
        $paymentMethodsTransfer->setMethods(new \ArrayObject(array_slice($paymentMethodsTransfer->getMethods()->getArrayCopy(), 1)));

        return $paymentMethodsTransfer;
    }
}
