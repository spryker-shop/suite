<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductPrice;

use ArrayObject;
use Generated\Shared\Transfer\SpyCurrencyEntityTransfer;
use Generated\Shared\Transfer\SpyPriceProductEntityTransfer;
use Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer;
use Generated\Shared\Transfer\SpyPriceTypeEntityTransfer;
use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyStoreEntityTransfer;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\ProductPriceHydratorStep;
use PyzTest\Zed\DataImport\Business\Model\AbstractWriterTest;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductPrice
 * @group AbstractProductPriceWriterTest
 * Add your own group annotations below this line
 */
abstract class AbstractProductPriceWriterTest extends AbstractWriterTest
{
    protected const SKU1 = '001';
    protected const SKU2 = '002';

    protected const PRICE_TYPE1 = 'DATAIMPORT_TEST1';
    protected const PRICE_TYPE2 = 'DATAIMPORT_TEST2';

    protected const PRICE_MODE_CONFIGURATION = 2;

    /**
     * @return array
     */
    protected function createDataSets(): array
    {
        $priceProductStores = new ArrayObject();
        $priceProductStores->append((new SpyPriceProductStoreEntityTransfer())
            ->setNetPrice('8999')
            ->setGrossPrice('9999')
            ->setCurrency((new SpyCurrencyEntityTransfer())->setName('EUR'))
            ->setStore((new SpyStoreEntityTransfer())->setName('DE')));

        $dataSet1 = new DataSet();
        $dataSet1[ProductPriceHydratorStep::KEY_ABSTRACT_SKU] = static::SKU1;
        $dataSet1[ProductPriceHydratorStep::KEY_CONCRETE_SKU] = '';
        $dataSet1[ProductPriceHydratorStep::PRICE_TYPE_TRANSFER] = (new SpyPriceTypeEntityTransfer())
            ->setName(static::PRICE_TYPE1)
            ->setPriceModeConfiguration(static::PRICE_MODE_CONFIGURATION);

        $dataSet1[ProductPriceHydratorStep::PRICE_PRODUCT_TRANSFER] = (new SpyPriceProductEntityTransfer())
            ->setPriceType($dataSet1[ProductPriceHydratorStep::PRICE_TYPE_TRANSFER])
            ->setSpyProductAbstract((new SpyProductAbstractEntityTransfer())->setSku(static::SKU1))
            ->setSpyPriceProductStores($priceProductStores);

        $dataSet2 = new DataSet();
        $dataSet2[ProductPriceHydratorStep::KEY_ABSTRACT_SKU] = static::SKU2;
        $dataSet2[ProductPriceHydratorStep::KEY_CONCRETE_SKU] = '';
        $dataSet2[ProductPriceHydratorStep::PRICE_TYPE_TRANSFER] = (new SpyPriceTypeEntityTransfer())
            ->setName(static::PRICE_TYPE2)
            ->setPriceModeConfiguration(static::PRICE_MODE_CONFIGURATION);
        $dataSet2[ProductPriceHydratorStep::PRICE_PRODUCT_TRANSFER] = (new SpyPriceProductEntityTransfer())
            ->setPriceType($dataSet2[ProductPriceHydratorStep::PRICE_TYPE_TRANSFER])
            ->setSpyProductAbstract((new SpyProductAbstractEntityTransfer())->setSku(static::SKU2))
            ->setSpyPriceProductStores($priceProductStores);

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
        return SpyPriceProductQuery::create()
            ->joinPriceProductStore()
            ->useSpyProductAbstractQuery()
                ->filterBySku_In([static::SKU1, static::SKU2])
            ->endUse()
            ->usePriceTypeQuery()
                ->filterByName_In([static::PRICE_TYPE1, static::PRICE_TYPE2])
            ->endUse()
            ->select([
                SpyPriceTypeTableMap::COL_NAME,
                SpyProductAbstractTableMap::COL_SKU,
                SpyPriceProductStoreTableMap::COL_GROSS_PRICE,
                SpyPriceProductStoreTableMap::COL_NET_PRICE,
            ])
            ->find()
            ->toArray();
    }

    /**
     * @param array $dataSets
     * @param array $fetchedResult
     *
     * @return void
     */
    protected function assertImportedData(array $dataSets, array $fetchedResult): void
    {
        $this->assertCount(count($dataSets), $fetchedResult);

        foreach ($fetchedResult as $productPrice) {
            /** @var \Generated\Shared\Transfer\SpyPriceProductEntityTransfer $dataSetPrice */
            $dataSetPrice = $dataSets[$productPrice[SpyProductAbstractTableMap::COL_SKU]][ProductPriceHydratorStep::PRICE_PRODUCT_TRANSFER];
            $this->assertEquals(
                $dataSetPrice->getPriceType()->getName(),
                $productPrice[SpyPriceTypeTableMap::COL_NAME]
            );
            $spyPriceProductStore = $dataSetPrice->getSpyPriceProductStores()[0];
            $this->assertEquals(
                $spyPriceProductStore->getNetPrice(),
                $productPrice[SpyPriceProductStoreTableMap::COL_NET_PRICE]
            );
            $this->assertEquals(
                $spyPriceProductStore->getGrossPrice(),
                $productPrice[SpyPriceProductStoreTableMap::COL_GROSS_PRICE]
            );
        }
    }
}
