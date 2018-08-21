<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DemoDataGenerator\Communication\Plugin;

use PHPUnit\Framework\MockObject\MockObject;
use Pyz\Zed\DemoDataGenerator\Business\DemoDataGeneratorBusinessFactory;
use Pyz\Zed\DemoDataGenerator\Communication\Plugin\ProductPriceCsvGeneratorPlugin;
use Spryker\Shared\Kernel\Store;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DemoDataGenerator
 * @group Communication
 * @group Plugin
 * @group PriceProductCsvGeneratorPluginTest
 * Add your own group annotations below this line
 */
class PriceProductCsvGeneratorPluginTest extends AbstractCsvGenerator
{
    protected const DEMO_DATA_TYPE = 'price_product';
    protected const TEST_DEMO_DATA = 'price_product.csv';
    protected const DEFAULT_STORE = 'DE';
    protected const DEFAULT_CURRENCY = 'CHF';

    /**
     * @return void
     */
    public function testPriceProductCsvDemoDataGenerator(): void
    {
        $demoDataGeneratorTransfer = $this->createDemoDataGeneratorTransfer(static::DEMO_DATA_TYPE);
        $demoDataGeneratorConfigMock = $this->createDemoDataConfigMock('getProductPriceCsvPath', static::TEST_DEMO_DATA);
        $demoDataGeneratorFactoryMock = $this->createDemoDataGeneratorFactoryMock($demoDataGeneratorConfigMock);
        $demoDataGeneratorFacade = $this->createDemoDataGeneratorFacade($demoDataGeneratorFactoryMock);

        $productAbstractCsvDemoDataGeneratorPlugin = new ProductPriceCsvGeneratorPlugin();
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
            ->method('getDefaultCurrencyCode')
            ->willReturn(static::DEFAULT_CURRENCY);

        $storeMock
            ->method('getCurrentCountry')
            ->willReturn(static::DEFAULT_STORE);

        return $storeMock;
    }
}
