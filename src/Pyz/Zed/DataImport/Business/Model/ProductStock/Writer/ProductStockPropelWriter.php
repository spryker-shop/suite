<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock\Writer;

use Orm\Zed\Stock\Persistence\SpyStock;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository;
use Pyz\Zed\DataImport\Business\Model\ProductStock\ProductStockHydratorStep;
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Business\Model\Writer\FlushInterface;
use Spryker\Zed\DataImport\Business\Model\Writer\WriterInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface;

class ProductStockPropelWriter extends DataImporterPublisher implements WriterInterface, FlushInterface
{
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
     * ProductStockPropelWriter constructor.
     *
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface $productBundleFacade
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository $productRepository
     */
    public function __construct(
        DataImportToEventFacadeInterface $eventFacade,
        AvailabilityFacadeInterface $availabilityFacade,
        ProductBundleFacadeInterface $productBundleFacade,
        ProductRepository $productRepository
    ) {
        parent::__construct($eventFacade);
        $this->availabilityFacade = $availabilityFacade;
        $this->productBundleFacade = $productBundleFacade;
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet)
    {
        $stockEntity = $this->createOrUpdateStock($dataSet);
        $this->createOrUpdateProductStock($dataSet, $stockEntity);

        $this->availabilityFacade->updateAvailability($dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU]);

        if ($dataSet[ProductStockHydratorStep::KEY_IS_BUNDLE]) {
            $this->productBundleFacade->updateBundleAvailability($dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU]);
            $this->productBundleFacade->updateAffectedBundlesAvailability($dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU]);
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->triggerEvents();
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
    protected function createOrUpdateProductStock(DataSetInterface $dataSet, SpyStock $stockEntity)
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
