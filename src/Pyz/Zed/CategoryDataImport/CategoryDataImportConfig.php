<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CategoryDataImport;

use Spryker\Zed\CategoryDataImport\CategoryDataImportConfig as SprykerCategoryDataImportConfig;

class CategoryDataImportConfig extends SprykerCategoryDataImportConfig
{
    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCategoryDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('category.csv', static::IMPORT_TYPE_CATEGORY);
    }
}
