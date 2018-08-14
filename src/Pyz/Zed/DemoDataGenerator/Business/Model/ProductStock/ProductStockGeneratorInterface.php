<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\ProductStock;

use Generated\Shared\Transfer\DemoDataGeneratorTransfer;

interface ProductStockGeneratorInterface
{
    /**
     * Specification:
     * - Generate stock_product.csv file with demo data for stock product importer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductStockCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;
}
