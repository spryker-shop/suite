<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model;

use Codeception\Stub;
use Codeception\Test\Unit;
use Propel\Runtime\Propel;
use Pyz\Zed\DataImport\Business\DataImportBusinessFactory;
use Pyz\Zed\DataImport\Business\Model\PropelMariaDbVersionConstraintException;
use Pyz\Zed\DataImport\Business\Model\PropelMariaDbVersionConstraintTrait;
use Pyz\Zed\DataImport\DataImportConfig;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Zed\Availability\Business\AvailabilityFacade;
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\Currency\Business\CurrencyFacade;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionBridge;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceBridge;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;
use Spryker\Zed\PriceProduct\Business\PriceProductFacade;
use Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundleFacade;
use Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface;
use Spryker\Zed\Propel\PropelConfig;
use Spryker\Zed\Stock\Business\StockFacade;
use Spryker\Zed\Stock\Business\StockFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacade;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

/**
 * Auto-generated group annotations
 *
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
    use PropelMariaDbVersionConstraintTrait;

    /**
     * @return \Pyz\Zed\DataImport\Business\DataImportBusinessFactory
     */
    protected function getDataImportBusinessFactoryStub(): DataImportBusinessFactory
    {
        /** @var \Pyz\Zed\DataImport\Business\DataImportBusinessFactory $dataImportBusinessFactory */
        $dataImportBusinessFactory = Stub::make(DataImportBusinessFactory::class, [
            'getPropelConnection' => $this->getPropelConnection(),
            'getStoreFacade' => $this->getStoreFacade(),
            'getCurrencyFacade' => $this->getCurrencyFacade(),
            'getStockFacade' => $this->getStockFacade(),
            'getProductBundleFacade' => $this->getProductBundleFacade(),
            'getAvailabilityFacade' => $this->getAvailabilityFacade(),
            'getPriceProductFacade' => $this->getPriceProductFacade(),
            'getUtilEncodingService' => $this->getUtilEncodingService(),
            'getConfig' => $this->getDataImportConfigStub(),
        ]);

        return $dataImportBusinessFactory;
    }

    /**
     * @return void
     */
    protected function markTestSkippedOnDatabaseConstraintsMismatch(): void
    {
        $dataImportBusinessFactory = $this->getDataImportBusinessFactoryStub();

        if ($dataImportBusinessFactory->getConfig()->getCurrentDatabaseEngine() === PropelConfig::DB_ENGINE_PGSQL) {
            return;
        }

        try {
            $this->checkIsMariaDBSupportsBulkImport(
                $dataImportBusinessFactory->createPropelExecutor(),
            );
        } catch (PropelMariaDbVersionConstraintException $exception) {
            $this->markTestSkipped('Importer does not support current database engine or it\'s version.');
        }
    }

    /**
     * @return \Pyz\Zed\DataImport\DataImportConfig
     */
    public function getDataImportConfigStub(): DataImportConfig
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

    /**
     * @return \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected function getPriceProductFacade(): PriceProductFacadeInterface
    {
        return new PriceProductFacade();
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): DataImportToUtilEncodingServiceInterface
    {
        return new DataImportToUtilEncodingServiceBridge(
            new UtilEncodingService(),
        );
    }
}
