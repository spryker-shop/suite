<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductPrice;

use ArrayObject;
use Generated\Shared\DataBuilder\SpyPriceProductStoreEntityBuilder;
use Generated\Shared\Transfer\SpyCurrencyEntityTransfer;
use Generated\Shared\Transfer\SpyPriceProductEntityTransfer;
use Generated\Shared\Transfer\SpyPriceTypeEntityTransfer;
use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyStoreEntityTransfer;
use Orm\Zed\Currency\Persistence\SpyCurrency;
use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceProductStoreTableMap;
use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Orm\Zed\PriceProduct\Persistence\SpyPriceProductQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\ProductPriceHydratorStep;
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
 * @group ProductPrice
 * @group AbstractProductPriceWriterTest
 * Add your own group annotations below this line
 */
abstract class AbstractProductPriceWriterTest extends AbstractWriterTest
{
    protected const PRICE_TYPES = [
        'DATAIMPORT_TEST1',
        'DATAIMPORT_TEST2',
    ];

    protected const PRICE_MODE_CONFIGURATION = 2;

    /**
     * @return array
     */
    protected function createDataSets(): array
    {
        $result = [];

        /**
         * @var \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer
         */
        $spyPriceProductStoreEntityTransfer = (new SpyPriceProductStoreEntityBuilder())->build();
        $priceProductStore = $spyPriceProductStoreEntityTransfer
            ->setCurrency((new SpyCurrencyEntityTransfer())->setName($this->getCurrency()->getCode()))
            ->setStore((new SpyStoreEntityTransfer())->setName(Store::getDefaultStore()));
        $priceProductStores = new ArrayObject();
        $priceProductStores->append($priceProductStore);

        $abstractProductSkus = $this->getAbstractProductSkus();
        foreach ($abstractProductSkus as $index => $sku) {
            $dataSet = new DataSet();

            $dataSet[ProductPriceHydratorStep::KEY_STORE] = $priceProductStore->getStore()->getName();
            $dataSet[ProductPriceHydratorStep::KEY_CURRENCY] = $priceProductStore->getCurrency()->getName();
            $dataSet[ProductPriceHydratorStep::KEY_PRICE_GROSS] = $priceProductStore->getGrossPrice();
            $dataSet[ProductPriceHydratorStep::KEY_PRICE_NET] = $priceProductStore->getNetPrice();
            $dataSet[ProductPriceHydratorStep::KEY_ABSTRACT_SKU] = $sku;
            $dataSet[ProductPriceHydratorStep::KEY_CONCRETE_SKU] = '';
            $dataSet[ProductPriceHydratorStep::PRICE_TYPE_TRANSFER] = (new SpyPriceTypeEntityTransfer())
                ->setName(static::PRICE_TYPES[$index])
                ->setPriceModeConfiguration(static::PRICE_MODE_CONFIGURATION);
            $dataSet[ProductPriceHydratorStep::PRICE_PRODUCT_TRANSFER] = (new SpyPriceProductEntityTransfer())
                ->setPriceType($dataSet[ProductPriceHydratorStep::PRICE_TYPE_TRANSFER])
                ->setSpyProductAbstract((new SpyProductAbstractEntityTransfer())->setSku($sku))
                ->setSpyPriceProductStores($priceProductStores);

            $result[$sku] = $dataSet;
        }

        return $result;
    }

    /**
     * @param array $skus
     *
     * @return array
     */
    protected function queryDataFromDB(array $skus): array
    {
        return SpyPriceProductQuery::create()
            ->joinPriceProductStore()
            ->useSpyProductAbstractQuery()
                ->filterBySku_In($skus)
            ->endUse()
            ->usePriceTypeQuery()
                ->filterByName_In(static::PRICE_TYPES)
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

    /**
     * @return \Orm\Zed\Currency\Persistence\SpyCurrency
     */
    protected function getCurrency(): SpyCurrency
    {
        return SpyCurrencyQuery::create()->findOne();
    }

    /**
     * @return array
     */
    protected function getAbstractProductSkus(): array
    {
        return SpyProductAbstractQuery::create()
            ->select(SpyProductAbstractTableMap::COL_SKU)
            ->limit(count(static::PRICE_TYPES))
            ->find()
            ->toArray();
    }
}
