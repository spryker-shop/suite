<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductImage;

use Generated\Shared\Transfer\SpyProductImageEntityTransfer;
use Generated\Shared\Transfer\SpyProductImageSetEntityTransfer;
use Generated\Shared\Transfer\SpyProductImageSetToProductImageEntityTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
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
    protected const SKU1 = '001';
    protected const SKU2 = '002';

    protected const FK_DE_LOCAL = 46;

    /**
     * @return array
     */
    protected function createDataSets(): array
    {
        $dataSet1 = new DataSet();
        $dataSet1[ProductImageHydratorStep::KEY_ABSTRACT_SKU] = static::SKU1;
        $dataSet1[ProductImageHydratorStep::KEY_CONCRETE_SKU] = '';
        $dataSet1[ProductImageHydratorStep::KEY_LOCALE] = 'de_DE';
        $dataSet1[ProductImageHydratorStep::PRODUCT_IMAGE_SET_TRANSFER] = (new SpyProductImageSetEntityTransfer())
            ->setName('default');
        $dataSet1[ProductImageHydratorStep::PRODUCT_IMAGE_TRANSFER] = (new SpyProductImageEntityTransfer())
            ->setExternalUrlSmall('//images.icecat.biz/img/norm/medium/25904006-8438.jpg')
            ->setExternalUrlLarge('//images.icecat.biz/img/norm/high/25904006-8438.jpg');
        $dataSet1[ProductImageHydratorStep::PRODUCT_IMAGE_TO_IMAGE_SET_RELATION_TRANSFER] = (new SpyProductImageSetToProductImageEntityTransfer())
            ->setSortOrder(0);

        $dataSet2 = new DataSet();
        $dataSet2[ProductImageHydratorStep::KEY_ABSTRACT_SKU] = static::SKU2;
        $dataSet2[ProductImageHydratorStep::KEY_CONCRETE_SKU] = '';
        $dataSet2[ProductImageHydratorStep::KEY_LOCALE] = 'de_DE';
        $dataSet2[ProductImageHydratorStep::PRODUCT_IMAGE_SET_TRANSFER] = (new SpyProductImageSetEntityTransfer())
            ->setName('default');
        $dataSet2[ProductImageHydratorStep::PRODUCT_IMAGE_TRANSFER] = (new SpyProductImageEntityTransfer())
            ->setExternalUrlSmall('//images.icecat.biz/img/norm/medium/25904006-8439.jpg')
            ->setExternalUrlLarge('//images.icecat.biz/img/norm/high/25904006-8439.jpg');
        $dataSet2[ProductImageHydratorStep::PRODUCT_IMAGE_TO_IMAGE_SET_RELATION_TRANSFER] = (new SpyProductImageSetToProductImageEntityTransfer())
            ->setSortOrder(0);

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
        $productImageSets = SpyProductImageSetQuery::create()
            ->filterByFkLocale(static::FK_DE_LOCAL)
            ->useSpyProductAbstractQuery()
                ->filterBySku_In([static::SKU1, static::SKU2])
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
            $dataSetProductImageSet = $dataSets[$productImageSet[SpyProductAbstractTableMap::COL_SKU]][ProductImageHydratorStep::PRODUCT_IMAGE_SET_TRANSFER];
            $this->assertEquals(
                $dataSetProductImageSet->getName(),
                $productImageSet[SpyProductImageSetTableMap::COL_NAME]
            );
        }
    }
}
