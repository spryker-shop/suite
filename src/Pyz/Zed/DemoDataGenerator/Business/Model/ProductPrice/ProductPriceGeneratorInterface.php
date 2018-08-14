<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\ProductPrice;

use Generated\Shared\Transfer\DemoDataGeneratorTransfer;

interface ProductPriceGeneratorInterface
{
    /**
     * Specification:
     * - Generate price_product.csv file with demo data for price product importer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductPriceCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;
}
