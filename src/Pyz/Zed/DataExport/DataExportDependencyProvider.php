<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataExport;

use Spryker\Zed\DataExport\DataExportDependencyProvider as SprykerDataExportDependencyProvider;
use Spryker\Zed\MerchantSalesOrderDataExport\Communication\Plugin\DataExport\MerchantOrderDataEntityExporterPlugin;
use Spryker\Zed\MerchantSalesOrderDataExport\Communication\Plugin\DataExport\MerchantOrderExpenseDataEntityExporterPlugin;
use Spryker\Zed\MerchantSalesOrderDataExport\Communication\Plugin\DataExport\MerchantOrderItemDataEntityExporterPlugin;
use Spryker\Zed\SalesDataExport\Communication\Plugin\DataExport\OrderDataEntityExporterPlugin;
use Spryker\Zed\SalesDataExport\Communication\Plugin\DataExport\OrderExpenseDataEntityExporterPlugin;
use Spryker\Zed\SalesDataExport\Communication\Plugin\DataExport\OrderItemDataEntityExporterPlugin;

class DataExportDependencyProvider extends SprykerDataExportDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\DataExportExtension\Dependency\Plugin\DataEntityExporterPluginInterface>
     */
    protected function getDataEntityExporterPlugins(): array
    {
        return [
            new OrderDataEntityExporterPlugin(),
            new OrderItemDataEntityExporterPlugin(),
            new OrderExpenseDataEntityExporterPlugin(),
            new MerchantOrderDataEntityExporterPlugin(),
            new MerchantOrderItemDataEntityExporterPlugin(),
            new MerchantOrderExpenseDataEntityExporterPlugin(),
        ];
    }
}
