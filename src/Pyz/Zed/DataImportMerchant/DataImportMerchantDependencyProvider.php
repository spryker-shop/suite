<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\DataImportMerchant;

use Spryker\Zed\DataImportMerchant\DataImportMerchantDependencyProvider as SprykerDataImportMerchantDependencyProvider;
use Spryker\Zed\MerchantProductDataImport\Communication\Plugin\DataImportMerchant\MerchantCombinedProductMerchantFileRequestExpanderPlugin;
use Spryker\Zed\MerchantProductDataImport\Communication\Plugin\DataImportMerchant\MerchantCombinedProductMerchantFileValidationPlugin;
use Spryker\Zed\MerchantProductDataImport\Communication\Plugin\DataImportMerchant\MerchantCombinedProductPossibleCsvHeaderExpanderPlugin;
use Spryker\Zed\User\Communication\Plugin\DataImportMerchant\UserDataImportMerchantFileExpanderPlugin;

class DataImportMerchantDependencyProvider extends SprykerDataImportMerchantDependencyProvider
{
    /**
     * @return list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileValidatorPluginInterface>
     */
    protected function getDataImportMerchantFileValidatorPlugins(): array
    {
        return [
            new MerchantCombinedProductMerchantFileValidationPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileExpanderPluginInterface>
     */
    protected function getDataImportMerchantFileExpanderPlugins(): array
    {
        return [
            new UserDataImportMerchantFileExpanderPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\DataImportMerchantFileRequestExpanderPluginInterface>
     */
    protected function getDataImportMerchantFileRequestExpanderPlugins(): array
    {
        return [
            new MerchantCombinedProductMerchantFileRequestExpanderPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\DataImportMerchantExtension\Dependency\Plugin\PossibleCsvHeaderExpanderPluginInterface>
     */
    protected function getPossibleCsvHeaderExpanderPlugins(): array
    {
        return [
            new MerchantCombinedProductPossibleCsvHeaderExpanderPlugin(),
        ];
    }
}
