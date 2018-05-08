<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Communication\Plugin\ProductAbstract;

use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyProductAbstractLocalizedAttributesEntityTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportWriterPluginInterface;

class ProductAbstractPropelWriterPlugin implements DataImportWriterPluginInterface
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

        echo 'Create Product Abstract' . PHP_EOL;
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

        foreach ($productAbstractLocalizedTransfers as $idLocale => $productAbstractLocalizedTransfer) {
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
}
