<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\PaymentsRestApi;

use Spryker\Glue\PaymentsRestApi\PaymentsRestApiConfig as SprykerPaymentsRestApiConfig;
use Spryker\Shared\DummyPayment\DummyPaymentConfig;
use SprykerEco\Shared\Unzer\UnzerConfig;

class PaymentsRestApiConfig extends SprykerPaymentsRestApiConfig
{
    /**
     * @var array<string, int>
     */
    protected const PAYMENT_METHOD_PRIORITY = [
        DummyPaymentConfig::PAYMENT_METHOD_INVOICE => 1,
        DummyPaymentConfig::PAYMENT_METHOD_CREDIT_CARD => 2,
        UnzerConfig::PAYMENT_METHOD_KEY_MARKETPLACE_CREDIT_CARD => 3,
        UnzerConfig::PAYMENT_METHOD_KEY_CREDIT_CARD => 4,
        UnzerConfig::PAYMENT_METHOD_KEY_MARKETPLACE_SOFORT => 5,
        UnzerConfig::PAYMENT_METHOD_KEY_SOFORT => 6,
        UnzerConfig::PAYMENT_METHOD_KEY_MARKETPLACE_BANK_TRANSFER => 7,
        UnzerConfig::PAYMENT_METHOD_KEY_BANK_TRANSFER => 8,
    ];

    /**
     * @var array<string, mixed>
     */
    protected const PAYMENT_METHOD_REQUIRED_FIELDS = [
        DummyPaymentConfig::PROVIDER_NAME => [
            DummyPaymentConfig::PAYMENT_METHOD_INVOICE => [
                'dummyPaymentInvoice.dateOfBirth',
            ],
            DummyPaymentConfig::PAYMENT_METHOD_CREDIT_CARD => [
                'dummyPaymentCreditCard.cardType',
                'dummyPaymentCreditCard.cardNumber',
                'dummyPaymentCreditCard.nameOnCard',
                'dummyPaymentCreditCard.cardExpiresMonth',
                'dummyPaymentCreditCard.cardExpiresYear',
                'dummyPaymentCreditCard.cardSecurityCode',
            ],
            UnzerConfig::PAYMENT_PROVIDER_NAME => [
                UnzerConfig::PAYMENT_METHOD_KEY_MARKETPLACE_CREDIT_CARD => [
                    'unzerPayment.paymentResource.id',
                ],
                UnzerConfig::PAYMENT_METHOD_KEY_MARKETPLACE_SOFORT => [
                    'unzerPayment.paymentResource.id',
                ],
                UnzerConfig::PAYMENT_METHOD_KEY_MARKETPLACE_BANK_TRANSFER => [
                    'unzerPayment.paymentResource.id',
                ],
                UnzerConfig::PAYMENT_METHOD_KEY_CREDIT_CARD => [
                    'unzerPayment.paymentResource.id',
                ],
                UnzerConfig::PAYMENT_METHOD_KEY_SOFORT => [
                    'unzerPayment.paymentResource.id',
                ],
                UnzerConfig::PAYMENT_METHOD_KEY_BANK_TRANSFER => [
                    'unzerPayment.paymentResource.id',
                ],
            ],
        ],
    ];
}
