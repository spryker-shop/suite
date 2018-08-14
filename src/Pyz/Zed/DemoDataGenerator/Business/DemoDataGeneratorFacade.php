<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business;

use Generated\Shared\Transfer\DemoDataGeneratorTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\DemoDataGenerator\Business\DemoDataGeneratorBusinessFactory getFactory()
 */
class DemoDataGeneratorFacade extends AbstractFacade implements DemoDataGeneratorFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductAbstractCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
    {
        $this->getFactory()
            ->createAbstractProductDemoDataGenerator()
            ->createProductAbstractCsvDemoData($demoDataGeneratorTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductConcreteCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
    {
        $this->getFactory()
            ->createConcreteProductDemoDataGenerator()
            ->createProductConcreteCsvDemoData($demoDataGeneratorTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductPriceCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
    {
        $this->getFactory()
            ->createProductPriceDemoDataGenerator()
            ->createProductPriceCsvDemoData($demoDataGeneratorTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductAbstractStoreCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
    {
        $this->getFactory()
            ->createProductAbstractStoreDemoDataGenerator()
            ->createProductAbstractStoreCsvDemoData($demoDataGeneratorTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductImageCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
    {
        $this->getFactory()
            ->createProductImageDemoDataGenerator()
            ->createProductImageCsvDemoData($demoDataGeneratorTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function createProductStockCsvDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
    {
        $this->getFactory()
            ->createProductStockDemoDataGenerator()
            ->createProductStockCsvDemoData($demoDataGeneratorTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function generate(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
    {
        $demoDataGeneratorFactory = $this->getFactory();
        $demoDataGeneratorFactory->createDemoDataGenerator()
            ->generate($demoDataGeneratorTransfer, $demoDataGeneratorFactory->createProcessPluginResolver());
    }
}
