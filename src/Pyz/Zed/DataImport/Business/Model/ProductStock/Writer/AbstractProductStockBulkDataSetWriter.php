<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock\Writer;

use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;
use Orm\Zed\Oms\Persistence\Map\SpyOmsProductReservationTableMap;
use Orm\Zed\Oms\Persistence\SpyOmsProductReservationQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\ProductStock\ProductStockHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Reader\ProductStockReaderInterface;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\DecimalObject\Decimal;
use Spryker\Zed\Availability\Dependency\AvailabilityEvents;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\DataImportConfig;
use Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface;
use Spryker\Zed\Stock\Business\StockFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

abstract class AbstractProductStockBulkDataSetWriter implements DataSetWriterInterface
{
    /**
     * @var int
     */
    public const BULK_SIZE = 2000;

    protected const COLUMN_NAME = ProductStockHydratorStep::COLUMN_NAME;
    protected const COLUMN_CONCRETE_SKU = ProductStockHydratorStep::COLUMN_CONCRETE_SKU;
    protected const COLUMN_IS_BUNDLE = ProductStockHydratorStep::COLUMN_IS_BUNDLE;
    protected const COLUMN_QUANTITY = ProductStockHydratorStep::COLUMN_QUANTITY;
    protected const COLUMN_IS_NEVER_OUT_OF_STOCK = ProductStockHydratorStep::COLUMN_IS_NEVER_OUT_OF_STOCK;

    /**
     * @var string
     */
    protected const KEY_SKU = 'sku';

    /**
     * @var string
     */
    protected const KEY_QUANTITY = 'qty';

    /**
     * @var string
     */
    protected const KEY_IS_NEVER_OUT_OF_STOCK = 'is_never_out_of_stock';

    /**
     * @var array
     */
    protected static $stockCollection = [];

    /**
     * @var array
     */
    protected static $stockProductCollection = [];

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
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface
     */
    protected $dataFormatter;

    /**
     * @var array<int>
     */
    protected $availabilityAbstractIds = [];

    /**
     * @var \Spryker\Zed\DataImport\DataImportConfig
     */
    protected $dataImportConfig;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\ProductStock\Reader\ProductStockReaderInterface
     */
    protected $productStockReader;

    /**
     * @param \Spryker\Zed\Stock\Business\StockFacadeInterface $stockFacade
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface $productBundleFacade
     * @param \Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSqlInterface $productStockSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     * @param \Spryker\Zed\DataImport\DataImportConfig $dataImportConfig
     * @param \Pyz\Zed\DataImport\Business\Model\ProductStock\Reader\ProductStockReaderInterface $productStockReader
     */
    public function __construct(
        StockFacadeInterface $stockFacade,
        ProductBundleFacadeInterface $productBundleFacade,
        ProductStockSqlInterface $productStockSql,
        PropelExecutorInterface $propelExecutor,
        StoreFacadeInterface $storeFacade,
        DataImportDataFormatterInterface $dataFormatter,
        DataImportConfig $dataImportConfig,
        ProductStockReaderInterface $productStockReader
    ) {
        $this->stockFacade = $stockFacade;
        $this->productBundleFacade = $productBundleFacade;
        $this->productStockSql = $productStockSql;
        $this->propelExecutor = $propelExecutor;
        $this->storeFacade = $storeFacade;
        $this->dataFormatter = $dataFormatter;
        $this->dataImportConfig = $dataImportConfig;
        $this->productStockReader = $productStockReader;
    }

    /**
     * @return void
     */
    abstract protected function persistStockEntities(): void;

    /**
     * @return void
     */
    abstract protected function persistStockProductEntities(): void;

    /**
     * @return void
     */
    abstract protected function persistAvailability(): void;

    /**
     * @param array $skus
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array $concreteSkusToAbstractMap
     * @param array $reservationItems
     *
     * @return void
     */
    abstract protected function updateAvailability(
        array $skus,
        StoreTransfer $storeTransfer,
        array $concreteSkusToAbstractMap,
        array $reservationItems
    ): void;

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
        $this->triggerAvailabilityPublishEvents();
    }

    /**
     * @return void
     */
    protected function writeEntities(): void
    {
        $this->persistStockEntities();
        $this->persistStockProductEntities();
        $this->persistAvailability();

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
     * @param array $skus
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    protected function getStockProductBySkusAndStore(array $skus, StoreTransfer $storeTransfer): array
    {
        $stockProducts = SpyStockProductQuery::create()
            ->useSpyProductQuery()
            ->filterBySku_In($skus)
            ->endUse()
            ->leftJoinWithStock()
            ->select([
                SpyProductTableMap::COL_SKU,
                SpyStockProductTableMap::COL_QUANTITY,
                SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK,
                SpyStockTableMap::COL_NAME,
            ])
            ->find()
            ->toArray();

        return $this->mapStockProducts($stockProducts, $storeTransfer);
    }

    /**
     * @param array $stockProducts
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return array
     */
    protected function mapStockProducts(array $stockProducts, StoreTransfer $storeTransfer): array
    {
        $stocks = $this->stockFacade->getStoreToWarehouseMapping();
        $result = [];
        foreach ($stockProducts as $stockProduct) {
            $sku = $stockProduct[SpyProductTableMap::COL_SKU];
            $result[$sku][static::KEY_SKU] = $sku;
            $result[$sku][static::KEY_IS_NEVER_OUT_OF_STOCK] = (bool)$stockProduct[SpyStockProductTableMap::COL_IS_NEVER_OUT_OF_STOCK];

            $quantity = '0';
            if (in_array($stockProduct[SpyStockTableMap::COL_NAME], $stocks[$storeTransfer->getName()])) {
                $quantity = $stockProduct[SpyStockProductTableMap::COL_QUANTITY];
            }
            $result[$sku][static::KEY_QUANTITY] = $quantity;
        }

        return $result;
    }

    /**
     * @param array<string> $skus
     *
     * @return array<\Spryker\DecimalObject\Decimal>
     */
    protected function getReservationsBySkus(array $skus): array
    {
        $reservations = SpyOmsProductReservationQuery::create()
            ->filterBySku_In($skus)
            ->select([
                SpyOmsProductReservationTableMap::COL_SKU,
                SpyOmsProductReservationTableMap::COL_RESERVATION_QUANTITY,
            ])
            ->find()
            ->toArray();
        $result = [];
        foreach ($reservations as $reservation) {
            $result[$reservation[SpyOmsProductReservationTableMap::COL_SKU]] = (new Decimal($result[$reservation[SpyOmsProductReservationTableMap::COL_SKU]] ?? '0'))->add($reservation[SpyOmsProductReservationTableMap::COL_RESERVATION_QUANTITY]);
        }

        return $result;
    }

    /**
     * @param array $skus
     *
     * @return array
     */
    protected function mapConcreteSkuToAbstractSku(array $skus): array
    {
        $abstractProducts = SpyProductAbstractQuery::create()
            ->useSpyProductQuery()
            ->filterBySku_In($skus)
            ->endUse()
            ->select([SpyProductTableMap::COL_SKU, SpyProductAbstractTableMap::COL_SKU])
            ->find()
            ->toArray();

        return array_combine(
            array_column($abstractProducts, SpyProductTableMap::COL_SKU),
            array_column($abstractProducts, SpyProductAbstractTableMap::COL_SKU),
        );
    }

    /**
     * @param array $stockProducts
     * @param array<\Spryker\DecimalObject\Decimal> $reservations
     *
     * @return array
     */
    protected function prepareConcreteAvailabilityData(array $stockProducts, array $reservations): array
    {
        foreach ($stockProducts as $stock) {
            $sku = $stock[static::KEY_SKU];
            $quantity = (new Decimal($stock[static::KEY_QUANTITY]))->subtract($reservations[$sku] ?? 0);
            $stockProducts[$sku][static::KEY_QUANTITY] = $quantity->greatherThanOrEquals(0) ? $quantity : new Decimal(0);
        }

        return $stockProducts;
    }

    /**
     * @param array $concreteAvailabilityData
     * @param array<string> $concreteSkusToAbstractMap
     *
     * @return array
     */
    protected function prepareAbstractAvailabilityData(
        array $concreteAvailabilityData,
        array $concreteSkusToAbstractMap
    ): array {
        $abstractAvailabilityData = [];
        foreach ($concreteAvailabilityData as $concreteAvailability) {
            $abstractSku = $concreteSkusToAbstractMap[$concreteAvailability[static::KEY_SKU]] ?? null;
            if (!$abstractSku) {
                continue;
            }
            $abstractAvailabilityData[$abstractSku][static::KEY_SKU] = $abstractSku;
            $abstractAvailabilityData[$abstractSku][static::KEY_QUANTITY] = (new Decimal($abstractAvailabilityData[$abstractSku][static::KEY_QUANTITY] ?? 0))->add($concreteAvailability[static::KEY_QUANTITY]);
        }

        return $abstractAvailabilityData;
    }

    /**
     * @return void
     */
    protected function updateBundleAvailability(): void
    {
        foreach (static::$stockProductCollection as $stockProduct) {
            if (!$stockProduct[static::COLUMN_IS_BUNDLE]) {
                continue;
            }
            $this->productBundleFacade->updateBundleAvailability($stockProduct[static::COLUMN_CONCRETE_SKU]);
            $this->productBundleFacade->updateAffectedBundlesAvailability($stockProduct[static::COLUMN_CONCRETE_SKU]);
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
        $productStockArray[static::COLUMN_IS_BUNDLE] = $dataSet[static::COLUMN_IS_BUNDLE];
        $productStockArray[static::COLUMN_CONCRETE_SKU] = $dataSet[static::COLUMN_CONCRETE_SKU];

        static::$stockProductCollection[] = $productStockArray;
    }

    /**
     * @param array<int> $availabilityAbstractIds
     *
     * @return void
     */
    protected function collectAvailabilityAbstractIds(array $availabilityAbstractIds): void
    {
        $availabilityAbstractIds = array_merge(
            $this->availabilityAbstractIds,
            array_column($availabilityAbstractIds, SpyAvailabilityAbstractTableMap::COL_ID_AVAILABILITY_ABSTRACT),
        );

        $this->availabilityAbstractIds = array_unique($availabilityAbstractIds);
    }

    /**
     * @return void
     */
    protected function triggerAvailabilityPublishEvents(): void
    {
        $productAbstractIds = $this->productStockReader->getProductAbstractIdsByAvailabilityAbstractIds($this->availabilityAbstractIds);

        foreach ($productAbstractIds as $productAbstractId) {
            DataImporterPublisher::addEvent(AvailabilityEvents::AVAILABILITY_PRODUCT_ABSTRACT_PUBLISH, $productAbstractId);
        }
    }
}
