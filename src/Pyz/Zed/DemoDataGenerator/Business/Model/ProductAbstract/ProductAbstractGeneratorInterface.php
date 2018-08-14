<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\ProductAbstract;

use Generated\Shared\Transfer\DemoDataGeneratorTransfer;

interface ProductAbstractGeneratorInterface
{
    /**
     * Specification:
     * - Generate product_abstract.csv file with demo data for product abstract importer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductAbstractCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;
}
