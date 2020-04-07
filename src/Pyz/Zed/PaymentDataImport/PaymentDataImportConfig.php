<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PaymentDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\PaymentDataImport\PaymentDataImportConfig as SprykerPaymentDataImportConfig;

class PaymentDataImportConfig extends SprykerPaymentDataImportConfig
{
    public const IMPORT_TYPE_PAYMENT_METHOD = 'payment-method';
    public const IMPORT_TYPE_PAYMENT_METHOD_STORE = 'payment-method-store';

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getPaymentMethodDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration('payment_method.csv', static::IMPORT_TYPE_PAYMENT_METHOD);
    }

    /**
     * @api
     *
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getPaymentMethodStoreDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration('payment_method_store.csv', static::IMPORT_TYPE_PAYMENT_METHOD_STORE);
    }
}
