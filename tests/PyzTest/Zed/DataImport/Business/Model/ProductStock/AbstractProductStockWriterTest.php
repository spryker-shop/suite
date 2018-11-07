<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductStock;

use Generated\Shared\Transfer\SpyStockEntityTransfer;
use Generated\Shared\Transfer\SpyStockProductEntityTransfer;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityAbstractTableMap;
use Orm\Zed\Availability\Persistence\Map\SpyAvailabilityTableMap;
use Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Pyz\Zed\DataImport\Business\Model\ProductStock\ProductStockHydratorStep;
use PyzTest\Zed\DataImport\Business\Model\AbstractWriterTest;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductStock
 * @group AbstractProductStockWriterTest
 * Add your own group annotations below this line
 */
abstract class AbstractProductStockWriterTest extends AbstractWriterTest
{
    protected const WAREHOUSES_QTY = [
        12345,
        123456,
    ];

    /**
     * @param array $productSkus
     * @param array $warehouses
     *
     * @return array
     */
    protected function createDataSets(array $productSkus, array $warehouses): array
    {
        $result = [];

        foreach ($productSkus as $index => $sku) {
            $dataSet = new DataSet();

            $dataSet[ProductStockHydratorStep::KEY_IS_BUNDLE] = 0;
            $dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU] = $sku;
            $dataSet[ProductStockHydratorStep::STOCK_ENTITY_TRANSFER] = (new SpyStockEntityTransfer())
                ->setName($warehouses[$index] ?? $warehouses[0]);

            $dataSet[ProductStockHydratorStep::STOCK_PRODUCT_ENTITY_TRANSFER] = (new SpyStockProductEntityTransfer())
                ->setQuantity(static::WAREHOUSES_QTY[$index])
                ->setIsNeverOutOfStock(0);

            $result[$sku] = $dataSet;
        }

        return $result;
    }

    /**
     * @param array $skus
     * @param array $warehouses
     *
     * @return array
     */
    protected function queryDataFromDB(array $skus, array $warehouses): array
    {

        $stockProducts = SpyStockProductQuery::create()
            ->filterByQuantity_In(static::WAREHOUSES_QTY)
            ->useStockQuery()
                ->filterByName_In($warehouses)
            ->endUse()
            ->useSpyProductQuery()
                ->filterBySku_In($skus)
            ->endUse()
            ->select([
                SpyStockTableMap::COL_NAME,
                SpyStockProductTableMap::COL_QUANTITY,
                SpyProductTableMap::COL_SKU,
            ])
            ->find()
            ->toArray();
        $availability = SpyAvailabilityAbstractQuery::create()
            ->useSpyAvailabilityQuery()
                ->filterBySku_In($skus)
            ->endUse()
            ->select([
                SpyAvailabilityTableMap::COL_SKU,
                SpyAvailabilityTableMap::COL_QUANTITY,
                SpyAvailabilityTableMap::COL_IS_NEVER_OUT_OF_STOCK,
                SpyAvailabilityAbstractTableMap::COL_QUANTITY,
            ])
            ->useStoreQuery()
                ->filterByName(Store::getDefaultStore())
            ->endUse()
            ->find()
            ->toArray();

        return [
            'stockProducts' => $stockProducts,
            'availability' => $availability,
        ];
    }

    /**
     * @param array $dataSets
     * @param array $fetchedResult
     *
     * @return void
     */
    protected function assertImportedData(array $dataSets, array $fetchedResult): void
    {
        $this->assertCount(count($dataSets), $fetchedResult['stockProducts']);

        foreach ($fetchedResult['stockProducts'] as $stockProduct) {
            $dataSetStock = $dataSets[$stockProduct[SpyProductTableMap::COL_SKU]][ProductStockHydratorStep::STOCK_ENTITY_TRANSFER];

            $this->assertEquals(
                $dataSetStock->getName(),
                $stockProduct[SpyStockTableMap::COL_NAME]
            );

            $dataSetProductStock = $dataSets[$stockProduct[SpyProductTableMap::COL_SKU]][ProductStockHydratorStep::STOCK_PRODUCT_ENTITY_TRANSFER];

            $this->assertEquals(
                $dataSetProductStock->getQuantity(),
                $stockProduct[SpyStockProductTableMap::COL_QUANTITY]
            );
        }

        foreach ($fetchedResult['availability'] as $availability) {
            $dataSetProductStock = $dataSets[$availability[SpyAvailabilityTableMap::COL_SKU]][ProductStockHydratorStep::STOCK_PRODUCT_ENTITY_TRANSFER];

            $this->assertEquals(
                $dataSetProductStock->getQuantity(),
                $availability[SpyAvailabilityTableMap::COL_QUANTITY]
            );
        }
    }

    /**
     * @return array
     */
    protected function getProductsSkus(): array
    {
        return SpyProductQuery::create()
            ->limit(count(static::WAREHOUSES_QTY))
            ->select(SpyProductTableMap::COL_SKU)
            ->find()
            ->toArray();
    }

    /**
     * @return array
     */
    protected function getWarehouses(): array
    {
        return SpyStockQuery::create()
            ->limit(count(static::WAREHOUSES_QTY))
            ->select(SpyStockTableMap::COL_NAME)
            ->find()
            ->toArray();
    }
}
