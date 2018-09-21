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
use Orm\Zed\Stock\Persistence\Map\SpyStockProductTableMap;
use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Pyz\Zed\DataImport\Business\Model\ProductStock\ProductStockHydratorStep;
use PyzTest\Zed\DataImport\Business\Model\AbstractWriterTest;
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
    protected const SKU1_CONCRETE = '001_25904006';
    protected const SKU2_CONCRETE = '002_25904004';

    protected const WAREHOUSE1_NAME = 'Warehouse1';
    protected const WAREHOUSE2_NAME = 'Warehouse2';

    protected const WAREHOUSE1_QTY = 155;
    protected const WAREHOUSE2_QTY = 154;

    /**
     * @return array
     */
    protected function createDataSets(): array
    {
        $dataSet1 = new DataSet();
        $dataSet1[ProductStockHydratorStep::KEY_IS_BUNDLE] = 0;
        $dataSet1[ProductStockHydratorStep::KEY_CONCRETE_SKU] = static::SKU1_CONCRETE;
        $dataSet1[ProductStockHydratorStep::STOCK_ENTITY_TRANSFER] = (new SpyStockEntityTransfer())
            ->setName(static::WAREHOUSE1_NAME);

        $dataSet1[ProductStockHydratorStep::STOCK_PRODUCT_ENTITY_TRANSFER] = (new SpyStockProductEntityTransfer())
            ->setQuantity(static::WAREHOUSE1_QTY)
            ->setIsNeverOutOfStock(0);

        $dataSet2 = new DataSet();
        $dataSet2[ProductStockHydratorStep::KEY_IS_BUNDLE] = 0;
        $dataSet2[ProductStockHydratorStep::KEY_CONCRETE_SKU] = static::SKU2_CONCRETE;
        $dataSet2[ProductStockHydratorStep::STOCK_ENTITY_TRANSFER] = (new SpyStockEntityTransfer())
            ->setName(static::WAREHOUSE2_NAME);

        $dataSet2[ProductStockHydratorStep::STOCK_PRODUCT_ENTITY_TRANSFER] = (new SpyStockProductEntityTransfer())
            ->setQuantity(static::WAREHOUSE2_QTY)
            ->setIsNeverOutOfStock(0);

        return [
            static::SKU1_CONCRETE => $dataSet1,
            static::SKU2_CONCRETE => $dataSet2,
        ];
    }

    /**
     * @return array
     */
    protected function queryDataFromDB(): array
    {

        $stockProducts = SpyStockProductQuery::create()
            ->filterByQuantity_In([static::WAREHOUSE1_QTY, static::WAREHOUSE2_QTY])
            ->useStockQuery()
                ->filterByName_In([static::WAREHOUSE1_NAME, static::WAREHOUSE2_NAME])
            ->endUse()
            ->useSpyProductQuery()
                ->filterBySku_In([static::SKU1_CONCRETE, static::SKU2_CONCRETE])
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
                ->filterBySku_In([static::SKU1_CONCRETE, static::SKU2_CONCRETE])
            ->endUse()
            ->select([
                SpyAvailabilityTableMap::COL_SKU,
                SpyAvailabilityTableMap::COL_QUANTITY,
                SpyAvailabilityTableMap::COL_IS_NEVER_OUT_OF_STOCK,
                SpyAvailabilityAbstractTableMap::COL_QUANTITY,
            ])
            ->useStoreQuery()
                ->filterByName('DE')
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
}
