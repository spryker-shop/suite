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
    protected const FK_DE_LOCAL = 46;
    protected const FK_EN_LOCAL = 66;

    protected const FK_CATEGORY = 4;

    protected const PRODUCT_ORDER = 16;

    protected const DATA_SET_COUNT = 2;

    /**
     * @return array
     */
    protected function createDataSets(): array
    {
        $result = [];

        for ($i = 0; $i < static::DATA_SET_COUNT; $i++) {
            $dataSet = new DataSet();
            $spyProductAbstractEntityTransfer = (new SpyProductAbstractEntityBuilder())
                ->build()
                ->setFkTaxSet(1)
                ->setAttributes('{"flash_range_tele":"4.2-4.9 ft","color":"Red"}');
            $dataSet[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_TRANSFER] = $spyProductAbstractEntityTransfer;
            $dataSet[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_LOCALIZED_TRANSFER] = [
                [
                    'abstract_sku' => $spyProductAbstractEntityTransfer->getSku(),
                    'localizedAttributeTransfer' => (new SpyProductAbstractLocalizedAttributesEntityBuilder())
                        ->build()
                        ->setFkLocale(static::FK_DE_LOCAL)
                        ->setAttributes('{"flash_range_tele":"4.2-4.9 ft","color":"Red"}'),
                ],
                [
                    'abstract_sku' => $spyProductAbstractEntityTransfer->getSku(),
                    'localizedAttributeTransfer' => (new SpyProductAbstractLocalizedAttributesEntityBuilder())
                        ->build()
                        ->setFkLocale(static::FK_EN_LOCAL)
                        ->setAttributes('{"flash_range_tele":"4.2-4.9 ft","color":"Red"}'),
                ],
            ];
            $dataSet[ProductAbstractHydratorStep::PRODUCT_CATEGORY_TRANSFER] = [
                [
                    'abstract_sku' => $spyProductAbstractEntityTransfer->getSku(),
                    'productCategoryTransfer' => (new SpyProductCategoryEntityTransfer())
                        ->setFkCategory(static::FK_CATEGORY)
                        ->setProductOrder(static::PRODUCT_ORDER),
                ],
            ];
            $dataSet[ProductAbstractHydratorStep::PRODUCT_URL_TRANSFER] = [
                [
                    'abstract_sku' => $spyProductAbstractEntityTransfer->getSku(),
                    'urlTransfer' => (new SpyUrlEntityBuilder())
                        ->build()
                        ->setFkLocale(static::FK_DE_LOCAL),
                ],
                [
                    'abstract_sku' => $spyProductAbstractEntityTransfer->getSku(),
                    'urlTransfer' => (new SpyUrlEntityBuilder())
                        ->build()
                        ->setFkLocale(static::FK_EN_LOCAL),
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
            $dataSetProduct = $dataSets[$abstractProductEntity->getSku()][ProductAbstractHydratorStep::PRODUCT_ABSTRACT_TRANSFER];
            $this->assertEquals(
                $dataSetProduct->getAttributes(),
                $abstractProductEntity->getAttributes()
            );
            $this->assertEquals(
                $dataSetProduct->getColorCode(),
                $abstractProductEntity->getColorCode()
            );

            //Localized
            $dataSetLocalizedAttributes = $dataSets[$abstractProductEntity->getSku()][ProductAbstractHydratorStep::PRODUCT_ABSTRACT_LOCALIZED_TRANSFER];
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
}
