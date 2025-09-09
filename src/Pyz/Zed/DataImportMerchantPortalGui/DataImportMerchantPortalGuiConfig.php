<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\DataImportMerchantPortalGui;

use Spryker\Zed\DataImportMerchantPortalGui\DataImportMerchantPortalGuiConfig as SprykerDataImportMerchantPortalGuiConfig;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class DataImportMerchantPortalGuiConfig extends SprykerDataImportMerchantPortalGuiConfig
{
    /**
     * @return list<string>
     */
    public function getSupportedImporterTypes(): array
    {
        return [
            MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_COMBINED_PRODUCT,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function getDataImportTemplates(): array
    {
        return [
            'CSV template Product' => 'js/static/MerchantProductDataImport/data/files/combined_product.csv',
        ];
    }
}
