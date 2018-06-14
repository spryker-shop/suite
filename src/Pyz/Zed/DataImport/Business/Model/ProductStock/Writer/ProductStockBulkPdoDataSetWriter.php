<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock\Writer;

use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataFormatter;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface;
use Pyz\Zed\DataImport\Business\Model\ProductStock\ProductStockHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface;

class ProductStockBulkPdoDataSetWriter extends DataImporterPublisher implements DataSetWriterInterface
{
    use DataFormatter;

    const BULK_SIZE = 5000;

    protected static $stockCollection = [];

    protected static $stockProductCollection = [];

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface
     */
    protected $productBundleFacade;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSqlInterface
     */
    protected $productStockSql;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    protected $propelExecutor;

    /**
     * ProductStockBulkPdoWriter constructor.
     *
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface $productBundleFacade
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface $productRepository
     * @param \Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSqlInterface $productStockSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     */
    public function __construct(
        DataImportToEventFacadeInterface $eventFacade,
        AvailabilityFacadeInterface $availabilityFacade,
        ProductBundleFacadeInterface $productBundleFacade,
        ProductRepositoryInterface $productRepository,
        ProductStockSqlInterface $productStockSql,
        PropelExecutorInterface $propelExecutor
    ) {
        parent::__construct($eventFacade);
        $this->availabilityFacade = $availabilityFacade;
        $this->productBundleFacade = $productBundleFacade;
        $this->productRepository = $productRepository;
        $this->productStockSql = $productStockSql;
        $this->propelExecutor = $propelExecutor;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $this->collectStock($dataSet);
        $this->collectStockProduct($dataSet);

        if (count(static::$stockProductCollection) >= static::BULK_SIZE) {
            $this->writeEntities();
            $this->availabilityFacade->updateAvailability($dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU]);

            if ($dataSet[ProductStockHydratorStep::KEY_IS_BUNDLE]) {
                $this->productBundleFacade->updateBundleAvailability($dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU]);
                $this->productBundleFacade->updateAffectedBundlesAvailability($dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU]);
            }
        }
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->writeEntities();
    }

    /**
     * @return void
     */
    protected function writeEntities(): void
    {
        $this->persistStockEntities();
        $this->persistStockProductEntities();
        $this->triggerEvents();
        $this->flushMemory();
    }

    /**
     * @return void
     */
    protected function flushMemory(): void
    {
        static::$stockCollection = [];
        static::$stockProductCollection = [];
    }

    /**
     * @return void
     */
    protected function persistStockEntities(): void
    {
        $names = $this->getCollectionDataByKey(static::$stockCollection, ProductStockHydratorStep::KEY_NAME);
        $uniqueNames = array_unique($names);
        $name = $this->formatPostgresArrayString($uniqueNames);

        $sql = $this->productStockSql->createStockSQL();
        $parameters = [
            $name,
        ];
        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @return void
     */
    protected function persistStockProductEntities(): void
    {
        $fkProduct = $this->formatPostgresArray(
            $this->getCollectionDataByKey(static::$stockProductCollection, ProductStockHydratorStep::KEY_FK_PRODUCT)
        );
        $stockName = $this->formatPostgresArray(
            $this->getCollectionDataByKey(static::$stockCollection, ProductStockHydratorStep::KEY_NAME)
        );
        $quantity = $this->formatPostgresArray(
            $this->getCollectionDataByKey(static::$stockProductCollection, ProductStockHydratorStep::KEY_QUANTITY)
        );
        $isNeverOutOfStock = $this->formatPostgresArray(
            $this->getCollectionDataByKey(static::$stockProductCollection, ProductStockHydratorStep::KEY_IS_NEVER_OUT_OF_STOCK)
        );

        $sql = $this->productStockSql->createStockProductSQL();
        $parameters = [
            $fkProduct,
            $stockName,
            $quantity,
            $isNeverOutOfStock,
        ];
        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectStock(DataSetInterface $dataSet): void
    {
        static::$stockCollection[] = $dataSet[ProductStockHydratorStep::STOCK_ENTITY_TRANSFER]->modifiedToArray();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectStockProduct(DataSetInterface $dataSet): void
    {
        $sku = $dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU];
        $idProduct = $this->productRepository->getIdProductByConcreteSku($sku);
        $dataSet[ProductStockHydratorStep::STOCK_PRODUCT_ENTITY_TRANSFER]->setFkProduct($idProduct);
        static::$stockProductCollection[] = $dataSet[ProductStockHydratorStep::STOCK_PRODUCT_ENTITY_TRANSFER]->modifiedToArray();
    }
}
