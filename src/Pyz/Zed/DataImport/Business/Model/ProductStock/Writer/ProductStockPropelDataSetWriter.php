<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock\Writer;

use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface;
use Pyz\Zed\DataImport\Business\Model\ProductStock\ProductStockHydratorStep;
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface;

class ProductStockPropelDataSetWriter implements DataSetWriterInterface
{
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
     * @param \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface $productBundleFacade
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        AvailabilityFacadeInterface $availabilityFacade,
        ProductBundleFacadeInterface $productBundleFacade,
        ProductRepositoryInterface $productRepository
    ) {
        $this->availabilityFacade = $availabilityFacade;
        $this->productBundleFacade = $productBundleFacade;
        $this->productRepository = $productRepository;
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
}
