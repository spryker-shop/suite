<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DemoDataGenerator\Communication\Plugin;

use PHPUnit\Framework\MockObject\MockObject;
use Pyz\Zed\DemoDataGenerator\Business\DemoDataGeneratorBusinessFactory;
use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductImageCsvGeneratorPlugin;
use Spryker\Shared\Kernel\Store;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DemoDataGenerator
 * @group Communication
 * @group Plugin
 * @group ProductImageCsvGeneratorPluginTest
 * Add your own group annotations below this line
 */
class ProductImageCsvGeneratorPluginTest extends AbstractCsvGenerator
{
    protected const DEMO_DATA_TYPE = 'product_image';
    protected const TEST_DEMO_DATA = 'product_image.csv';
    protected const DEFAULT_LOCALE = 'de_DE';

    /**
     * @return void
     */
    public function testProductImageCsvDemoDataGenerator(): void
    {
        $demoDataGeneratorTransfer = $this->createDemoDataGeneratorTransfer(static::DEMO_DATA_TYPE);
        $demoDataGeneratorConfigMock = $this->createDemoDataConfigMock('getProductImageCsvPath', static::TEST_DEMO_DATA);
        $demoDataGeneratorFactoryMock = $this->createDemoDataGeneratorFactoryMock($demoDataGeneratorConfigMock);
        $demoDataGeneratorFacade = $this->createDemoDataGeneratorFacade($demoDataGeneratorFactoryMock);

        $productAbstractCsvDemoDataGeneratorPlugin = new ProductImageCsvGeneratorPlugin();
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
            ->setMethods(['getConfig', 'getStore', 'getStoreFacade'])
            ->getMock();

        $demoDataGeneratorFactoryMock
            ->method('getConfig')
            ->willReturn($demoDataGeneratorConfigMock);

        $demoDataGeneratorFactoryMock
            ->method('getStore')
            ->willReturn($demoDataGeneratorStoreMock);

        $demoDataGeneratorFactoryMock
            ->method('getStoreFacade')
            ->willReturn($this->tester->getLocator()->store()->facade());

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
            ->method('getLocales')
            ->willReturn([static::DEFAULT_LOCALE]);

        return $storeMock;
    }
}
