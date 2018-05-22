<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer;

use Generated\Shared\Transfer\SpyProductSearchEntityTransfer;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteHydratorStep;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductBundle\Persistence\SpyProductBundleQuery;
use Orm\Zed\ProductSearch\Persistence\SpyProductSearchQuery;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Business\Model\Writer\FlushInterface;
use Spryker\Zed\DataImport\Business\Model\Writer\WriterInterface;

class ProductConcretePropelWriter extends DataImporterPublisher implements WriterInterface, FlushInterface
{
    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * ProductConcretePropelWriter constructor.
     *
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface $productRepository
     */
    public function __construct(
        DataImportToEventFacadeInterface $eventFacade,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct($eventFacade);
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $productConcreteEntity = $this->createOrUpdateProductConcrete($dataSet);

        $this->productRepository->addProductConcrete(
            $productConcreteEntity,
            $dataSet[ProductConcreteHydratorStep::KEY_ABSTRACT_SKU]
        );

        $this->createOrUpdateProductConcreteLocalizedAttributesEntities($dataSet, $productConcreteEntity->getIdProduct());
        $this->createOrUpdateBundles($dataSet, $productConcreteEntity->getIdProduct());
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
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     */
    protected function createOrUpdateProductConcrete(DataSetInterface $dataSet)
    {
        $productConcreteEntityTransfer = $this->getProductConcreteTransfer($dataSet);

        $productConcreteEntity = SpyProductQuery::create()
            ->filterBySku($productConcreteEntityTransfer->getSku())
            ->findOneOrCreate();
        $productConcreteEntity->fromArray($productConcreteEntityTransfer->modifiedToArray());

        if ($productConcreteEntity->isNew() || $productConcreteEntity->isModified()) {
            $productConcreteEntity->save();
            $this->addEvent(ProductEvents::PRODUCT_CONCRETE_PUBLISH, $productConcreteEntity->getIdProduct());
        }

        return $productConcreteEntity;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param $idProduct
     *
     * @return void
     */
    protected function createOrUpdateBundles(DataSetInterface $dataSet, $idProduct)
    {
        $productBundleTransfers = $this->getProductConcreteBundleTransfers($dataSet);

        foreach ($productBundleTransfers as $productBundleTransfer) {
            $productBundleEntity = SpyProductBundleQuery::create()
                ->filterByFkProduct($idProduct)
                ->filterByFkBundledProduct($productBundleTransfer->getFkBundledProduct())
                ->findOneOrCreate();
            $productBundleEntity->fromArray($productBundleTransfer->modifiedToArray());

            if ($productBundleEntity->isNew() || $productBundleEntity->isModified()) {
                $productBundleEntity->save();
            }
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param int $idProduct
     *
     * @return void
     */
    protected function createOrUpdateProductConcreteLocalizedAttributesEntities(DataSetInterface $dataSet, int $idProduct): void
    {
        $productConcreteLocalizedTransfers = $this->getProductConcreteLocalizedTransfers($dataSet);

        foreach ($productConcreteLocalizedTransfers as $productConcreteLocalizedArray) {
            $productConcreteLocalizedTransfer = $productConcreteLocalizedArray[ProductConcreteHydratorStep::KEY_PRODUCT_CONCRETE_LOCALIZED_TRANSFER];
            $productSearchEntityTransfer = $productConcreteLocalizedArray[ProductConcreteHydratorStep::KEY_PRODUCT_SEARCH_TRANSFER];

            $productConcreteLocalizedAttributesEntity = SpyProductLocalizedAttributesQuery::create()
                ->filterByFkProduct($idProduct)
                ->filterByFkLocale($productConcreteLocalizedTransfer->getFkLocale())
                ->findOneOrCreate();
            $productConcreteLocalizedAttributesEntity->fromArray($productConcreteLocalizedTransfer->modifiedToArray());

            if ($productConcreteLocalizedAttributesEntity->isNew() || $productConcreteLocalizedAttributesEntity->isModified()) {
                $productConcreteLocalizedAttributesEntity->save();
            }

            $this->createOrUpdateProductConcreteSearchEntities($idProduct, $productSearchEntityTransfer);
        }
    }

    /**
     * @param int $idProduct
     * @param \Generated\Shared\Transfer\SpyProductSearchEntityTransfer $productSearchEntityTransfer
     *
     * @return void
     */
    protected function createOrUpdateProductConcreteSearchEntities(
        int $idProduct,
        SpyProductSearchEntityTransfer $productSearchEntityTransfer
    ) {
        $productSearchEntity = SpyProductSearchQuery::create()
            ->filterByFkProduct($idProduct)
            ->filterByFkLocale($productSearchEntityTransfer->getFkLocale())
            ->findOneOrCreate();
        $productSearchEntity->fromArray($productSearchEntityTransfer->modifiedToArray());

        if ($productSearchEntity->isNew() || $productSearchEntity->isModified()) {
            $productSearchEntity->save();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array
     */
    protected function getProductConcreteBundleTransfers(DataSetInterface $dataSet)
    {
        return $dataSet
            [ProductConcreteHydratorStep::PRODUCT_BUNDLE_TRANSFER]
            [ProductConcreteHydratorStep::KEY_PRODUCT_BUNDLE_TRANSFER] ?? [];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array
     */
    protected function getProductConcreteLocalizedTransfers(DataSetInterface $dataSet)
    {
        return $dataSet[ProductConcreteHydratorStep::PRODUCT_CONCRETE_LOCALIZED_TRANSFER] ?? [];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer
     */
    protected function getProductConcreteTransfer(DataSetInterface $dataSet)
    {
        return $dataSet[ProductConcreteHydratorStep::PRODUCT_CONCRETE_TRANSFER];
    }
}
