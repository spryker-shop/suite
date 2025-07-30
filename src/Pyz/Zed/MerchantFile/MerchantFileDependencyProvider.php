<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\MerchantFile;

use Spryker\Zed\FileImportMerchantPortalGui\Communication\Plugin\MerchantFile\MerchantFileImportMerchantFilePostSavePlugin;
use Spryker\Zed\MerchantFile\MerchantFileDependencyProvider as SprykerMerchantFileDependencyProvider;
use Spryker\Zed\MerchantProductDataImport\Communication\Plugin\MerchantFile\MerchantCombinedProductHeadersMerchantFileValidationPlugin;

class MerchantFileDependencyProvider extends SprykerMerchantFileDependencyProvider
{
    protected function getMerchantFilePostSavePlugins(): array
    {
        return [
            new MerchantFileImportMerchantFilePostSavePlugin(),
        ];
    }

    protected function getMerchantFileValidationPlugins(): array
    {
        return [
            new MerchantCombinedProductHeadersMerchantFileValidationPlugin(),
        ];
    }
}
