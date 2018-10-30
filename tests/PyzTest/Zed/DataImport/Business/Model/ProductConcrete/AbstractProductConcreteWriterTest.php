<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductConcrete;

use Generated\Shared\DataBuilder\SpyProductEntityBuilder;
use Generated\Shared\DataBuilder\SpyProductLocalizedAttributesEntityBuilder;
use Generated\Shared\Transfer\SpyProductSearchEntityTransfer;
use Orm\Zed\Locale\Persistence\SpyLocale;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteHydratorStep;
use PyzTest\Zed\DataImport\Business\Model\AbstractWriterTest;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSet;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Zed
 * @group DataImport
 * @group Business
 * @group Model
 * @group ProductConcrete
 * @group AbstractProductConcreteWriterTest
 * Add your own group annotations below this line
 */
abstract class AbstractProductConcreteWriterTest extends AbstractWriterTest
{
    protected const LOCALES_LIMIT = 2;

    protected const PRODUCTS_LIMIT = 2;

    /**
     * @return array
     */
    protected function createDataSets(): array
    {
        $result = [];

        $abstractSkus = $this->getAbstractProductSkus();
        $locale = $this->getLocale();
        foreach ($abstractSkus as $abstractSku) {
            $dataSet = new DataSet();

            /** @var \Generated\Shared\Transfer\SpyProductEntityTransfer $productTransfer */
            $productTransfer = (new SpyProductEntityBuilder())->build();
            $dataSet[ProductConcreteHydratorStep::KEY_ABSTRACT_SKU] = $abstractSku;
            $dataSet[ProductConcreteHydratorStep::DATA_PRODUCT_CONCRETE_TRANSFER] = $productTransfer;
            /**
             * @var \Generated\Shared\Transfer\SpyProductLocalizedAttributesEntityTransfer
             */
            $spyProductLocalizedAttributesEntityTransfer = (new SpyProductLocalizedAttributesEntityBuilder())
                ->build();
            $dataSet[ProductConcreteHydratorStep::DATA_PRODUCT_CONCRETE_LOCALIZED_TRANSFER] = [
                [
                    'sku' => $productTransfer->getSku(),
                    'localizedAttributeTransfer' => $spyProductLocalizedAttributesEntityTransfer
                        ->setFkLocale($locale->getIdLocale()),
                    'productSearchEntityTransfer' => (new SpyProductSearchEntityTransfer())
                        ->setFkLocale($locale->getIdLocale())
                        ->setIsSearchable(1),
                ],
            ];
            $dataSet[ProductConcreteHydratorStep::DATA_PRODUCT_BUNDLE_TRANSFER] = [];

            $result[$productTransfer->getSku()] = $dataSet;
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
        $products = SpyProductQuery::create()->filterBySku_In($skus)->find();
        $productIds = array_column($products->toArray(), 'IdProduct');
        $productsLocalizedAttributes = SpyProductLocalizedAttributesQuery::create()->filterByFkProduct_In($productIds);

        return [
            'products' => $products,
            'productsLocalizedAttributes' => $productsLocalizedAttributes,
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
        $this->assertCount(count($dataSets), $fetchedResult['products']);

        /** @var \Orm\Zed\Product\Persistence\SpyProduct $productEntity */
        foreach ($fetchedResult['products'] as $productEntity) {
            //Product
            /** @var \Generated\Shared\Transfer\SpyProductEntityTransfer $dataSetProduct */
            $dataSetProduct = $dataSets[$productEntity->getSku()][ProductConcreteHydratorStep::DATA_PRODUCT_CONCRETE_TRANSFER];
            $this->assertEquals(
                $dataSetProduct->getIsActive(),
                $productEntity->getIsActive()
            );
            $this->assertEquals(
                $dataSetProduct->getIsQuantitySplittable(),
                $productEntity->getIsQuantitySplittable()
            );

            //Localized
            $dataSetLocalizedAttributes = $dataSets[$productEntity->getSku()][ProductConcreteHydratorStep::DATA_PRODUCT_CONCRETE_LOCALIZED_TRANSFER];
            /** @var \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes $localizedAttribute */
            foreach ($fetchedResult['productsLocalizedAttributes'] as $localizedAttribute) {
                if ($localizedAttribute->getFkProduct() !== $productEntity->getIdProduct()) {
                    continue;
                }
                foreach ($dataSetLocalizedAttributes as $dataSetLocalizedAttribute) {
                    /** @var \Generated\Shared\Transfer\SpyProductLocalizedAttributesEntityTransfer $localizedAttributeTransfer */
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
                        $localizedAttributeTransfer->getIsComplete(),
                        $localizedAttribute->getIsComplete()
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
        return SpyLocaleQuery::create()->findOne();
    }

    /**
     * @return array
     */
    protected function getAbstractProductSkus(): array
    {
        return SpyProductAbstractQuery::create()
            ->select(SpyProductAbstractTableMap::COL_SKU)
            ->limit(static::PRODUCTS_LIMIT)
            ->find()
            ->toArray();
    }
}
