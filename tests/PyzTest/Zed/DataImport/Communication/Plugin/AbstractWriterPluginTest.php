<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Communication\Plugin;

use Codeception\Configuration;
use Codeception\Test\Unit;
use Codeception\Util\Stub;
use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Generated\Shared\Transfer\DataImporterReaderConfigurationTransfer;
use Propel\Runtime\Propel;
use Pyz\Zed\DataImport\Business\DataImportBusinessFactory;
use Pyz\Zed\DataImport\Business\Model\PropelMariaDbVersionConstraintException;
use Pyz\Zed\DataImport\Business\Model\PropelMariaDbVersionConstraintTrait;
use Pyz\Zed\DataImport\DataImportConfig;
use Spryker\Service\UtilEncoding\UtilEncodingService;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterCollection;
use Spryker\Zed\DataImport\Dependency\Propel\DataImportToPropelConnectionBridge;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceBridge;
use Spryker\Zed\PriceProduct\Business\PriceProductFacade;
use Spryker\Zed\Propel\PropelConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Communication
 * @group Plugin
 * @group AbstractWriterPluginTest
 * Add your own group annotations below this line
 */
abstract class AbstractWriterPluginTest extends Unit
{
    use PropelMariaDbVersionConstraintTrait;

    /**
     * @return array
     */
    abstract public function getDataImportWriterPlugins(): array;

    /**
     * @return \Generated\Shared\Transfer\DataImportConfigurationActionTransfer
     */
    abstract public function getDataImportConfigurationActionTransfer(): DataImportConfigurationActionTransfer;

    /**
     * @return \Pyz\Zed\DataImport\Business\DataImportBusinessFactory
     */
    protected function getDataImportBusinessFactoryStub()
    {
        /**
         * @var \Pyz\Zed\DataImport\Business\DataImportBusinessFactory
         */
        $dataImportBusinessFactory = Stub::make(DataImportBusinessFactory::class, [
            'createProductAbstractDataImportWriters' => $this->createDataImportWriters(),
            'createProductAbstractStoreDataImportWriters' => $this->createDataImportWriters(),
            'createProductPriceDataImportWriters' => $this->createDataImportWriters(),
            'createProductConcreteDataImportWriters' => $this->createDataImportWriters(),
            'createProductImageDataWriters' => $this->createDataImportWriters(),
            'createProductStockDataImportWriters' => $this->createDataImportWriters(),
            'getConfig' => $this->getDataImportConfigStub(),
            'getPropelConnection' => $this->getPropelConnection(),
            'getStore' => $this->getStore(),
            'getPriceProductFacade' => new PriceProductFacade(),
            'getUtilEncodingService' => new DataImportToUtilEncodingServiceBridge(
                new UtilEncodingService()
            ),
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
                $dataImportBusinessFactory->createPropelExecutor()
            );
        } catch (PropelMariaDbVersionConstraintException $exception) {
            $this->markTestSkipped('Importer does not support current database engine or it\'s version.');
        }
    }

    /**
     * @return \Pyz\Zed\DataImport\DataImportConfig
     */
    public function getDataImportConfigStub()
    {
        /** @var \Pyz\Zed\DataImport\DataImportConfig $dataImportConfig */
        $dataImportConfig = Stub::make(DataImportConfig::class, [
            'buildImporterConfigurationByDataImportConfigAction' => $this->getDataImporterConfiguration(),
        ]);

        return $dataImportConfig;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterCollection
     */
    public function createDataImportWriters(): DataSetWriterCollection
    {
        return new DataSetWriterCollection($this->getDataImportWriterPlugins());
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getDataImporterConfiguration(): DataImporterConfigurationTransfer
    {
        $dataImportConfigurationActionTransfer = $this->getDataImportConfigurationActionTransfer();

        $dataImporterReaderConfigurationTransfer = new DataImporterReaderConfigurationTransfer();
        $dataImporterReaderConfigurationTransfer->setFileName(Configuration::dataDir() . $dataImportConfigurationActionTransfer->getSource());

        $dataImportConfigurationTransfer = new DataImporterConfigurationTransfer();
        $dataImportConfigurationTransfer->setReaderConfiguration($dataImporterReaderConfigurationTransfer);
        $dataImportConfigurationTransfer->setImportType($dataImportConfigurationActionTransfer->getDataEntity());

        return $dataImportConfigurationTransfer;
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
}
