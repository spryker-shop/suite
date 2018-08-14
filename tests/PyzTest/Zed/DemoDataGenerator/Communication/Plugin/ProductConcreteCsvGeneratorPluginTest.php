<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DemoDataGenerator\Communication\Plugin;

use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductConcreteCsvGeneratorPlugin;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DemoDataGenerator
 * @group Communication
 * @group Plugin
 * @group ProductConcreteCsvGeneratorPluginTest
 * Add your own group annotations below this line
 */
class ProductConcreteCsvGeneratorPluginTest extends AbstractCsvGenerator
{
    protected const DEMO_DATA_TYPE = 'product_concrete';
    protected const TEST_DEMO_DATA = 'product_concrete.csv';
    protected const TEST_ROW_NUMBER = 3;

    /**
     * @return void
     */
    public function testProductConcreteCsvDemoDataGenerator(): void
    {
        $demoDataGeneratorTransfer = $this->createDemoDataGeneratorTransfer(static::DEMO_DATA_TYPE, static::TEST_ROW_NUMBER);
        $demoDataGeneratorConfigMock = $this->createDemoDataConfigMock('getProductConcreteCsvPath', static::TEST_DEMO_DATA);
        $demoDataGeneratorFactoryMock = $this->createDemoDataGeneratorFactoryMock($demoDataGeneratorConfigMock);
        $demoDataGeneratorFacade = $this->createDemoDataGeneratorFacade($demoDataGeneratorFactoryMock);

        $productAbstractCsvDemoDataGeneratorPlugin = new ProductConcreteCsvGeneratorPlugin();
        $productAbstractCsvDemoDataGeneratorPlugin->setFacade($demoDataGeneratorFacade);
        $productAbstractCsvDemoDataGeneratorPlugin->generateDemoData($demoDataGeneratorTransfer);

        $this->tester->assertFileExists(codecept_data_dir() . static::TEST_DEMO_DATA);
        $this->tester->deleteFile(codecept_data_dir() . static::TEST_DEMO_DATA);
    }
}
