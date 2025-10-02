<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\DataImportMerchant;

use Spryker\Zed\DataImportMerchant\DataImportMerchantConfig as SprykerDataImportMerchantConfig;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;
use Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig;

class DataImportMerchantConfig extends SprykerDataImportMerchantConfig
{
    /**
     * @return list<string>
     */
    public function getSupportedImporterTypes(): array
    {
        return [
            MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT,
            MerchantProductOfferDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT_OFFER,
        ];
    }

    /**
     * @return list<string>
     */
    public function getSupportedContentTypes(): array
    {
        return [
            'text/csv',
            'application/csv',
            'text/plain',
        ];
    }
}
