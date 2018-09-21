<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductAbstract;

use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyProductAbstractLocalizedAttributesEntityTransfer;
use Generated\Shared\Transfer\SpyProductCategoryEntityTransfer;
use Generated\Shared\Transfer\SpyUrlEntityTransfer;
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
    protected const SKU1 = '001';
    protected const SKU2 = '002';

    protected const FK_DE_LOCAL = 46;
    protected const FK_EN_LOCAL = 66;

    protected const FK_CATEGORY = 4;

    protected const PRODUCT_ORDER = 16;

    /**
     * @return array
     */
    protected function createDataSets(): array
    {
        $dataSet1 = new DataSet();
        $dataSet1[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_TRANSFER] = (new SpyProductAbstractEntityTransfer())
            ->setNewFrom('2017-06-01 00:00:00.000000')
            ->setNewTo('2017-06-30 00:00:00.000000')
            ->setSku(static::SKU1)
            ->setAttributes('{"megapixel":"20 MP","flash_range_tele":"1.3-1.5 m","memory_slots":"1","usb_version":"2","brand":"Canon"}')
            ->setFkTaxSet(1)
            ->setColorCode('#DC2E09');
        $dataSet1[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_LOCALIZED_TRANSFER] = [
            [
                'abstract_sku' => static::SKU1,
                'localizedAttributeTransfer' => (new SpyProductAbstractLocalizedAttributesEntityTransfer())
                    ->setFkLocale(static::FK_DE_LOCAL)
                    ->setName('Canon IXUS 160')
                    ->setDescription('Beeindruckende Aufnahmen, ganz einfach Smart Auto ermöglicht die mühelose Aufnahme von fantastischen Fotos und Movies – die Kamera wählt in diesem Modus automatisch die idealen Einstellungen für die jeweilige Aufnahmesituation. Sie müssen nur noch das Motiv anvisieren und auslösen. Ein Druck auf die Hilfe-Taste führt zu leicht verständlichen Erklärungen der Kamerafunktionen. Zahlreiche Kreativfilter laden zum Experimentieren ein und bieten echten Fotospaß. So lässt sich neben vielen anderen Optionen der Verzeichnungseffekt eines Fisheye-Objektivs nachempfinden oder in Fotos und Movies werden die Dinge wie Miniaturmodelle dargestellt.')
                    ->setMetaTitle('Canon IXUS 160')
                    ->setMetaKeywords('Canon,Entertainment Electronics')
                    ->setMetaDescription('Beeindruckende Aufnahmen, ganz einfach Smart Auto ermöglicht die mühelose Aufnahme von fantastischen Fotos und Movies – die Kamera wählt in diesem Modus au')
                    ->setAttributes('{"color":"Weinrot"}'),
            ],
            [
                'abstract_sku' => static::SKU1,
                'localizedAttributeTransfer' => (new SpyProductAbstractLocalizedAttributesEntityTransfer())
                    ->setFkLocale(static::FK_EN_LOCAL)
                    ->setName('Canon IXUS 160')
                    ->setDescription('Beeindruckende Aufnahmen, ganz einfach Smart Auto ermöglicht die mühelose Aufnahme von fantastischen Fotos und Movies – die Kamera wählt in diesem Modus automatisch die idealen Einstellungen für die jeweilige Aufnahmesituation. Sie müssen nur noch das Motiv anvisieren und auslösen. Ein Druck auf die Hilfe-Taste führt zu leicht verständlichen Erklärungen der Kamerafunktionen. Zahlreiche Kreativfilter laden zum Experimentieren ein und bieten echten Fotospaß. So lässt sich neben vielen anderen Optionen der Verzeichnungseffekt eines Fisheye-Objektivs nachempfinden oder in Fotos und Movies werden die Dinge wie Miniaturmodelle dargestellt.')
                    ->setMetaTitle('Canon IXUS 160')
                    ->setMetaKeywords('Canon,Entertainment Electronics')
                    ->setMetaDescription('Add a personal touch Make shots your own with quick and easy control over picture settings such as brightness and colour intensity. Preview the results whi')
                    ->setAttributes('{"flash_range_tele":"4.2-4.9 ft","color":"Red"}'),
            ],
        ];
        $dataSet1[ProductAbstractHydratorStep::PRODUCT_CATEGORY_TRANSFER] = [
            [
                'abstract_sku' => static::SKU1,
                'productCategoryTransfer' => (new SpyProductCategoryEntityTransfer())
                    ->setFkCategory(static::FK_CATEGORY)
                    ->setProductOrder(static::PRODUCT_ORDER),
            ],
        ];
        $dataSet1[ProductAbstractHydratorStep::PRODUCT_URL_TRANSFER] = [
            [
                'abstract_sku' => static::SKU1,
                'urlTransfer' => (new SpyUrlEntityTransfer())
                    ->setFkLocale(static::FK_DE_LOCAL)
                    ->setUrl('/de/canon-ixus-160-001'),
            ],
            [
                'abstract_sku' => static::SKU1,
                'urlTransfer' => (new SpyUrlEntityTransfer())
                    ->setFkLocale(static::FK_EN_LOCAL)
                    ->setUrl('/en/canon-ixus-160-001'),
            ],
        ];

        $dataSet2 = new DataSet();
        $dataSet2[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_TRANSFER] = (new SpyProductAbstractEntityTransfer())
            ->setNewFrom('2017-06-01 00:00:00.000000')
            ->setNewTo('2017-06-30 00:00:00.000000')
            ->setSku(static::SKU2)
            ->setAttributes('{"megapixel":"20 MP","flash_range_tele":"1.3-1.5 m","memory_slots":"1","usb_version":"2","brand":"Canon"}')
            ->setFkTaxSet(1)
            ->setColorCode('#DC2E09');
        $dataSet2[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_LOCALIZED_TRANSFER] = [
            [
                'abstract_sku' => static::SKU2,
                'localizedAttributeTransfer' => (new SpyProductAbstractLocalizedAttributesEntityTransfer())
                    ->setFkLocale(static::FK_DE_LOCAL)
                    ->setName('Canon IXUS 161')
                    ->setDescription('Beeindruckende Aufnahmen, ganz einfach Smart Auto ermöglicht die mühelose Aufnahme von fantastischen Fotos und Movies – die Kamera wählt in diesem Modus automatisch die idealen Einstellungen für die jeweilige Aufnahmesituation. Sie müssen nur noch das Motiv anvisieren und auslösen. Ein Druck auf die Hilfe-Taste führt zu leicht verständlichen Erklärungen der Kamerafunktionen. Zahlreiche Kreativfilter laden zum Experimentieren ein und bieten echten Fotospaß. So lässt sich neben vielen anderen Optionen der Verzeichnungseffekt eines Fisheye-Objektivs nachempfinden oder in Fotos und Movies werden die Dinge wie Miniaturmodelle dargestellt.')
                    ->setMetaTitle('Canon IXUS 160')
                    ->setMetaKeywords('Canon,Entertainment Electronics')
                    ->setMetaDescription('Beeindruckende Aufnahmen, ganz einfach Smart Auto ermöglicht die mühelose Aufnahme von fantastischen Fotos und Movies – die Kamera wählt in diesem Modus au')
                    ->setAttributes('{"color":"Weinrot"}'),
            ],
            [
                'abstract_sku' => static::SKU2,
                'localizedAttributeTransfer' => (new SpyProductAbstractLocalizedAttributesEntityTransfer())
                    ->setFkLocale(static::FK_EN_LOCAL)
                    ->setName('Canon IXUS 161')
                    ->setDescription('Beeindruckende Aufnahmen, ganz einfach Smart Auto ermöglicht die mühelose Aufnahme von fantastischen Fotos und Movies – die Kamera wählt in diesem Modus automatisch die idealen Einstellungen für die jeweilige Aufnahmesituation. Sie müssen nur noch das Motiv anvisieren und auslösen. Ein Druck auf die Hilfe-Taste führt zu leicht verständlichen Erklärungen der Kamerafunktionen. Zahlreiche Kreativfilter laden zum Experimentieren ein und bieten echten Fotospaß. So lässt sich neben vielen anderen Optionen der Verzeichnungseffekt eines Fisheye-Objektivs nachempfinden oder in Fotos und Movies werden die Dinge wie Miniaturmodelle dargestellt.')
                    ->setMetaTitle('Canon IXUS 160')
                    ->setMetaKeywords('Canon,Entertainment Electronics')
                    ->setMetaDescription('Add a personal touch Make shots your own with quick and easy control over picture settings such as brightness and colour intensity. Preview the results whi')
                    ->setAttributes('{"flash_range_tele":"4.2-4.9 ft","color":"Red"}'),
            ],
        ];
        $dataSet2[ProductAbstractHydratorStep::PRODUCT_CATEGORY_TRANSFER] = [
            [
                'abstract_sku' => static::SKU2,
                'productCategoryTransfer' => (new SpyProductCategoryEntityTransfer())
                    ->setFkCategory(static::FK_CATEGORY)
                    ->setProductOrder(static::PRODUCT_ORDER),
            ],
        ];
        $dataSet2[ProductAbstractHydratorStep::PRODUCT_URL_TRANSFER] = [
            [
                'abstract_sku' => static::SKU2,
                'urlTransfer' => (new SpyUrlEntityTransfer())
                    ->setFkLocale(static::FK_DE_LOCAL)
                    ->setUrl('/de/canon-ixus-160-002'),
            ],
            [
                'abstract_sku' => static::SKU2,
                'urlTransfer' => (new SpyUrlEntityTransfer())
                    ->setFkLocale(static::FK_EN_LOCAL)
                    ->setUrl('/en/canon-ixus-160-002'),
            ],
        ];

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
        $abstractProducts = SpyProductAbstractQuery::create()->filterBySku_In([static::SKU1, static::SKU2])->find();
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
