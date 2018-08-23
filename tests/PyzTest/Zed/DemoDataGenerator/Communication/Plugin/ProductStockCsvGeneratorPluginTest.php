<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DemoDataGenerator\Communication\Plugin;

use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductStockCsvGeneratorPlugin;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DemoDataGenerator
 * @group Communication
 * @group Plugin
 * @group ProductStockCsvGeneratorPluginTest
 * Add your own group annotations below this line
 */
class ProductStockCsvGeneratorPluginTest extends AbstractCsvGenerator
{
    protected const DEMO_DATA_TYPE = 'product_stock';
    protected const TEST_DEMO_DATA = 'product_stock.csv';

    /**
     * @return void
     */
    public function testProductStockCsvDemoDataGenerator(): void
    {
        $demoDataGeneratorTransfer = $this->createDemoDataGeneratorTransfer(static::DEMO_DATA_TYPE);
        $demoDataGeneratorConfigMock = $this->createDemoDataConfigMock('getProductStockCsvPath', static::TEST_DEMO_DATA);
        $demoDataGeneratorFactoryMock = $this->createDemoDataGeneratorFactoryMock($demoDataGeneratorConfigMock);
        $demoDataGeneratorFacade = $this->createDemoDataGeneratorFacade($demoDataGeneratorFactoryMock);

        $productAbstractCsvDemoDataGeneratorPlugin = new ProductStockCsvGeneratorPlugin();
        $productAbstractCsvDemoDataGeneratorPlugin->setFacade($demoDataGeneratorFacade);
        $productAbstractCsvDemoDataGeneratorPlugin->generateDemoData($demoDataGeneratorTransfer);

        $this->tester->assertFileExists(codecept_data_dir() . static::TEST_DEMO_DATA);
        $this->tester->deleteFile(codecept_data_dir() . static::TEST_DEMO_DATA);
    }
}
