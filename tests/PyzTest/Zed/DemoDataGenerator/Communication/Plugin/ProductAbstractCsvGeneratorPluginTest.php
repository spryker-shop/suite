<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DemoDataGenerator\Communication\Plugin;

use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductAbstractCsvGeneratorPlugin;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DemoDataGenerator
 * @group Communication
 * @group Plugin
 * @group ProductAbstractCsvGeneratorPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractCsvGeneratorPluginTest extends AbstractCsvGenerator
{
    protected const DEMO_DATA_TYPE = 'product_abstract';
    protected const TEST_DEMO_DATA = 'product_abstract.csv';
    protected const TEST_ROW_NUMBER = 3;
    protected const TEST_CONTENT = 'digital-cameras';

    /**
     * @return void
     */
    public function testProductAbstractCsvDemoDataGenerator(): void
    {
        $demoDataGeneratorTransfer = $this->createDemoDataGeneratorTransfer(static::DEMO_DATA_TYPE, static::TEST_ROW_NUMBER);
        $demoDataGeneratorConfigMock = $this->createDemoDataConfigMock('getProductAbstractCsvPath', static::TEST_DEMO_DATA);
        $demoDataGeneratorFactoryMock = $this->createDemoDataGeneratorFactoryMock($demoDataGeneratorConfigMock);
        $demoDataGeneratorFacade = $this->createDemoDataGeneratorFacade($demoDataGeneratorFactoryMock);

        $productAbstractCsvDemoDataGeneratorPlugin = new ProductAbstractCsvGeneratorPlugin();
        $productAbstractCsvDemoDataGeneratorPlugin->setFacade($demoDataGeneratorFacade);
        $productAbstractCsvDemoDataGeneratorPlugin->generateDemoData($demoDataGeneratorTransfer);

        $this->tester->assertFileExists(codecept_data_dir() . static::TEST_DEMO_DATA);
        $this->tester->openFile(codecept_data_dir() . static::TEST_DEMO_DATA);
        $this->tester->seeInThisFile(static::TEST_CONTENT);
        $this->tester->deleteFile(codecept_data_dir() . static::TEST_DEMO_DATA);
    }
}
