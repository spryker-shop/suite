<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantCommissionGui;

use Spryker\Zed\MerchantCommissionDataExport\Communication\Plugin\MerchantCommissionGui\DataExportMerchantCommissionExportPlugin;
use Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiDependencyProvider as SprykerMerchantCommissionGuiDependencyProvider;
use Spryker\Zed\MerchantCommissionGuiExtension\Communication\Dependency\Plugin\MerchantCommissionExportPluginInterface;

class MerchantCommissionGuiDependencyProvider extends SprykerMerchantCommissionGuiDependencyProvider
{
    /**
     * @return \Spryker\Zed\MerchantCommissionGuiExtension\Communication\Dependency\Plugin\MerchantCommissionExportPluginInterface
     */
    protected function getMerchantCommissionExportPlugin(): MerchantCommissionExportPluginInterface
    {
        return new DataExportMerchantCommissionExportPlugin();
    }
}
