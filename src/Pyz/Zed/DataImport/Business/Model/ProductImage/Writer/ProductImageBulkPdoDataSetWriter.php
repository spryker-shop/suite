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
    protected static $collectedProductImageSetDataCollection = [];

    /**
     * @var array
     */
    protected static $collectedProductImageDataCollection = [];

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
        $this->collectProductImageSetData($dataSet);
        $this->collectProductImageData($dataSet);

        if (count(static::$collectedProductImageSetDataCollection) >= 2) {
            $this->flush();
        }
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        if (!static::$collectedProductImageSetDataCollection || !static::$collectedProductImageSetDataCollection) {
            return;
        }

        $this->persistProductImageSets();
        $this->persistProductImages();
        $this->persistProductImageSetRelations();

        $this->flushCollectedData();
        DataImporterPublisher::triggerEvents();
    }

    /**
     * @return void
     */
    protected function persistProductImages(): void
    {
        $externalUrlLargeCollection = $this->dataFormatter->getCollectionDataByKey(static::$collectedProductImageDataCollection, ProductImageHydratorStep::KEY_EXTERNAL_URL_LARGE);
        $externalUrlSmallCollection = $this->dataFormatter->getCollectionDataByKey(static::$collectedProductImageDataCollection, ProductImageHydratorStep::KEY_EXTERNAL_URL_SMALL);
        $sql = $this->productImageSql->createProductImageSQL();

        $parameters = [
            $this->dataFormatter->formatPostgresArrayString($externalUrlLargeCollection),
            $this->dataFormatter->formatPostgresArrayString($externalUrlSmallCollection),
        ];

        $result = $this->propelExecutor->execute($sql, $parameters);

        $this->hydrateProductImageDataWithProductImageIds(
            $this->extractDbQueryResultColumn($result, ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE)
        );
    }

    /**
     * @param int[] $productImageIds
     *
     * @return void
     */
    protected function hydrateProductImageDataWithProductImageIds(array $productImageIds): void
    {
        $result = [];

        foreach (static::$collectedProductImageDataCollection as $index => $productImageData) {
            $productImageData[ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE] = $productImageIds[$index] ?? null;
            $result[] = $productImageData;
        }

        static::$collectedProductImageDataCollection = $result;
    }

    /**
     * @return void
     */
    protected function persistProductImageSets()
    {
        $fkProductAbstractIds = $this->dataFormatter->getCollectionDataByKey(
            static::$collectedProductImageSetDataCollection,
            ProductImageHydratorStep::KEY_FK_PRODUCT_ABSTRACT
        );
        $fkProductConcreteIds = $this->dataFormatter->getCollectionDataByKey(
            static::$collectedProductImageSetDataCollection,
            ProductImageHydratorStep::KEY_FK_PRODUCT
        );
        $fkLocaleIds = $this->dataFormatter->getCollectionDataByKey(static::$collectedProductImageSetDataCollection, ProductImageHydratorStep::KEY_IMAGE_SET_FK_LOCALE);
        $imageSetNames = $this->dataFormatter->getCollectionDataByKey(static::$collectedProductImageSetDataCollection, ProductImageHydratorStep::KEY_IMAGE_SET_DB_NAME_COLUMN);

        $queryParameters = [
            $this->dataFormatter->formatPostgresArrayString($imageSetNames),
            $this->dataFormatter->formatPostgresArray($fkLocaleIds),
            $this->dataFormatter->formatPostgresArray($fkProductConcreteIds),
            $this->dataFormatter->formatPostgresArray($fkProductAbstractIds),
            $this->dataFormatter->formatPostgresArray(array_keys(static::$collectedProductImageSetDataCollection)),
        ];

        $sql = $this->productImageSql->createProductImageSetSQL();
        $result = $this->propelExecutor->execute($sql, $queryParameters);

        $this->hydrateProductImageSetDataWithProductImageSetIds(
            $this->extractDbQueryResultColumn($result, ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET)
        );

        $this->addProductImageSetChangeEvent($result);
    }

    /**
     * @param array $productImageSetIds
     *
     * @return void
     */
    protected function hydrateProductImageSetDataWithProductImageSetIds(array $productImageSetIds): void
    {
        $result = [];

        foreach (static::$collectedProductImageSetDataCollection as $index => $productImageSetData) {
            $productImageSetData[ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET] = $productImageSetIds[$index] ?? null;
            $result[] = $productImageSetData;
        }

        static::$collectedProductImageSetDataCollection = $result;
    }

    /**
     * @return void
     */
    protected function persistProductImageSetRelations(): void
    {
        $productImageSetIds = $this->dataFormatter->getCollectionDataByKey(
            static::$collectedProductImageSetDataCollection,
            ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET
        );
        $productImageIds = $this->dataFormatter->getCollectionDataByKey(
            static::$collectedProductImageDataCollection,
            ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE
        );
        $sortOrder = array_keys(static::$collectedProductImageDataCollection);

        $sql = $this->productImageSql->createProductImageSetRelationSQL();

        $parameters = [
            $this->dataFormatter->formatPostgresArray($productImageIds),
            $this->dataFormatter->formatPostgresArray($productImageSetIds),
            $this->dataFormatter->formatPostgresArray($sortOrder),
        ];

        $this->propelExecutor->execute($sql, $parameters);
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
    protected function collectProductImageSetData(DataSetInterface $dataSet): void
    {
        $productImageSetData = $dataSet[ProductImageHydratorStep::DATA_PRODUCT_IMAGE_SET_TRANSFER]->modifiedToArray();

        static::$collectedProductImageSetDataCollection[] = $productImageSetData;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductImageData(DataSetInterface $dataSet): void
    {
        $productImageData = $dataSet[ProductImageHydratorStep::DATA_PRODUCT_IMAGE_TRANSFER]->modifiedToArray();

        static::$collectedProductImageDataCollection[] = $productImageData;
    }

    /**
     * @param array|null $result
     * @param string $key
     *
     * @return array
     */
    protected function extractDbQueryResultColumn(?array $result, string $key): array
    {
        if (!$result) {
            return [];
        }

        return $this->dataFormatter->getCollectionDataByKey($result, $key);
    }

    /**
     * @return void
     */
    protected function flushCollectedData(): void
    {
        static::$collectedProductImageDataCollection = [];
        static::$collectedProductImageSetDataCollection = [];
    }
}
