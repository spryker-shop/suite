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
     * @param int $rowsNumber
     *
     * @return void
     */
    public function createProductAbstractCsvDemoData(int $rowsNumber): void;

    /**
     * @param int $rowsNumber
     *
     * @return void
     */
    public function createProductConcreteCsvDemoData(int $rowsNumber): void;

    /**
     * @return void
     */
    public function createPriceProductCsvDemoData(): void;

    /**
     * @return void
     */
    public function createProductAbstractStoreCsvDemoData(): void;

    /**
     * @return void
     */
    public function createProductImageCsvDemoData(): void;

    /**
     * @return void
     */
    public function createStockProductCsvDemoData(): void;

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function generate(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;
}
