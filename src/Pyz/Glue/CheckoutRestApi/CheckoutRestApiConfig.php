<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CheckoutRestApi;

use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig as SprykerCheckoutRestApiConfig;

class CheckoutRestApiConfig extends SprykerCheckoutRestApiConfig
{
    protected const PAYMENT_REQUIRED_DATA_COMMON = [
        'paymentMethod' => '',
        'paymentProvider' => '',
        'paymentSelection' => '',
        'amount' => '',
    ];

    protected const PAYMENT_REQUIRED_DATA = [
        'dummyPaymentInvoice' => [
            'dateOfBirth' => '',
        ],
        'dummyPaymentCreditCard' => [
            'cardType' => '',
            'cardNumber' => '',
            'nameOnCard' => '',
            'cardExpiresMonth' => '',
            'cardExpiresYear' => '',
            'cardSecurityCode' => '',
        ],
    ];
}
