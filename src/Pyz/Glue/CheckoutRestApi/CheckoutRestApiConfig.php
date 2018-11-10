<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CheckoutRestApi;

use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig as SprykerCheckoutRestApiConfig;

class CheckoutRestApiConfig extends SprykerCheckoutRestApiConfig
{
    protected const PAYMENT_REQUIRED_FIELDS = [
        'paymentMethod',
        'paymentProvider',
        'paymentSelection',
    ];

    protected const PAYMENT_METHOD_REQUIRED_FIELDS = [
        'dummyPaymentInvoice.dateOfBirth',
        'dummyPaymentCreditCard.cardType',
        'dummyPaymentCreditCard.cardNumber',
        'dummyPaymentCreditCard.nameOnCard',
        'dummyPaymentCreditCard.cardExpiresMonth',
        'dummyPaymentCreditCard.cardExpiresYear',
        'dummyPaymentCreditCard.cardSecurityCode',
    ];
}
