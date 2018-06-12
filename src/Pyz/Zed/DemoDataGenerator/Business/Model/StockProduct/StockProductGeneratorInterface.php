<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\StockProduct;

use Generated\Shared\Transfer\DemoDataGeneratorTransfer;

interface StockProductGeneratorInterface
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
    public function createStockProductCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;
}
