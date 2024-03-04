<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Communication\Plugin\ProductAbstractStore;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterReportTransfer;
use Pyz\Zed\DataImport\Communication\Plugin\ProductAbstractStore\ProductAbstractStorePropelWriterPlugin;
use PyzTest\Zed\DataImport\Communication\Plugin\AbstractWriterPluginTest;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Communication
 * @group Plugin
 * @group ProductAbstractStore
 * @group ProductAbstractStorePropelWriterPluginTest
 * Add your own group annotations below this line
 */
class ProductAbstractStorePropelWriterPluginTest extends AbstractWriterPluginTest
{
    use LocatorHelperTrait;

    /**
     * @var string
     */
    public const CSV_IMPORT_FILE = 'import/ProductAbstractStore/product_abstract_store.csv';

    /**
     * @var string
     */
    public const DATA_IMPORTER_TYPE = 'product-abstract-store';

    /**
     * @return void
     */
    public function testProductAbstractStoreBulkPdoWriterPlugin(): void
    {
        foreach ($this->testSkus as $sku) {
            if (!$this->getProductFacade()->findProductAbstractIdBySku($sku)) {
                $this->tester->haveProductAbstract(['sku' => $sku]);
            }
        }

        $dataImportBusinessFactory = $this->getDataImportBusinessFactoryStub();
        $dataImport = $dataImportBusinessFactory->createProductAbstractStoreImporter($this->getDataImportConfigurationActionTransfer());
        $dataImporterReportTransfer = $dataImport->import();
        $this->assertInstanceOf(DataImporterReportTransfer::class, $dataImporterReportTransfer);
    }

    /**
     * @return array
     */
    public function getDataImportWriterPlugins(): array
    {
        return [
            new ProductAbstractStorePropelWriterPlugin(),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\DataImportConfigurationActionTransfer
     */
    public function getDataImportConfigurationActionTransfer(): DataImportConfigurationActionTransfer
    {
        return (new DataImportConfigurationActionTransfer())
            ->setDataEntity(static::DATA_IMPORTER_TYPE)
            ->setSource(static::CSV_IMPORT_FILE);
    }

    /**
     * @return \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    private function getProductFacade(): ProductFacadeInterface
    {
        return $this->getLocator()->product()->facade();
    }
}
