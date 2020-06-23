<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage\Writer;

use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\ProductImage\ProductImageHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;

class ProductImageBulkPdoDataSetWriter implements DataSetWriterInterface
{
    protected const COLUMN_EXTERNAL_URL_LARGE = ProductImageHydratorStep::COLUMN_EXTERNAL_URL_LARGE;
    protected const COLUMN_EXTERNAL_URL_SMALL = ProductImageHydratorStep::COLUMN_EXTERNAL_URL_SMALL;
    protected const COLUMN_PRODUCT_IMAGE_KEY = ProductImageHydratorStep::COLUMN_PRODUCT_IMAGE_KEY;
    protected const COLUMN_SORT_ORDER = ProductImageHydratorStep::COLUMN_SORT_ORDER;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface
     */
    protected $productImageSql;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    protected $propelExecutor;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface
     */
    protected $dataFormatter;

    /**
     * @var array
     */
    protected static $productImageDataCollection = [];

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface $productImageSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     * @param \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface $dataFormatter
     */
    public function __construct(
        ProductImageSqlInterface $productImageSql,
        PropelExecutorInterface $propelExecutor,
        DataImportDataFormatterInterface $dataFormatter
    ) {
        $this->productImageSql = $productImageSql;
        $this->propelExecutor = $propelExecutor;
        $this->dataFormatter = $dataFormatter;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $this->collectProductImageData($dataSet);

        if (count(static::$productImageDataCollection) >= ProductImageHydratorStep::BULK_SIZE) {
            $this->flush();
        }
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        if (!static::$productImageDataCollection) {
            return;
        }

        $this->persistProductImageSets();
        $touchedProductImages = $this->persistProductImages();
        $this->persistProductImageSetRelations();

        $this->flushCollectedData();
        $this->triggerEventsForUpdatedImageSets($touchedProductImages);
    }

    /**
     * @return void
     */
    protected function persistProductImageSets(): void
    {
        $productImageSetNames = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, ProductImageHydratorStep::KEY_IMAGE_SET_DB_NAME_COLUMN);
        $fkLocaleIds = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, ProductImageHydratorStep::KEY_IMAGE_SET_FK_LOCALE);
        $fkProductAbstractIds = $this->dataFormatter->getCollectionDataByKey(
            static::$productImageDataCollection,
            ProductImageHydratorStep::KEY_FK_PRODUCT_ABSTRACT
        );
        $fkProductConcreteIds = $this->dataFormatter->getCollectionDataByKey(
            static::$productImageDataCollection,
            ProductImageHydratorStep::KEY_FK_PRODUCT
        );

        $queryParameters = [
            $this->dataFormatter->formatPostgresArrayString($productImageSetNames),
            $this->dataFormatter->formatPostgresArray($fkLocaleIds),
            $this->dataFormatter->formatPostgresArray($fkProductConcreteIds),
            $this->dataFormatter->formatPostgresArray($fkProductAbstractIds),
        ];

        $this->propelExecutor->execute(
            $this->productImageSql->createProductImageSetSQL(),
            $queryParameters
        );
    }

    /**
     * @return array
     */
    protected function persistProductImages(): array
    {
        $externalUrlLargeCollection = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, static::COLUMN_EXTERNAL_URL_LARGE);
        $externalUrlSmallCollection = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, static::COLUMN_EXTERNAL_URL_SMALL);
        $productImageKeys = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, static::COLUMN_PRODUCT_IMAGE_KEY);

        $parameters = [
            $this->dataFormatter->formatPostgresArrayString($externalUrlLargeCollection),
            $this->dataFormatter->formatPostgresArrayString($externalUrlSmallCollection),
            $this->dataFormatter->formatPostgresArrayString($productImageKeys),
        ];

        $result = $this->propelExecutor->execute(
            $this->productImageSql->createOrUpdateProductImageSQL(),
            $parameters
        );

        return $this->dataFormatter->getCollectionDataByKey($result, ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE);
    }

    /**
     * @return void
     */
    protected function persistProductImageSetRelations(): void
    {
        $productImageSetNames = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, ProductImageHydratorStep::KEY_IMAGE_SET_DB_NAME_COLUMN);
        $fkLocaleIds = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, ProductImageHydratorStep::KEY_IMAGE_SET_FK_LOCALE);
        $fkProductConcreteIds = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, ProductImageHydratorStep::KEY_FK_PRODUCT);
        $fkProductAbstractIds = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, ProductImageHydratorStep::KEY_FK_PRODUCT_ABSTRACT);
        $sortOrder = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, static::COLUMN_SORT_ORDER);
        $productImageKeys = $this->dataFormatter->getCollectionDataByKey(static::$productImageDataCollection, static::COLUMN_PRODUCT_IMAGE_KEY);

        $parameters = [
            $this->dataFormatter->formatPostgresArrayString($productImageSetNames),
            $this->dataFormatter->formatPostgresArray($fkLocaleIds),
            $this->dataFormatter->formatPostgresArray($fkProductConcreteIds),
            $this->dataFormatter->formatPostgresArray($fkProductAbstractIds),
            $this->dataFormatter->formatPostgresArray($sortOrder),
            $this->dataFormatter->formatPostgresArrayString($productImageKeys),
        ];

        $this->propelExecutor->execute(
            $this->productImageSql->createProductImageSetRelationSQL(),
            $parameters
        );
    }

    /**
     * @param array $touchedProductSetImages
     *
     * @return void
     */
    protected function addProductImageSetChangeEvent(array $touchedProductSetImages): void
    {
        foreach ($touchedProductSetImages as $productImageSet) {
            if (!empty($productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT])) {
                DataImporterPublisher::addEvent(
                    ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH,
                    $productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT]
                );
                DataImporterPublisher::addEvent(
                    ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                    $productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT]
                );
            } elseif (!empty($productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT])) {
                DataImporterPublisher::addEvent(
                    ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH,
                    $productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT]
                );
            }
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductImageData(DataSetInterface $dataSet): void
    {
        $productImageData = $dataSet[ProductImageHydratorStep::DATA_PRODUCT_IMAGE_SET_TRANSFER]->modifiedToArray();
        $productImageData = array_merge($productImageData, $dataSet[ProductImageHydratorStep::DATA_PRODUCT_IMAGE_TRANSFER]->modifiedToArray());
        $productImageData[static::COLUMN_SORT_ORDER] = $dataSet[static::COLUMN_SORT_ORDER];
        $productImageData[static::COLUMN_PRODUCT_IMAGE_KEY] = $dataSet[static::COLUMN_PRODUCT_IMAGE_KEY];

        static::$productImageDataCollection[] = $productImageData;
    }

    /**
     * @return void
     */
    protected function flushCollectedData(): void
    {
        static::$productImageDataCollection = [];
    }

    /**
     * @param int[] $touchedProductImages
     *
     * @return void
     */
    protected function triggerEventsForUpdatedImageSets(array $touchedProductImages): void
    {
        $parameters = [
            $this->dataFormatter->formatPostgresArray($touchedProductImages),
        ];
        $updatedProductImageSets = $this->propelExecutor->execute(
            $this->productImageSql->findProductImageSetsByProductImageIds(),
            $parameters
        );

        $this->addProductImageSetChangeEvent($updatedProductImageSets);
        DataImporterPublisher::triggerEvents();
    }
}
