<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock\Writer;

use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface;
use Pyz\Zed\DataImport\Business\Model\ProductStock\ProductStockHydratorStep;
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\Availability\Dependency\AvailabilityEvents;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

class ProductStockPropelDataSetWriter implements DataSetWriterInterface
{
    /**
     * @var int[]
     */
    protected static $productAbstractSkus;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface
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
     * @var \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface $productBundleFacade
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface $productRepository
     * @param \Spryker\Zed\Store\Business\StoreFacadeInterface $storeFacade
     */
    public function __construct(
        AvailabilityFacadeInterface $availabilityFacade,
        ProductBundleFacadeInterface $productBundleFacade,
        ProductRepositoryInterface $productRepository,
        StoreFacadeInterface $storeFacade
    ) {
        $this->availabilityFacade = $availabilityFacade;
        $this->productBundleFacade = $productBundleFacade;
        $this->productRepository = $productRepository;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $stockEntity = $this->createOrUpdateStock($dataSet);
        $this->createOrUpdateProductStock($dataSet, $stockEntity);
        $this->collectProductAbstractSku($dataSet);

        $this->availabilityFacade->updateAvailability($dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU]);

        if ($dataSet[ProductStockHydratorStep::KEY_IS_BUNDLE]) {
            $this->productBundleFacade->updateBundleAvailability($dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU]);
        } else {
            $this->productBundleFacade->updateAffectedBundlesAvailability($dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU]);
            $this->productBundleFacade->updateAffectedBundlesStock($dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU]);
        }
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->triggerAvailabilityPublishEvents();
        $this->productRepository->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Stock\Persistence\SpyStock
     */
    protected function createOrUpdateStock(DataSetInterface $dataSet)
    {
        $stockTransfer = $dataSet[ProductStockHydratorStep::STOCK_ENTITY_TRANSFER];
        $stockEntity = SpyStockQuery::create()
            ->filterByName($stockTransfer->getName())
            ->findOneOrCreate();
        $stockEntity->fromArray($stockTransfer->modifiedToArray());
        $stockEntity->save();

        return $stockEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\Stock\Persistence\SpyStock $stockEntity
     *
     * @return void
     */
    protected function createOrUpdateProductStock(DataSetInterface $dataSet, SpyStock $stockEntity): void
    {
        $stockProductEntityTransfer = $dataSet[ProductStockHydratorStep::STOCK_PRODUCT_ENTITY_TRANSFER];
        $idProduct = $this->productRepository->getIdProductByConcreteSku($dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU]);
        $stockProductEntity = SpyStockProductQuery::create()
            ->filterByFkProduct($idProduct)
            ->filterByFkStock($stockEntity->getIdStock())
            ->findOneOrCreate();
        $stockProductEntity->fromArray($stockProductEntityTransfer->modifiedToArray());
        $stockProductEntity->save();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductAbstractSku(DataSetInterface $dataSet): void
    {
        $productConcreteSku = $dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU];
        static::$productAbstractSkus[] = $this->productRepository->getAbstractSkuByConcreteSku($productConcreteSku);
    }

    /**
     * @return void
     */
    protected function triggerAvailabilityPublishEvents(): void
    {
        $availabilityAbstractIds = $this->getAvailabilityAbstractIdsByProductAbstractSkus();

        foreach ($availabilityAbstractIds as $availabilityAbstractId) {
            DataImporterPublisher::addEvent(AvailabilityEvents::AVAILABILITY_ABSTRACT_PUBLISH, $availabilityAbstractId);
        }
    }

    /**
     * @return int[]
     */
    protected function getAvailabilityAbstractIdsByProductAbstractSkus(): array
    {
        $storeIds = $this->getStoreIds();

        return SpyAvailabilityAbstractQuery::create()
            ->joinWithSpyAvailability()
            ->useSpyAvailabilityQuery()
                ->filterByFkStore_In($storeIds)
            ->endUse()
            ->filterByAbstractSku_In(static::$productAbstractSkus)
            ->select([
                SpyAvailabilityAbstractTableMap::COL_ID_AVAILABILITY_ABSTRACT,
            ])
            ->find()
            ->getData();
    }

    /**
     * @return int[]
     */
    protected function getStoreIds(): array
    {
        $storeTransfer = $this->storeFacade->getCurrentStore();
        $storeIds = [$storeTransfer->getIdStore()];

        foreach ($storeTransfer->getStoresWithSharedPersistence() as $storeName) {
            $storeTransfer = $this->storeFacade->getStoreByName($storeName);
            $storeIds[] = $storeTransfer->getIdStore();
        }

        return $storeIds;
    }
}
