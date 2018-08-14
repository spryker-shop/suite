<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business;

use Generated\Shared\Transfer\DemoDataGeneratorTransfer;

interface DemoDataGeneratorFacadeInterface
{
    /**
     * Specification:
     * - Create product_abstract.csv file.
     * - Fill created file with demo data for product abstract importer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductAbstractCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;

    /**
     * - Create product_concrete.csv file.
     * - Fill created file with demo data for product concrete importer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductConcreteCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;

    /**
     * - Create price_product.csv file.
     * - Fill created file with demo data for price product importer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductPriceCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;

    /**
     * - Create product_abstract_store.csv file.
     * - Fill created file with demo data for product abstract store importer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductAbstractStoreCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;

    /**
     * - Create product_image.csv file.
     * - Fill created file with demo data for product image importer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductImageCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;

    /**
     * - Create product_stock.csv file.
     * - Fill created file with demo data for stock product importer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductStockCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;

    /**
     * - Get plugin type from $demoDataGeneratorTransfer and generate specific demo data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function generate(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;
}
