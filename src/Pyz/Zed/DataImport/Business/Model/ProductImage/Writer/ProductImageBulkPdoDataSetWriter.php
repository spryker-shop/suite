<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage\Writer;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
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
     * @var array
     */
    protected static $productAbstractImageSetCollection = [];

    /**
     * @var array
     */
    protected static $productConcreteImageSetCollection = [];

    /**
     * @var array
     */
    protected static $productAbstractImageCollection = [];

    /**
     * @var array
     */
    protected static $productConcreteImageCollection = [];

    /**
     * @var array
     */
    protected static $productUniqueImageCollection = [];

    /**
     * @var int[]
     */
    protected static $productImageLocaleIds = [];

    /**
     * @var int[]
     */
    protected static $productIds = [];

    /**
     * @var int[]
     */
    protected static $productImageIds = [];

    /**
     * @var array
     */
    protected static $productImageSetIds = [];

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
        $this->collectProductSetImage($dataSet);

        if (count(static::$productAbstractImageCollection) >= ProductImageHydratorStep::BULK_SIZE ||
            count(static::$productConcreteImageCollection) >= ProductImageHydratorStep::BULK_SIZE
        ) {
            $this->flush();
        }
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        if (static::$productAbstractImageCollection === [] && static::$productConcreteImageCollection === []) {
            return;
        }

        $this->persistProductImageEntities();

        $this->prepareProductAbstractImageSetLocaleIds();
        $this->prepareProductAbstractIds();
        $this->persistProductAbstractImageSetEntities();
        $this->prepareAbstractProductImageIds();
        $this->persistProductAbstractImageSetRelationEntities();

        $this->flushProductImageAbstractMemory();

        $this->prepareProductConcreteImageSetLocaleIds();
        $this->prepareProductConcreteIds();
        $this->persistProductConcreteImageSetEntities();
        $this->prepareConcreteProductImageIds();
        $this->persistProductConcreteImageSetRelationEntities();

        $this->flushProductImageConcreteMemory();

        DataImporterPublisher::triggerEvents();
    }

    /**
     * @return void
     */
    protected function persistProductImageEntities(): void
    {
        $externalUrlLargeCollection = $this->dataFormatter->getCollectionDataByKey(static::$productUniqueImageCollection, ProductImageHydratorStep::KEY_EXTERNAL_URL_LARGE);
        $externalUrlSmallCollection = $this->dataFormatter->getCollectionDataByKey(static::$productUniqueImageCollection, ProductImageHydratorStep::KEY_EXTERNAL_URL_SMALL);
        $externalUrlLarge = $this->dataFormatter->formatPostgresArrayString($externalUrlLargeCollection);
        $externalUrlSmall = $this->dataFormatter->formatPostgresArrayString($externalUrlSmallCollection);
        $orderKey = $this->dataFormatter->formatPostgresArray(array_keys($externalUrlLargeCollection));

        $sql = $this->productImageSql->createProductImageSQL();

        $parameters = [
            $externalUrlLarge,
            $externalUrlSmall,
            $orderKey,
        ];

        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @return void
     */
    protected function persistProductAbstractImageSetEntities(): void
    {
        if (!count(static::$productIds)) {
            return;
        }

        $nameCollection = $this->dataFormatter->getCollectionDataByKey(static::$productAbstractImageSetCollection, ProductImageHydratorStep::KEY_IMAGE_SET_DB_NAME_COLUMN);
        $name = $this->dataFormatter->formatPostgresArrayString($nameCollection);

        $idLocale = $this->dataFormatter->formatPostgresArray(
            $this->dataFormatter->getCollectionDataByKey(static::$productImageLocaleIds, ProductImageHydratorStep::KEY_ID_LOCALE)
        );
        $idProductAbstract = $this->dataFormatter->formatPostgresArray(
            $this->dataFormatter->getCollectionDataByKey(static::$productIds, ProductImageHydratorStep::KEY_ID_PRODUCT_ABSTRACT)
        );
        $orderKey = $this->dataFormatter->formatPostgresArray(array_keys($nameCollection));

        $parametersForProductAbstract = [
            $name,
            $idLocale,
            $idProductAbstract,
            $orderKey,
        ];

        $this->persistProductAbstractImageSet($parametersForProductAbstract);
    }

    /**
     * @return void
     */
    protected function persistProductConcreteImageSetEntities(): void
    {
        if (!count(static::$productIds)) {
            return;
        }

        $nameCollection = $this->dataFormatter->getCollectionDataByKey(static::$productConcreteImageSetCollection, ProductImageHydratorStep::KEY_IMAGE_SET_DB_NAME_COLUMN);
        $name = $this->dataFormatter->formatPostgresArrayString($nameCollection);

        $idLocale = $this->dataFormatter->formatPostgresArray(
            $this->dataFormatter->getCollectionDataByKey(static::$productImageLocaleIds, ProductImageHydratorStep::KEY_ID_LOCALE)
        );

        $idProductConcrete = $this->dataFormatter->formatPostgresArray(
            $this->dataFormatter->getCollectionDataByKey(static::$productIds, ProductImageHydratorStep::KEY_ID_PRODUCT)
        );

        $orderKey = $this->dataFormatter->formatPostgresArray(array_keys($nameCollection));

        $parametersForProductConcrete = [
            $name,
            $idLocale,
            $idProductConcrete,
            $orderKey,
        ];

        $this->persistProductConcreteImageSet($parametersForProductConcrete);
    }

    /**
     * @param array $parametersForProductAbstract
     *
     * @return void
     */
    protected function persistProductAbstractImageSet(array $parametersForProductAbstract): void
    {
        $sqlForProductAbstract = $this->productImageSql->createProductImageSetSQL(
            ProductImageHydratorStep::KEY_ID_PRODUCT_ABSTRACT,
            ProductImageHydratorStep::KEY_FK_PRODUCT_ABSTRACT
        );

        $result = $this->propelExecutor->execute($sqlForProductAbstract, $parametersForProductAbstract);

        $this->addProductAbstractImageSetChangeEvent($result);
    }

    /**
     * @param array $parametersForProductConcrete
     *
     * @return void
     */
    protected function persistProductConcreteImageSet(array $parametersForProductConcrete): void
    {
        $sqlForProductConcrete = $this->productImageSql->createProductImageSetSQL(
            ProductImageHydratorStep::KEY_ID_PRODUCT,
            ProductImageHydratorStep::KEY_FK_PRODUCT
        );

        $result = $this->propelExecutor->execute($sqlForProductConcrete, $parametersForProductConcrete);

        $this->addProductConcreteImageSetChangeEvent($result);
    }

    /**
     * @return void
     */
    protected function persistProductAbstractImageSetRelationEntities(): void
    {
        if (!count(static::$productImageIds)) {
            return;
        }

        $productImageCollection = $this->dataFormatter->getCollectionDataByKey(static::$productImageIds, ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE);
        $idProductImage = $this->dataFormatter->formatPostgresArray($productImageCollection);

        $idProductImageSet = $this->dataFormatter->formatPostgresArray(
            $this->dataFormatter->getCollectionDataByKey(static::$productImageSetIds, ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET)
        );
        $sortOrder = $this->dataFormatter->formatPostgresArray(
            $this->dataFormatter->getCollectionDataByKey(static::$productImageSetIds, ProductImageHydratorStep::KEY_SORT_ORDER)
        );
        $orderKey = $this->dataFormatter->formatPostgresArray(array_keys($productImageCollection));

        $sql = $this->productImageSql->createProductImageSetRelationSQL();

        $parameters = [
            $idProductImage,
            $idProductImageSet,
            $sortOrder,
            $orderKey,
        ];

        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @return void
     */
    protected function persistProductConcreteImageSetRelationEntities(): void
    {
        if (!count(static::$productImageIds)) {
            return;
        }

        $productImageCollection = $this->dataFormatter->getCollectionDataByKey(static::$productImageIds, ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE);
        $idProductImage = $this->dataFormatter->formatPostgresArray($productImageCollection);
        $idProductImageSet = $this->dataFormatter->formatPostgresArray(
            $this->dataFormatter->getCollectionDataByKey(static::$productImageSetIds, ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET)
        );
        $sortOrder = $this->dataFormatter->formatPostgresArray(
            $this->dataFormatter->getCollectionDataByKey(static::$productImageSetIds, ProductImageHydratorStep::KEY_SORT_ORDER)
        );
        $orderKey = $this->dataFormatter->formatPostgresArray(array_keys($productImageCollection));

        $sql = $this->productImageSql->createProductImageSetRelationSQL();

        $parameters = [
            $idProductImage,
            $idProductImageSet,
            $sortOrder,
            $orderKey,
        ];

        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @param array $insertedProductSetImage
     *
     * @return void
     */
    protected function addProductAbstractImageSetChangeEvent(array $insertedProductSetImage): void
    {
        foreach ($insertedProductSetImage as $productImageSet) {
            DataImporterPublisher::addEvent(
                ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH,
                $productImageSet[ProductImageHydratorStep::KEY_FK_PRODUCT_ABSTRACT]
            );
            DataImporterPublisher::addEvent(
                ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                $productImageSet[ProductImageHydratorStep::KEY_FK_PRODUCT_ABSTRACT]
            );
            static::$productImageSetIds[] = [
                ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET => $productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET],
                ProductImageHydratorStep::KEY_SORT_ORDER => ProductImageHydratorStep::IMAGE_TO_IMAGE_SET_RELATION_ORDER,
            ];
        }
    }

    /**
     * @param array $insertedProductSetImage
     *
     * @return void
     */
    protected function addProductConcreteImageSetChangeEvent(array $insertedProductSetImage): void
    {
        foreach ($insertedProductSetImage as $productImageSet) {
            DataImporterPublisher::addEvent(
                ProductImageEvents::PRODUCT_IMAGE_PRODUCT_CONCRETE_PUBLISH,
                $productImageSet[ProductImageHydratorStep::KEY_FK_PRODUCT]
            );
            static::$productImageSetIds[] = [
                ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET => $productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET],
                ProductImageHydratorStep::KEY_SORT_ORDER => ProductImageHydratorStep::IMAGE_TO_IMAGE_SET_RELATION_ORDER,
            ];
        }
    }

    /**
     * @return void
     */
    protected function flushProductImageAbstractMemory(): void
    {
        static::$productUniqueImageCollection = [];
        static::$productAbstractImageSetCollection = [];
        static::$productAbstractImageCollection = [];
        static::$productImageLocaleIds = [];
        static::$productIds = [];
        static::$productImageIds = [];
        static::$productImageSetIds = [];
    }

    /**
     * @return void
     */
    protected function flushProductImageConcreteMemory(): void
    {
        static::$productConcreteImageSetCollection = [];
        static::$productConcreteImageCollection = [];
        static::$productImageLocaleIds = [];
        static::$productIds = [];
        static::$productImageIds = [];
        static::$productImageSetIds = [];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductSetImage(DataSetInterface $dataSet): void
    {
        $productImage = $dataSet[ProductImageHydratorStep::DATA_PRODUCT_IMAGE_SET_TRANSFER]->modifiedToArray();
        $productImage[ProductImageHydratorStep::KEY_LOCALE] = $productImage[ProductImageHydratorStep::KEY_SPY_LOCALE][ProductImageHydratorStep::KEY_LOCALE_NAME];
        $productImage[ProductImageHydratorStep::KEY_ABSTRACT_SKU] = $dataSet[ProductImageHydratorStep::KEY_ABSTRACT_SKU];
        $productImage[ProductImageHydratorStep::KEY_CONCRETE_SKU] = $dataSet[ProductImageHydratorStep::KEY_CONCRETE_SKU];

        $isProductAbstract = $productImage[ProductImageHydratorStep::KEY_ABSTRACT_SKU] !== "";
        if ($isProductAbstract) {
            static::$productAbstractImageSetCollection[] = $productImage;
        } else {
            static::$productConcreteImageSetCollection[] = $productImage;
        }

        $this->collectProductImage($dataSet, $isProductAbstract);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param bool $isProductAbstract
     *
     * @return void
     */
    protected function collectProductImage(DataSetInterface $dataSet, bool $isProductAbstract): void
    {
        $productImage = $dataSet[ProductImageHydratorStep::DATA_PRODUCT_IMAGE_TRANSFER]->modifiedToArray();

        if ($isProductAbstract) {
            static::$productAbstractImageCollection[] = $productImage;
        } else {
            static::$productConcreteImageCollection[] = $productImage;
        }

        $this->collectProductUniqueImage($productImage);
    }

    /**
     * @param array $productImage
     *
     * @return void
     */
    protected function collectProductUniqueImage(array $productImage): void
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
     * @return void
     */
    protected function prepareProductAbstractImageSetLocaleIds(): void
    {
        $localeCollection = $this->dataFormatter->getCollectionDataByKey(static::$productAbstractImageSetCollection, ProductImageHydratorStep::KEY_LOCALE);
        $locale = $this->dataFormatter->formatPostgresArray($localeCollection);
        $orderKey = $this->dataFormatter->formatPostgresArray(array_keys($localeCollection));

        $sql = $this->productImageSql->convertLocaleNameToId();

        $parameters = [
            $orderKey,
            $locale,
        ];

        $result = $this->propelExecutor->execute($sql, $parameters);

        foreach ($result as $idLocale) {
            static::$productImageLocaleIds[] = $idLocale;
        }
    }

    /**
     * @return void
     */
    protected function prepareProductConcreteImageSetLocaleIds(): void
    {
        $localeCollection = $this->dataFormatter->getCollectionDataByKey(static::$productConcreteImageSetCollection, ProductImageHydratorStep::KEY_LOCALE);
        $locale = $this->dataFormatter->formatPostgresArray($localeCollection);
        $orderKey = $this->dataFormatter->formatPostgresArray(array_keys($localeCollection));

        $sql = $this->productImageSql->convertLocaleNameToId();

        $parameters = [
            $orderKey,
            $locale,
        ];

        $result = $this->propelExecutor->execute($sql, $parameters);

        foreach ($result as $idLocale) {
            static::$productImageLocaleIds[] = $idLocale;
        }
    }

    /**
     * @return void
     */
    protected function prepareProductAbstractIds(): void
    {
        $productAbstractCollection = $this->dataFormatter->getCollectionDataByKey(static::$productAbstractImageSetCollection, ProductImageHydratorStep::KEY_ABSTRACT_SKU);
        $productAbstractSku = $this->dataFormatter->formatPostgresArray($productAbstractCollection);
        $orderKey = $this->dataFormatter->formatPostgresArray(array_keys($productAbstractCollection));

        $sql = $this->productImageSql->convertProductSkuToId(SpyProductAbstractTableMap::TABLE_NAME, ProductImageHydratorStep::KEY_ID_PRODUCT_ABSTRACT);

        $parameters = [
            $orderKey,
            $productAbstractSku,
        ];

        $result = $this->propelExecutor->execute($sql, $parameters);

        foreach ($result as $idProductAbstract) {
            static::$productIds[] = $idProductAbstract;
        }
    }

    /**
     * @return void
     */
    protected function prepareProductConcreteIds(): void
    {
        $productConcreteCollection = $this->dataFormatter->getCollectionDataByKey(static::$productConcreteImageSetCollection, ProductImageHydratorStep::KEY_CONCRETE_SKU);
        $productConcreteSku = $this->dataFormatter->formatPostgresArray($productConcreteCollection);
        $orderKey = $this->dataFormatter->formatPostgresArray(array_keys($productConcreteCollection));

        $sql = $this->productImageSql->convertProductSkuToId(SpyProductTableMap::TABLE_NAME, ProductImageHydratorStep::KEY_ID_PRODUCT);

        $parameters = [
            $orderKey,
            $productConcreteSku,
        ];

        $result = $this->propelExecutor->execute($sql, $parameters);

        foreach ($result as $idProductConcrete) {
            static::$productIds[] = $idProductConcrete;
        }
    }

    /**
     * @return void
     */
    protected function prepareAbstractProductImageIds(): void
    {
        $productImageNamesCollection = $this->dataFormatter->getCollectionDataByKey(static::$productAbstractImageCollection, ProductImageHydratorStep::KEY_EXTERNAL_URL_LARGE);
        $productImageNames = $this->dataFormatter->formatPostgresArray($productImageNamesCollection);
        $orderKey = $this->dataFormatter->formatPostgresArray(array_keys($productImageNamesCollection));

        $sql = $this->productImageSql->convertImageNameToId();

        $parameters = [
            $orderKey,
            $productImageNames,
        ];

        $result = $this->propelExecutor->execute($sql, $parameters);

        foreach ($result as $idProductImage) {
            static::$productImageIds[] = $idProductImage;
        }
    }

    /**
     * @return void
     */
    protected function prepareConcreteProductImageIds(): void
    {
        $productImageNamesCollection = $this->dataFormatter->getCollectionDataByKey(static::$productConcreteImageCollection, ProductImageHydratorStep::KEY_EXTERNAL_URL_LARGE);
        $productImageNames = $this->dataFormatter->formatPostgresArray($productImageNamesCollection);
        $orderKey = $this->dataFormatter->formatPostgresArray(array_keys($productImageNamesCollection));

        $sql = $this->productImageSql->convertImageNameToId();

        $parameters = [
            $orderKey,
            $productImageNames,
        ];

        $result = $this->propelExecutor->execute($sql, $parameters);

        foreach ($result as $idProductImage) {
            static::$productImageIds[] = $idProductImage;
        }
    }
}
