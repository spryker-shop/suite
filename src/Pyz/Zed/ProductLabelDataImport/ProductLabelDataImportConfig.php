<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductLabelDataImport;

use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Spryker\Zed\ProductLabelDataImport\ProductLabelDataImportConfig as SprykerProductLabelDataImportConfig;

class ProductLabelDataImportConfig extends SprykerProductLabelDataImportConfig
{
    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductLabelDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        return $this->buildImporterConfiguration('product_label.csv', static::IMPORT_TYPE_PRODUCT_LABEL);
    }
}
