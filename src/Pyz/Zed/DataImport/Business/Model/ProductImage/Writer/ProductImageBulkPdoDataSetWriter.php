<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage\Writer;

use Pyz\Zed\DataImport\Business\Model\ProductImage\ProductImageHydratorStep;
use Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\Propel\PropelConfig;

class ProductImageBulkPdoDataSetWriter extends AbstractProductImageBulkDataSetWriter implements DataSetWriterInterface, ApplicableDatabaseEngineAwareInterface
{
    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return $this->dataImportConfig->getCurrentDatabaseEngine() === PropelConfig::DB_ENGINE_PGSQL;
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
     * @param array<int> $touchedProductImages
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
