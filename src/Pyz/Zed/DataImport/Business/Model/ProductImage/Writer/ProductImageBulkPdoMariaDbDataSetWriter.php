<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage\Writer;

use Pyz\Zed\DataImport\Business\Model\ProductImage\ProductImageHydratorStep;
use Pyz\Zed\DataImport\Business\Model\PropelMariaDbVersionConstraintTrait;
use Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\Propel\PropelConfig;

class ProductImageBulkPdoMariaDbDataSetWriter extends AbstractProductImageBulkDataSetWriter implements DataSetWriterInterface, ApplicableDatabaseEngineAwareInterface
{
    use PropelMariaDbVersionConstraintTrait;

    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return $this->dataImportConfig->getCurrentDatabaseEngine() === PropelConfig::DB_ENGINE_MYSQL
            && $this->checkIsMariaDBSupportsBulkImport($this->propelExecutor);
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

        $rowsCount = count($productImageSetNames);

        $queryParameters = [
            $rowsCount,
            $this->dataFormatter->formatStringList($productImageSetNames, $rowsCount),
            $this->dataFormatter->formatStringList($fkLocaleIds, $rowsCount),
            $this->dataFormatter->formatStringList($fkProductConcreteIds, $rowsCount),
            $this->dataFormatter->formatStringList($fkProductAbstractIds, $rowsCount),
        ];

        $this->propelExecutor->execute(
            $this->productImageSql->createProductImageSetSQL(),
            $queryParameters,
            false
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
            count($externalUrlLargeCollection),
            $this->dataFormatter->formatStringList($externalUrlLargeCollection),
            $this->dataFormatter->formatStringList($externalUrlSmallCollection),
            $this->dataFormatter->formatStringList($productImageKeys),
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
            count($productImageSetNames),
            $this->dataFormatter->formatStringList($productImageSetNames),
            $this->dataFormatter->formatStringList($fkLocaleIds),
            $this->dataFormatter->formatStringList($fkProductConcreteIds),
            $this->dataFormatter->formatStringList($fkProductAbstractIds),
            $this->dataFormatter->formatStringList($sortOrder),
            $this->dataFormatter->formatStringList($productImageKeys),
        ];

        $this->propelExecutor->execute(
            $this->productImageSql->createProductImageSetRelationSQL(),
            $parameters,
            false
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
            count($touchedProductImages),
            $this->dataFormatter->formatStringList($touchedProductImages),
        ];
        $updatedProductImageSets = $this->propelExecutor->execute(
            $this->productImageSql->findProductImageSetsByProductImageIds(),
            $parameters
        );

        $this->addProductImageSetChangeEvent($updatedProductImageSets);
        DataImporterPublisher::triggerEvents();
    }
}
