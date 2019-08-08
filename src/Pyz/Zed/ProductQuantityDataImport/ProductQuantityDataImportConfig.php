<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductQuantityDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\ProductQuantityDataImport\ProductQuantityDataImportConfig as SprykerProductQuantityDataImportConfig;

class ProductQuantityDataImportConfig extends SprykerProductQuantityDataImportConfig
{
    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductQuantityDataImportConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration('product_quantity.csv', static::IMPORT_TYPE_PRODUCT_QUANTITY);
    }
}
