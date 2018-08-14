<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock\Writer;

use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataFormatter;
use Pyz\Zed\DataImport\Business\Model\ProductStock\ProductStockHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface;
use Spryker\Zed\Stock\Business\StockFacadeInterface;

class ProductStockBulkPdoDataSetWriter extends DataImporterPublisher implements DataSetWriterInterface
{
    use DataFormatter;

    const BULK_SIZE = 2000;

    protected static $stockCollection = [];

    protected static $stockProductCollection = [];

    protected static $storeToStock = [];

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    protected $stockFacade;

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
     * @param \Spryker\Zed\Stock\Business\StockFacadeInterface $stockFacade
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface $productBundleFacade
     * @param \Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSqlInterface $productStockSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     */
    public function __construct(
        DataImportToEventFacadeInterface $eventFacade,
        StockFacadeInterface $stockFacade,
        ProductBundleFacadeInterface $productBundleFacade,
        ProductStockSqlInterface $productStockSql,
        PropelExecutorInterface $propelExecutor
    ) {
        parent::__construct($eventFacade);
        $this->stockFacade = $stockFacade;
        $this->productBundleFacade = $productBundleFacade;
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
        $this->persistAvailabilityProductEntities();

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
        $sku = $this->formatPostgresArrayString(
            $this->getCollectionDataByKey(static::$stockProductCollection, ProductStockHydratorStep::KEY_CONCRETE_SKU)
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
            $sku,
            $stockName,
            $quantity,
            $isNeverOutOfStock,
        ];
        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @return void
     */
    protected function persistAvailabilityProductEntities(): void
    {
        $skus = $this->formatPostgresArrayString(
            $this->getCollectionDataByKey(static::$stockProductCollection, ProductStockHydratorStep::KEY_CONCRETE_SKU)
        );

        $sql = $this->productStockSql->createAvailabilityProductSQL();
        $storeToStock = $this->getStoreToWarehouse();
        foreach ($storeToStock as $store => $stocks) {
            $stores = array_fill(0, count(static::$stockProductCollection), $store);
            $parameters = [
                $skus,
                $this->formatPostgresArrayString($stores),
                $this->formatPostgresArrayString($stocks),
            ];
            $this->propelExecutor->execute($sql, $parameters);
        }

        foreach (static::$stockProductCollection as $stockProduct) {
            if ($stockProduct[ProductStockHydratorStep::KEY_IS_BUNDLE]) {
                $this->productBundleFacade->updateBundleAvailability($stockProduct[ProductStockHydratorStep::KEY_CONCRETE_SKU]);
                $this->productBundleFacade->updateAffectedBundlesAvailability($stockProduct[ProductStockHydratorStep::KEY_CONCRETE_SKU]);
            }
        }
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
        $productStockArray = $dataSet[ProductStockHydratorStep::STOCK_PRODUCT_ENTITY_TRANSFER]->modifiedToArray();
        $productStockArray[ProductStockHydratorStep::KEY_IS_BUNDLE] = $dataSet[ProductStockHydratorStep::KEY_IS_BUNDLE];
        $productStockArray[ProductStockHydratorStep::KEY_CONCRETE_SKU] = $dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU];

        static::$stockProductCollection[] = $productStockArray;
    }

    /**
     * @return array
     */
    protected function getStoreToWarehouse()
    {
        if (empty(static::$storeToStock)) {
            $storeToWarehouse = [];
            $warehouseToStore = $this->stockFacade->getWarehouseToStoreMapping();
            foreach ($warehouseToStore as $warehouse => $stores) {
                foreach ($stores as $store) {
                    $storeToWarehouse[$store][$warehouse] = $warehouse;
                }
            }
            static::$storeToStock = $storeToWarehouse;
        }

        return static::$storeToStock;
    }
}
