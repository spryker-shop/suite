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
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductAbstractCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductConcreteCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createPriceProductCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductAbstractStoreCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductImageCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createStockProductCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function generate(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void;
}
