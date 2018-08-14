<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\ProductImage;

use Generated\Shared\Transfer\DemoDataGeneratorTransfer;

interface ProductImageGeneratorInterface
{
    /**
     * Specification:
     * - Generate product_abstract_store.csv file with demo data for product image importer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductImageCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;
}
