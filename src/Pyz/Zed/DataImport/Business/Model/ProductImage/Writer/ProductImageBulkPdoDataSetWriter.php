<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage\Writer;

use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataFormatter;
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
    use DataFormatter;

    const BULK_SIZE = 5000;

    /**
     * @var array
     */
    protected static $productImageSetCollection = [];

    /**
     * @var array
     */
    protected static $productImageCollection = [];

    /**
     * @var array
     */
    protected static $productUniqueImageCollection = [];

    /**
     * @var array
     */
    protected static $productImageToImageSetRelationCollection = [];

    /**
     * @var array
     */
    protected static $persistedProductImageSetCollection = [];

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface
     */
    protected $productImageSql;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    protected $propelExecutor;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface $productImageSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     */
    public function __construct(
        ProductImageSqlInterface $productImageSql,
        PropelExecutorInterface $propelExecutor
    ) {
        $this->productImageSql = $productImageSql;
        $this->propelExecutor = $propelExecutor;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $this->collectProductSetImage($dataSet);
        $this->collectProductImage($dataSet);
        $this->collectProductImageToImageSetRelation($dataSet);

        if (count(static::$productImageSetCollection) >= static::BULK_SIZE) {
            $this->writeEntities();
        }
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->writeEntities();
    }

    /**
     * @return void
     */
    protected function writeEntities(): void
    {
        $this->persistProductImageSetEntities();
        $this->persistProductImageEntities();
        $this->persistProductImageSetRelationEntities();

        DataImporterPublisher::triggerEvents();
        $this->flushMemory();
    }

    /**
     * @return void
     */
    protected function persistProductImageEntities(): void
    {
        $externalUrlLarge = $this->formatPostgresArrayString(
            $this->getCollectionDataByKey(static::$productUniqueImageCollection, ProductImageHydratorStep::KEY_EXTERNAL_URL_LARGE)
        );
        $externalUrlSmall = $this->formatPostgresArrayString(
            $this->getCollectionDataByKey(static::$productUniqueImageCollection, ProductImageHydratorStep::KEY_EXTERNAL_URL_SMALL)
        );

        $sql = $this->productImageSql->createProductImageSQL();
        $parameters = [
            $externalUrlLarge,
            $externalUrlSmall,
        ];

        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @return void
     */
    protected function persistProductImageSetEntities(): void
    {
        $name = $this->formatPostgresArrayString(
            $this->getCollectionDataByKey(static::$productImageSetCollection, ProductImageHydratorStep::KEY_IMAGE_SET_DB_NAME_COLUMN)
        );
        $localeName = $this->formatPostgresArrayString(
            $this->getCollectionDataByKey(static::$productImageSetCollection, ProductImageHydratorStep::KEY_LOCALE)
        );
        $productConcreteSku = $this->formatPostgresArrayString(
            $this->getCollectionDataByKey(static::$productImageSetCollection, ProductImageHydratorStep::KEY_CONCRETE_SKU)
        );
        $productAbstractSku = $this->formatPostgresArrayString(
            $this->getCollectionDataByKey(static::$productImageSetCollection, ProductImageHydratorStep::KEY_ABSTRACT_SKU)
        );
        $fkResourceProductSet = $this->formatPostgresArray(
            $this->getCollectionDataByKey(static::$productImageSetCollection, ProductImageHydratorStep::KEY_IMAGE_SET_FK_RESOURCE_PRODUCT_SET)
        );

        $sql = $this->productImageSql->createProductImageSetSQL();
        $parameters = [
            $name,
            $localeName,
            $productConcreteSku,
            $productAbstractSku,
            $fkResourceProductSet,
        ];
        $result = $this->propelExecutor->execute($sql, $parameters);

        static::$persistedProductImageSetCollection = $result;
        $this->addProductImageSetChangeEvent($result);
    }

    /**
     * @return void
     */
    protected function persistProductImageSetRelationEntities(): void
    {
        $externalUrlLarge = $this->formatPostgresArray(
            $this->getCollectionDataByKey(static::$productImageCollection, ProductImageHydratorStep::KEY_EXTERNAL_URL_LARGE)
        );
        $idProductImageSet = $this->formatPostgresArray(
            $this->getCollectionDataByKey(static::$persistedProductImageSetCollection, ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET)
        );
        $sortOrder = $this->formatPostgresArray(
            $this->getCollectionDataByKey(static::$productImageToImageSetRelationCollection, ProductImageHydratorStep::KEY_SORT_ORDER)
        );

        $sql = $this->productImageSql->createProductImageSetRelationSQL();
        $parameters = [
            $externalUrlLarge,
            $idProductImageSet,
            $sortOrder,
        ];

        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @param array $insertedProductSetImage
     *
     * @return void
     */
    protected function addProductImageSetChangeEvent(array $insertedProductSetImage): void
    {
        foreach ($insertedProductSetImage as $productImageSet) {
            if ($productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT]) {
                DataImporterPublisher::addEvent(
                    ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH,
                    $productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT]
                );
                DataImporterPublisher::addEvent(
                    ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                    $productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT]
                );
            } elseif ($productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT]) {
                DataImporterPublisher::addEvent(
                    ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH,
                    $productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT]
                );
            }
        }
    }

    /**
     * @return void
     */
    protected function flushMemory(): void
    {
        static::$productImageSetCollection = [];
        static::$productImageCollection = [];
        static::$productUniqueImageCollection = [];
        static::$productImageToImageSetRelationCollection = [];
        static::$persistedProductImageSetCollection = [];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductSetImage(DataSetInterface $dataSet): void
    {
        $productImage = $dataSet[ProductImageHydratorStep::PRODUCT_IMAGE_SET_TRANSFER]->modifiedToArray();
        $productImage[ProductImageHydratorStep::KEY_LOCALE] = $dataSet[ProductImageHydratorStep::KEY_LOCALE];
        $productImage[ProductImageHydratorStep::KEY_ABSTRACT_SKU] = $dataSet[ProductImageHydratorStep::KEY_ABSTRACT_SKU];
        $productImage[ProductImageHydratorStep::KEY_CONCRETE_SKU] = $dataSet[ProductImageHydratorStep::KEY_CONCRETE_SKU];
        static::$productImageSetCollection[] = $productImage;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductImage(DataSetInterface $dataSet): void
    {
        $productImage = $dataSet[ProductImageHydratorStep::PRODUCT_IMAGE_TRANSFER]->modifiedToArray();
        static::$productImageCollection[] = $productImage;

        $this->collectProductUniqueImage($productImage);
    }

    /**
     * @param array $productImage
     *
     * @return void
     */
    protected function collectProductUniqueImage($productImage): void
    {
        $uniqueExternalUrlLargeCollection = array_column(
            static::$productUniqueImageCollection,
            ProductImageHydratorStep::KEY_EXTERNAL_URL_LARGE
        );

        if (!in_array($productImage[ProductImageHydratorStep::KEY_EXTERNAL_URL_LARGE], $uniqueExternalUrlLargeCollection)) {
            static::$productUniqueImageCollection[] = $productImage;
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductImageToImageSetRelation(DataSetInterface $dataSet): void
    {
        static::$productImageToImageSetRelationCollection[] = $dataSet[ProductImageHydratorStep::PRODUCT_IMAGE_TO_IMAGE_SET_RELATION_TRANSFER]->modifiedToArray();
    }
}
