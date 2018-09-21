<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\DataImport\Business\Model\ProductConcrete;

use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductLocalizedAttributesEntityTransfer;
use Generated\Shared\Transfer\SpyProductSearchEntityTransfer;
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
    protected const SKU1 = '001';
    protected const SKU2 = '002';

    protected const SKU1_CONCRETE = '001_25904006';
    protected const SKU2_CONCRETE = '002_25904004';

    protected const FK_DE_LOCAL = 46;
    protected const FK_EN_LOCAL = 66;

    /**
     * @return array
     */
    protected function createDataSets(): array
    {
        $dataSet1 = new DataSet();
        $dataSet1[ProductConcreteHydratorStep::KEY_ABSTRACT_SKU] = static::SKU1;
        $dataSet1[ProductConcreteHydratorStep::PRODUCT_CONCRETE_TRANSFER] = (new SpyProductEntityTransfer())
            ->setSku(static::SKU1_CONCRETE)
            ->setIsActive(true)
            ->setAttributes('[]')
            ->setIsQuantitySplittable(true);
        $dataSet1[ProductConcreteHydratorStep::PRODUCT_CONCRETE_LOCALIZED_TRANSFER] = [
            [
                'sku' => static::SKU1_CONCRETE,
                'localizedAttributeTransfer' => (new SpyProductLocalizedAttributesEntityTransfer())
                    ->setFkLocale(static::FK_DE_LOCAL)
                    ->setName('Canon IXUS 160')
                    ->setDescription('Beeindruckende Aufnahmen, ganz einfach Smart Auto ermöglicht die mühelose Aufnahme von fantastischen Fotos und Movies – die Kamera wählt in diesem Modus automatisch die idealen Einstellungen für die jeweilige Aufnahmesituation. Sie müssen nur noch das Motiv anvisieren und auslösen. Ein Druck auf die Hilfe-Taste führt zu leicht verständlichen Erklärungen der Kamerafunktionen. Zahlreiche Kreativfilter laden zum Experimentieren ein und bieten echten Fotospaß. So lässt sich neben vielen anderen Optionen der Verzeichnungseffekt eines Fisheye-Objektivs nachempfinden oder in Fotos und Movies werden die Dinge wie Miniaturmodelle dargestellt.')
                    ->setAttributes('[]')
                    ->setIsComplete(true),
                'productSearchEntityTransfer' => (new SpyProductSearchEntityTransfer())
                    ->setFkLocale(static::FK_DE_LOCAL)
                    ->setIsSearchable(1),
            ],
            [
                'sku' => static::SKU1_CONCRETE,
                'localizedAttributeTransfer' => (new SpyProductLocalizedAttributesEntityTransfer())
                    ->setFkLocale(static::FK_EN_LOCAL)
                    ->setName('Canon IXUS 160')
                    ->setDescription('Add a personal touch Make shots your own with quick and easy control over picture settings such as brightness and colour intensity. Preview the results while framing using Live View Control and enjoy sharing them with friends using the 6.8 cm (2.7”) LCD screen. Combine with a Canon Connect Station and you can easily share your photos and movies with the world on social media sites and online albums like irista, plus enjoy watching them with family and friends on an HD TV. Effortlessly enjoy great shots of friends thanks to Face Detection technology. It detects multiple faces in a single frame making sure they remain in focus and with optimum brightness. Face Detection also ensures natural skin tones even in unusual lighting conditions.')
                    ->setAttributes('[]')
                    ->setIsComplete(true),
                'productSearchEntityTransfer' => (new SpyProductSearchEntityTransfer())
                    ->setFkLocale(static::FK_EN_LOCAL)
                    ->setIsSearchable(1),
            ],
        ];
        $dataSet1[ProductConcreteHydratorStep::PRODUCT_BUNDLE_TRANSFER] = [];

        $dataSet2 = new DataSet();
        $dataSet2[ProductConcreteHydratorStep::KEY_ABSTRACT_SKU] = static::SKU2;
        $dataSet2[ProductConcreteHydratorStep::PRODUCT_CONCRETE_TRANSFER] = (new SpyProductEntityTransfer())
            ->setSku(static::SKU2_CONCRETE)
            ->setIsActive(true)
            ->setAttributes('[]')
            ->setIsQuantitySplittable(true);
        $dataSet2[ProductConcreteHydratorStep::PRODUCT_CONCRETE_LOCALIZED_TRANSFER] = [
            [
                'sku' => static::SKU2_CONCRETE,
                'localizedAttributeTransfer' => (new SpyProductLocalizedAttributesEntityTransfer())
                    ->setFkLocale(static::FK_DE_LOCAL)
                    ->setName('Canon IXUS 160')
                    ->setDescription('Beeindruckende Aufnahmen, ganz einfach Smart Auto ermöglicht die mühelose Aufnahme von fantastischen Fotos und Movies – die Kamera wählt in diesem Modus automatisch die idealen Einstellungen für die jeweilige Aufnahmesituation. Sie müssen nur noch das Motiv anvisieren und auslösen. Ein Druck auf die Hilfe-Taste führt zu leicht verständlichen Erklärungen der Kamerafunktionen. Zahlreiche Kreativfilter laden zum Experimentieren ein und bieten echten Fotospaß. So lässt sich neben vielen anderen Optionen der Verzeichnungseffekt eines Fisheye-Objektivs nachempfinden oder in Fotos und Movies werden die Dinge wie Miniaturmodelle dargestellt.')
                    ->setAttributes('[]')
                    ->setIsComplete(true),
                'productSearchEntityTransfer' => (new SpyProductSearchEntityTransfer())
                    ->setFkLocale(static::FK_DE_LOCAL)
                    ->setIsSearchable(1),
            ],
            [
                'sku' => static::SKU2_CONCRETE,
                'localizedAttributeTransfer' => (new SpyProductLocalizedAttributesEntityTransfer())
                    ->setFkLocale(static::FK_EN_LOCAL)
                    ->setName('Canon IXUS 160')
                    ->setDescription('Add a personal touch Make shots your own with quick and easy control over picture settings such as brightness and colour intensity. Preview the results while framing using Live View Control and enjoy sharing them with friends using the 6.8 cm (2.7”) LCD screen. Combine with a Canon Connect Station and you can easily share your photos and movies with the world on social media sites and online albums like irista, plus enjoy watching them with family and friends on an HD TV. Effortlessly enjoy great shots of friends thanks to Face Detection technology. It detects multiple faces in a single frame making sure they remain in focus and with optimum brightness. Face Detection also ensures natural skin tones even in unusual lighting conditions.')
                    ->setAttributes('[]')
                    ->setIsComplete(true),
                'productSearchEntityTransfer' => (new SpyProductSearchEntityTransfer())
                    ->setFkLocale(static::FK_EN_LOCAL)
                    ->setIsSearchable(1),
            ],
        ];
        $dataSet2[ProductConcreteHydratorStep::PRODUCT_BUNDLE_TRANSFER] = [];

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
        $products = SpyProductQuery::create()->filterBySku_In([static::SKU1_CONCRETE, static::SKU2_CONCRETE])->find();
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
            $dataSetProduct = $dataSets[$productEntity->getSku()][ProductConcreteHydratorStep::PRODUCT_CONCRETE_TRANSFER];
            $this->assertEquals(
                $dataSetProduct->getIsActive(),
                $productEntity->getIsActive()
            );
            $this->assertEquals(
                $dataSetProduct->getIsQuantitySplittable(),
                $productEntity->getIsQuantitySplittable()
            );

            //Localized
            $dataSetLocalizedAttributes = $dataSets[$productEntity->getSku()][ProductConcreteHydratorStep::PRODUCT_CONCRETE_LOCALIZED_TRANSFER];
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
}
