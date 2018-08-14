<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DemoDataGenerator\Communication\Plugin;

use PHPUnit\Framework\MockObject\MockObject;
use Pyz\Zed\DemoDataGenerator\Business\DemoDataGeneratorBusinessFactory;
use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductAbstractStoreCsvGeneratorPlugin;
use Spryker\Shared\Kernel\Store;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DemoDataGenerator
 * @group Communication
 * @group Plugin
 * @group ProductAbstractStoreCsvGeneratorPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractStoreCsvGeneratorPluginTest extends AbstractCsvGenerator
{
    protected const DEMO_DATA_TYPE = 'product_abstract_store';
    protected const TEST_DEMO_DATA = 'product_abstract_store.csv';
    protected const DEFAULT_STORE = 'DE';

    /**
     * @return void
     */
    public function testProductAbstractStoreCsvDemoDataGenerator(): void
    {
        $demoDataGeneratorTransfer = $this->createDemoDataGeneratorTransfer(static::DEMO_DATA_TYPE);
        $demoDataGeneratorConfigMock = $this->createDemoDataConfigMock('getProductAbstractStoreCsvPath', static::TEST_DEMO_DATA);
        $demoDataGeneratorFactoryMock = $this->createDemoDataGeneratorFactoryMock($demoDataGeneratorConfigMock);
        $demoDataGeneratorFacade = $this->createDemoDataGeneratorFacade($demoDataGeneratorFactoryMock);

        $productAbstractCsvDemoDataGeneratorPlugin = new ProductAbstractStoreCsvGeneratorPlugin();
        $productAbstractCsvDemoDataGeneratorPlugin->setFacade($demoDataGeneratorFacade);
        $productAbstractCsvDemoDataGeneratorPlugin->generateDemoData($demoDataGeneratorTransfer);

        $this->tester->assertFileExists(codecept_data_dir() . static::TEST_DEMO_DATA);
        $this->tester->deleteFile(codecept_data_dir() . static::TEST_DEMO_DATA);
    }

    /**
     * @param \PHPUnit\Framework\MockObject\MockObject $demoDataGeneratorConfigMock
     *
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    protected function createDemoDataGeneratorFactoryMock(MockObject $demoDataGeneratorConfigMock): MockObject
    {
        $demoDataGeneratorStoreMock = $this->createDemoDataGeneratorStoreMock();

        $demoDataGeneratorFactoryMock = $this->getMockBuilder(DemoDataGeneratorBusinessFactory::class)
            ->setMethods(['getConfig', 'getStore'])
            ->getMock();

        $demoDataGeneratorFactoryMock
            ->method('getConfig')
            ->willReturn($demoDataGeneratorConfigMock);

        $demoDataGeneratorFactoryMock
            ->method('getStore')
            ->willReturn($demoDataGeneratorStoreMock);

        return $demoDataGeneratorFactoryMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    private function createDemoDataGeneratorStoreMock(): MockObject
    {
        $storeMock = $this->getMockBuilder(Store::class)
            ->disableOriginalConstructor()
            ->getMock();

        $storeMock
            ->method('getAllowedStores')
            ->willReturn([static::DEFAULT_STORE]);

        return $storeMock;
    }
}
