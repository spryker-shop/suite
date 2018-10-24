<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductImage;

use Generated\Shared\DataBuilder\SpyProductImageEntityBuilder;
use Generated\Shared\DataBuilder\SpyProductImageSetEntityBuilder;
use Generated\Shared\Transfer\SpyProductImageSetToProductImageEntityTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageSetToProductImageTableMap;
use Orm\Zed\ProductImage\Persistence\Map\SpyProductImageTableMap;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetQuery;
use Pyz\Zed\DataImport\Business\Model\ProductImage\ProductImageHydratorStep;
use PyzTest\Zed\DataImport\Business\Model\AbstractWriterTest;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductImage
 * @group AbstractProductImageWriterTest
 * Add your own group annotations below this line
 */
abstract class AbstractProductImageWriterTest extends AbstractWriterTest
{
    protected const PRODUCTS_LIMIT = 2;

    /**
     * @param array $products
     * @param \Orm\Zed\Locale\Persistence\SpyLocale $locale
     *
     * @return array
     */
    protected function createDataSets(array $products, SpyLocale $locale): array
    {
        $result = [];

        foreach ($products as $product) {
            $dataSet = new DataSet();
            $dataSet[ProductImageHydratorStep::KEY_ABSTRACT_SKU] = $product[SpyProductAbstractTableMap::COL_SKU];
            $dataSet[ProductImageHydratorStep::KEY_CONCRETE_SKU] = '';
            $dataSet[ProductImageHydratorStep::KEY_LOCALE] = $locale->getLocaleName();
            /**
             * @var \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer
             */
            $spyProductImageSetEntityTransfer = (new SpyProductImageSetEntityBuilder())->build();
            $dataSet[ProductImageHydratorStep::DATA_PRODUCT_IMAGE_SET_TRANSFER] = $spyProductImageSetEntityTransfer
                ->setFkLocale($locale->getIdLocale())
                ->setFkProductAbstract($product[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT]);
            $dataSet[ProductImageHydratorStep::DATA_PRODUCT_IMAGE_TRANSFER] = (new SpyProductImageEntityBuilder())->build();
            $dataSet[ProductImageHydratorStep::DATA_PRODUCT_IMAGE_TO_IMAGE_SET_RELATION_TRANSFER] = (new SpyProductImageSetToProductImageEntityTransfer())
                ->setSortOrder(0);

            $result[$product[SpyProductAbstractTableMap::COL_SKU]] = $dataSet;
        }

        return $result;
    }

    /**
     * @param array $products
     * @param \Orm\Zed\Locale\Persistence\SpyLocale $locale
     *
     * @return array
     */
    protected function queryDataFromDB(array $products, SpyLocale $locale): array
    {
        $productImageSets = SpyProductImageSetQuery::create()
            ->filterByFkLocale($locale->getIdLocale())
            ->useSpyProductAbstractQuery()
                ->filterBySku_In(array_column($products, SpyProductAbstractTableMap::COL_SKU))
            ->endUse()
            ->select([
                SpyProductImageSetTableMap::COL_ID_PRODUCT_IMAGE_SET,
                SpyProductAbstractTableMap::COL_SKU,
                SpyProductImageSetTableMap::COL_NAME,
            ])
            ->find()
            ->toArray();

        $setIds = array_column($productImageSets, SpyProductImageSetTableMap::COL_ID_PRODUCT_IMAGE_SET);
        $productImages = SpyProductImageQuery::create()
            ->useSpyProductImageSetToProductImageQuery()
                ->filterByFkProductImageSet_In($setIds)
            ->endUse()
            ->select([
                SpyProductImageSetToProductImageTableMap::COL_FK_PRODUCT_IMAGE_SET,
                SpyProductImageTableMap::COL_EXTERNAL_URL_LARGE,
                SpyProductImageTableMap::COL_EXTERNAL_URL_SMALL,
            ])
            ->find();

        return [
            'productImageSets' => $productImageSets,
            'productImages' => $productImages,
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
        $this->assertCount(count($dataSets), $fetchedResult['productImageSets']);
        $this->assertNotEmpty($fetchedResult['productImages']);

        /** @var \Orm\Zed\Product\Persistence\SpyProduct $productEntity */
        foreach ($fetchedResult['productImageSets'] as $productImageSet) {
            //Image Set
            /** @var \Generated\Shared\Transfer\SpyProductImageSetEntityTransfer $dataSetProductImageSet */
            $dataSetProductImageSet = $dataSets[$productImageSet[SpyProductAbstractTableMap::COL_SKU]][ProductImageHydratorStep::DATA_PRODUCT_IMAGE_SET_TRANSFER];
            $this->assertEquals(
                $dataSetProductImageSet->getName(),
                $productImageSet[SpyProductImageSetTableMap::COL_NAME]
            );
        }
    }

    /**
     * @return array
     */
    protected function getAbstractProducts(): array
    {
        return SpyProductAbstractQuery::create()
            ->select([
                SpyProductAbstractTableMap::COL_SKU,
                SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT,
            ])
            ->limit(static::PRODUCTS_LIMIT)
            ->find()
            ->toArray();
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocale
     */
    protected function getLocale(): SpyLocale
    {
        return SpyLocaleQuery::create()->findOne();
    }
}
