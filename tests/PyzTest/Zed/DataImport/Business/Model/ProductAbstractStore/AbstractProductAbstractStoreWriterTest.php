<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductAbstractStore;

use Generated\Shared\Transfer\ProductAbstractStoreTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractStoreQuery;
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\ProductAbstractStoreHydratorStep;
use PyzTest\Zed\DataImport\Business\Model\AbstractWriterTest;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductAbstractStore
 * @group AbstractProductAbstractStoreWriterTest
 * Add your own group annotations below this line
 */
abstract class AbstractProductAbstractStoreWriterTest extends AbstractWriterTest
{
    protected const SKU1 = '001';
    protected const SKU2 = '002';

    protected const STORE_NAME = 'DE';

    /**
     * @return array
     */
    protected function createDataSets(): array
    {
        $dataSet1 = new DataSet();
        $dataSet1[ProductAbstractStoreHydratorStep::PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFER] = (new ProductAbstractStoreTransfer())
            ->setProductAbstractSku(static::SKU1)
            ->setStoreName(static::STORE_NAME);

        $dataSet2 = new DataSet();
        $dataSet2[ProductAbstractStoreHydratorStep::PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFER] = (new ProductAbstractStoreTransfer())
            ->setProductAbstractSku(static::SKU2)
            ->setStoreName(static::STORE_NAME);

        return [
            static::SKU1 => $dataSet1,
            static::SKU2 => $dataSet2,
        ];
    }

    /**
     * @return array
     */
    protected function queryDataFromDB(): array
    {
        return SpyProductAbstractStoreQuery::create()
            ->useSpyProductAbstractQuery()
                ->filterBySku_In([static::SKU1, static::SKU2])
            ->endUse()
            ->useSpyStoreQuery()
                ->filterByName(static::STORE_NAME)
            ->endUse()
            ->select([SpyProductAbstractTableMap::COL_SKU, SpyStoreTableMap::COL_NAME])
            ->find()
            ->toArray();
    }

    /**
     * @param array $dataSets
     * @param array $productAbstractStores
     *
     * @return void
     */
    protected function assertImportedData(array $dataSets, array $productAbstractStores): void
    {
        $this->assertCount(count($dataSets), $productAbstractStores);

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract $abstractProductEntity */
        foreach ($productAbstractStores as $productAbstractStore) {
            $productAbstractStoreDataSet = $dataSets[$productAbstractStore[SpyProductAbstractTableMap::COL_SKU]];
            $this->assertNotEmpty($productAbstractStoreDataSet);
            $this->assertEquals(
                $productAbstractStoreDataSet[ProductAbstractStoreHydratorStep::PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFER]->getStoreName(),
                $productAbstractStore[SpyStoreTableMap::COL_NAME]
            );
        }
    }
}
