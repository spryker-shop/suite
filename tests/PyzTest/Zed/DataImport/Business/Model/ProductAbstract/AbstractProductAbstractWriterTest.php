<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductAbstract;

use Generated\Shared\DataBuilder\SpyProductAbstractEntityBuilder;
use Generated\Shared\DataBuilder\SpyProductAbstractLocalizedAttributesEntityBuilder;
use Generated\Shared\DataBuilder\SpyUrlEntityBuilder;
use Generated\Shared\Transfer\SpyProductCategoryEntityTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryQuery;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use PyzTest\Zed\DataImport\Business\Model\AbstractWriterTest;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductAbstract
 * @group AbstractProductAbstractWriterTest
 * Add your own group annotations below this line
 */
abstract class AbstractProductAbstractWriterTest extends AbstractWriterTest
{
    protected const PRODUCT_ORDER = 16;

    protected const DATA_SET_COUNT = 2;

    protected const LOCALES_LIMIT = 2;

    /**
     * @return array
     */
    protected function createDataSets(): array
    {
        $result = [];

        $locale = $this->getLocale();
        for ($i = 0; $i < static::DATA_SET_COUNT; $i++) {
            $dataSet = new DataSet();
            /**
             * @var \Generated\Shared\Transfer\SpyProductAbstractEntityTransfer
             */
            $spyProductAbstractEntityTransfer = (new SpyProductAbstractEntityBuilder())
                ->build();
            $spyProductAbstractEntityTransfer
                ->setFkTaxSet(1)
                ->setAttributes('{"flash_range_tele":"4.2-4.9 ft","color":"Red"}');
            $dataSet[ProductAbstractHydratorStep::DATA_PRODUCT_ABSTRACT_TRANSFER] = $spyProductAbstractEntityTransfer;

            /**
             * @var \Generated\Shared\Transfer\SpyProductAbstractLocalizedAttributesEntityTransfer
             */
            $productAbstractLocalizedAttributesEntityTransfer = (new SpyProductAbstractLocalizedAttributesEntityBuilder())
                ->build();
            $productAbstractLocalizedAttributesEntityTransfer
                ->setFkLocale($locale->getIdLocale())
                ->setAttributes('{"flash_range_tele":"4.2-4.9 ft","color":"Red"}');
            $dataSet[ProductAbstractHydratorStep::DATA_PRODUCT_ABSTRACT_LOCALIZED_TRANSFER] = [
                [
                    'abstract_sku' => $spyProductAbstractEntityTransfer->getSku(),
                    'localizedAttributeTransfer' => $productAbstractLocalizedAttributesEntityTransfer,
                ],
            ];
            /**
             * @var \Generated\Shared\Transfer\SpyUrlEntityTransfer
             */
            $urlEntityTransfer = (new SpyUrlEntityBuilder())->build();
            $urlEntityTransfer
                ->setFkLocale($locale->getIdLocale());
            $dataSet[ProductAbstractHydratorStep::DATA_PRODUCT_URL_TRANSFER] = [
                [
                    'abstract_sku' => $spyProductAbstractEntityTransfer->getSku(),
                    'urlTransfer' => $urlEntityTransfer,
                ],
            ];
            $dataSet[ProductAbstractHydratorStep::DATA_PRODUCT_CATEGORY_TRANSFER] = [
                [
                    'abstract_sku' => $spyProductAbstractEntityTransfer->getSku(),
                    'productCategoryTransfer' => (new SpyProductCategoryEntityTransfer())
                        ->setFkCategory($this->getCategory()->getIdCategory())
                        ->setProductOrder(static::PRODUCT_ORDER),
                ],
            ];
            $result[$spyProductAbstractEntityTransfer->getSku()] = $dataSet;
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
        $abstractProducts = SpyProductAbstractQuery::create()->filterBySku_In($skus)->find();
        $productAbstractIds = array_column($abstractProducts->toArray(), 'IdProductAbstract');
        $abstractProductsLocalizedAttributes = SpyProductAbstractLocalizedAttributesQuery::create()->filterByFkProductAbstract_In($productAbstractIds);
        $abstractProductsCategories = SpyProductCategoryQuery::create()->filterByFkProductAbstract_In($productAbstractIds)->find();
        $abstractProductsUrls = SpyUrlQuery::create()->filterByFkResourceProductAbstract_In($productAbstractIds);

        return [
            'abstractProducts' => $abstractProducts,
            'abstractProductsLocalizedAttributes' => $abstractProductsLocalizedAttributes,
            'abstractProductsCategories' => $abstractProductsCategories,
            'abstractProductsUrls' => $abstractProductsUrls,
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
        $this->assertCount(count($dataSets), $fetchedResult['abstractProducts']);

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract $abstractProductEntity */
        foreach ($fetchedResult['abstractProducts'] as $abstractProductEntity) {
            //Abstract product
            /** @var \Generated\Shared\Transfer\SpyProductAbstractEntityTransfer $dataSetProduct */
            $dataSetProduct = $dataSets[$abstractProductEntity->getSku()][ProductAbstractHydratorStep::DATA_PRODUCT_ABSTRACT_TRANSFER];
            $this->assertEquals(
                $dataSetProduct->getAttributes(),
                $abstractProductEntity->getAttributes()
            );
            $this->assertEquals(
                $dataSetProduct->getColorCode(),
                $abstractProductEntity->getColorCode()
            );

            //Localized
            $dataSetLocalizedAttributes = $dataSets[$abstractProductEntity->getSku()][ProductAbstractHydratorStep::DATA_PRODUCT_ABSTRACT_LOCALIZED_TRANSFER];
            /** @var \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes $localizedAttribute */
            foreach ($fetchedResult['abstractProductsLocalizedAttributes'] as $localizedAttribute) {
                if ($localizedAttribute->getFkProductAbstract() !== $abstractProductEntity->getIdProductAbstract()) {
                    continue;
                }
                foreach ($dataSetLocalizedAttributes as $dataSetLocalizedAttribute) {
                    /** @var \Generated\Shared\Transfer\SpyProductAbstractLocalizedAttributesEntityTransfer $localizedAttributeTransfer */
                    $localizedAttributeTransfer = $dataSetLocalizedAttribute['localizedAttributeTransfer'];
                    if ($localizedAttribute->getFkLocale() !== $localizedAttributeTransfer->getFkLocale()) {
                        continue;
                    }
                    $this->assertEquals(
                        $localizedAttributeTransfer->getName(),
                        $localizedAttribute->getName()
                    );
                    $this->assertEquals(
                        $localizedAttributeTransfer->getDescription(),
                        $localizedAttribute->getDescription()
                    );
                    $this->assertEquals(
                        $localizedAttributeTransfer->getMetaTitle(),
                        $localizedAttribute->getMetaTitle()
                    );
                }
            }
        }
    }

    /**
     * @return \Orm\Zed\Locale\Persistence\SpyLocale
     */
    protected function getLocale(): SpyLocale
    {
        return SpyLocaleQuery::create()
            ->findOne();
    }

    /**
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function getCategory(): SpyCategory
    {
        return SpyCategoryQuery::create()->findOne();
    }
}
