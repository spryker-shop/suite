<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock\Reader;

use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\EntityManager\InstancePoolingTrait;

class ProductStockReader implements ProductStockReaderInterface
{
    use InstancePoolingTrait;

    /**
     * @param int[] $availabilityAbstractIds
     *
     * @return int[]
     */
    public function getProductAbstractIdsByAvailabilityAbstractIds(array $availabilityAbstractIds): array
    {
        $isPoolingEnabled = $this->isInstancePoolingEnabled();
        if ($isPoolingEnabled) {
            $this->disableInstancePooling();
        }

        $productAbstractIds = SpyAvailabilityAbstractQuery::create()
            ->select([SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT])
            ->filterByIdAvailabilityAbstract_In($availabilityAbstractIds)
            ->addJoin(SpyAvailabilityAbstractTableMap::COL_ABSTRACT_SKU, SpyProductAbstractTableMap::COL_SKU, Criteria::INNER_JOIN)
            ->withColumn(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, SpyProductAbstractEntityTransfer::ID_PRODUCT_ABSTRACT)
            ->find()
            ->getData();

        if ($isPoolingEnabled) {
            $this->enableInstancePooling();
        }

        return $productAbstractIds;
    }
}
