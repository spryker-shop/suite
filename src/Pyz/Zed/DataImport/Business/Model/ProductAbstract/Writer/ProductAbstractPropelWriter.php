<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer;

use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyProductAbstractLocalizedAttributesEntityTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Business\Model\Writer\FlushInterface;
use Spryker\Zed\DataImport\Business\Model\Writer\WriterInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;

class ProductAbstractPropelWriter extends DataImporterPublisher implements WriterInterface, FlushInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet)
    {
        $productAbstractEntity = $this->createOrUpdateProductAbstract($dataSet);
        $this->createOrUpdateProductAbstractLocalizedAbstract($dataSet, $productAbstractEntity->getIdProductAbstract());
    }

    /**
     * @param DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function createOrUpdateProductAbstract(DataSetInterface $dataSet)
    {
        $productAbstractEntityTransfer = $this->getProductAbstractTransfer($dataSet);
        $productAbstractEntity = SpyProductAbstractQuery::create()
            ->filterBySku($productAbstractEntityTransfer->getSku())
            ->findOneOrCreate();

        $productAbstractEntity->fromArray($productAbstractEntityTransfer->modifiedToArray());

        if ($productAbstractEntity->isNew() || $productAbstractEntity->isModified()) {
            $productAbstractEntity->save();

            $this->addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $productAbstractEntity->getIdProductAbstract());
        }

        return $productAbstractEntity;
    }

    /**
     * @param DataSetInterface $dataSet
     * @param int $idProductAbstract
     *
     * @return void
     */
    protected function createOrUpdateProductAbstractLocalizedAbstract(DataSetInterface $dataSet, $idProductAbstract)
    {
        $productAbstractLocalizedTransfers = $this->getProductAbstractLocalizedTransfers($dataSet);

        foreach ($productAbstractLocalizedTransfers as $productAbstractLocalizedArray) {
            $productAbstractLocalizedTransfer = $productAbstractLocalizedArray['localizedAttributeTransfer'];
            $idLocale = $productAbstractLocalizedTransfer->getFkLocale();
            $productAbstractLocalizedAttributesEntity = SpyProductAbstractLocalizedAttributesQuery::create()
                ->filterByFkProductAbstract($idProductAbstract)
                ->filterByFkLocale($idLocale)
                ->findOneOrCreate();

            $productAbstractLocalizedAttributesEntity->fromArray($productAbstractLocalizedTransfer->modifiedToArray());

            if ($productAbstractLocalizedAttributesEntity->isNew() || $productAbstractLocalizedAttributesEntity->isModified()) {
                $productAbstractLocalizedAttributesEntity->save();
            }
        }
    }

    /**
     * @param DataSetInterface $dataSet
     *
     * @return SpyProductAbstractEntityTransfer
     */
    protected function getProductAbstractTransfer(DataSetInterface $dataSet)
    {
        return $dataSet[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_TRANSFER];
    }

    /**
     * @param DataSetInterface $dataSet
     *
     * @return SpyProductAbstractLocalizedAttributesEntityTransfer
     */
    protected function getProductAbstractLocalizedTransfers(DataSetInterface $dataSet)
    {
        return $dataSet[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_LOCALIZED_TRANSFER];
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->triggerEvents();
    }
}
