<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model;

use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Propel\Runtime\Propel;
use Pyz\Zed\DataImport\Business\DataImportBusinessFactory;
use Pyz\Zed\DataImport\DataImportConfig;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Availability\Business\AvailabilityFacade;
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\Currency\Business\CurrencyFacade;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionBridge;
use Spryker\Zed\ProductBundle\Business\ProductBundleFacade;
use Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface;
use Spryker\Zed\Stock\Business\StockFacade;
use Spryker\Zed\Stock\Business\StockFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacade;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group AbstractWriterTest
 * Add your own group annotations below this line
 */
abstract class AbstractWriterTest extends Unit
{
    /**
     * @return \Pyz\Zed\DataImport\Business\DataImportBusinessFactory
     */
    protected function getDataImportBusinessFactoryStub()
    {
        /** @var \Pyz\Zed\DataImport\Business\DataImportBusinessFactory $dataImportBusinessFactory */
        $dataImportBusinessFactory = Stub::make(DataImportBusinessFactory::class, [
            'getPropelConnection' => $this->getPropelConnection(),
            'getStore' => $this->getStore(),
            'getStoreFacade' => $this->getStoreFacade(),
            'getCurrencyFacade' => $this->getCurrencyFacade(),
            'getStockFacade' => $this->getStockFacade(),
            'getProductBundleFacade' => $this->getProductBundleFacade(),
            'getAvailabilityFacade' => $this->getAvailabilityFacade(),
        ]);

        return $dataImportBusinessFactory;
    }

    /**
     * @return \Pyz\Zed\DataImport\DataImportConfig
     */
    public function getDataImportConfigStub()
    {
        /** @var \Pyz\Zed\DataImport\DataImportConfig $dataImportConfig */
        $dataImportConfig = Stub::make(DataImportConfig::class);

        return $dataImportConfig;
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionBridge
     */
    public function getPropelConnection(): DataImportToPropelConnectionBridge
    {
        return new DataImportToPropelConnectionBridge(Propel::getConnection());
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return Store::getInstance();
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return new StoreFacade();
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected function getCurrencyFacade(): CurrencyFacadeInterface
    {
        return new CurrencyFacade();
    }

    /**
     * @return \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    protected function getStockFacade(): StockFacadeInterface
    {
        return new StockFacade();
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface
     */
    protected function getProductBundleFacade(): ProductBundleFacadeInterface
    {
        return new ProductBundleFacade();
    }

    /**
     * @return \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    protected function getAvailabilityFacade(): AvailabilityFacadeInterface
    {
        return new AvailabilityFacade();
    }
}
