<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage\Writer;

use Propel\Runtime\Propel;
use Pyz\Zed\DataImport\Business\Model\AbstractBulkPdoWriter\AbstractBulkPdoWriterTrait;
use Pyz\Zed\DataImport\Business\Model\ProductImage\ProductImageHydratorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Business\Model\Writer\FlushInterface;
use Spryker\Zed\DataImport\Business\Model\Writer\WriterInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductImage\Dependency\ProductImageEvents;

class ProductImageBulkPdoWriter extends DataImporterPublisher implements WriterInterface, FlushInterface
{
    use AbstractBulkPdoWriterTrait;

    const BULK_SIZE = 1000;

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
        $this->triggerEvents();
    }

    /**
     * @return void
     */
    protected function writeEntities(): void
    {
        $this->persistProductImageSetEntities();
        $this->persistProductImageEntities();
        $this->persistProductImageSetRelationEntities();

        $this->triggerEvents();
        $this->flushMemory();
    }

    /**
     * @return void
     */
    protected function persistProductImageEntities(): void
    {
        $externalUrlLarge = $this->formatPostgresArrayString(
            array_column(static::$productUniqueImageCollection, ProductImageHydratorStep::KEY_EXTERNAL_URL_LARGE)
        );
        $externalUrlSmall = $this->formatPostgresArrayString(
            array_column(static::$productUniqueImageCollection, ProductImageHydratorStep::KEY_EXTERNAL_URL_SMALL)
        );

        $sql = $this->createProductImageSQL();
        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $externalUrlLarge,
            $externalUrlSmall,
        ]);
    }

    /**
     * @return void
     */
    protected function persistProductImageSetEntities(): void
    {
        $name = $this->formatPostgresArrayString(
            array_column(static::$productImageSetCollection, ProductImageHydratorStep::KEY_IMAGE_SET_DB_NAME_COLUMN)
        );
        $fkLocale = $this->formatPostgresArray(
            array_column(static::$productImageSetCollection, ProductImageHydratorStep::KEY_IMAGE_SET_FK_LOCALE)
        );
        $fkProduct = $this->formatPostgresArray(
            array_column(static::$productImageSetCollection, ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT)
        );
        $fkProductAbstract = $this->formatPostgresArray(
            array_column(static::$productImageSetCollection, ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT)
        );
        $fkResourceProductSet = $this->formatPostgresArray(
            array_column(static::$productImageSetCollection, ProductImageHydratorStep::KEY_IMAGE_SET_FK_RESOURCE_PRODUCT_SET)
        );

        $sql = $this->createProductImageSetSQL();

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $name,
            $fkLocale,
            $fkProduct,
            $fkProductAbstract,
            $fkResourceProductSet,
        ]);

        static::$persistedProductImageSetCollection = $stmt->fetchAll();
        $this->addProductImageSetChangeEvent($stmt->fetchAll());
    }

    /**
     * @return void
     */
    protected function persistProductImageSetRelationEntities(): void
    {
        $externalUrlLarge = $this->formatPostgresArray(
            array_column(static::$productImageCollection, ProductImageHydratorStep::KEY_EXTERNAL_URL_LARGE)
        );
        $idProductImageSet = $this->formatPostgresArray(
            array_column(static::$persistedProductImageSetCollection, ProductImageHydratorStep::KEY_IMAGE_SET_RELATION_ID_PRODUCT_IMAGE_SET)
        );
        $sortOrder = $this->formatPostgresArray(
            array_column(static::$productImageToImageSetRelationCollection, ProductImageHydratorStep::KEY_SORT_ORDER)
        );

        $sql = $this->createProductImageSetRelationSQL();

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $externalUrlLarge,
            $idProductImageSet,
            $sortOrder,
        ]);
    }

    /**
     * @return string
     */
    protected function createProductImageSetSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.name,
      input.fkLocale,
      input.fkProduct,
      input.fkProductAbstract,
      input.fkResourceProductSet,
      id_product_image_set as idProductImageSet
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS name,
             unnest(? :: INTEGER []) AS fkLocale,
             unnest(? :: INTEGER []) AS fkProduct,
             unnest(? :: INTEGER []) AS fkProductAbstract,
             unnest(? :: INTEGER []) AS fkResourceProductSet
         ) input
      LEFT JOIN spy_product_image_set ON
        spy_product_image_set.fk_locale = input.fkLocale AND
        (spy_product_image_set.fk_product_abstract = input.fkProductAbstract OR
        spy_product_image_set.fk_product_abstract is null) AND
        (spy_product_image_set.fk_product = input.fkProduct OR
        spy_product_image_set.fk_product is null)
),
    updated AS (
    UPDATE spy_product_image_set
    SET
      name = records.name,
      fk_locale = records.fkLocale,
      fk_product = records.fkProduct,
      fk_product_abstract = records.fkProductAbstract,
      fk_resource_product_set = records.fkResourceProductSet,
      updated_at = now()
    FROM records
    WHERE
      idProductImageSet is not null AND (
      (records.fkProduct = spy_product_image_set.fk_product OR records.fkProduct is null) AND
      (records.fkProductAbstract = spy_product_image_set.fk_product_abstract OR records.fkProductAbstract is null) AND
      records.fkLocale = spy_product_image_set.fk_locale )
    RETURNING id_product_image_set
  ),
    inserted AS(
    INSERT INTO spy_product_image_set (
      id_product_image_set,
      name,
      fk_locale,
      fk_product,
      fk_product_abstract,
      fk_resource_product_set,
      created_at,
      updated_at
    ) (
      SELECT
        nextval('spy_product_image_set_pk_seq'),
        name,
        fkLocale,
        fkProduct,
        fkProductAbstract,
        fkResourceProductSet,
        now(),
        now()
      FROM records
      WHERE idProductImageSet is null AND (fkProduct is null OR fkProductAbstract is null OR fkLocale is null)
    ) RETURNING id_product_image_set
  )
SELECT updated.id_product_image_set FROM updated UNION ALL SELECT inserted.id_product_image_set FROM inserted;";
        return $sql;
    }

    /**
     * @return string
     */
    protected function createProductImageSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.externalUrlLarge,
      input.externalUrlSmall,
      id_product_image as idProductImage
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS externalUrlLarge,
             unnest(? :: VARCHAR []) AS externalUrlSmall
         ) input
      LEFT JOIN spy_product_image ON spy_product_image.external_url_large = input.externalUrlLarge
),
    updated AS (
    UPDATE spy_product_image
    SET
      external_url_large = records.externalUrlLarge,
      external_url_small = records.externalUrlSmall,
      updated_at = now()
    FROM records
    WHERE records.idProductImage = spy_product_image.id_product_image
    RETURNING id_product_image
  ),
    inserted AS(
    INSERT INTO spy_product_image (
      id_product_image,
      external_url_large,
      external_url_small,
      created_at,
      updated_at
    ) (
      SELECT
        nextval('spy_product_image_pk_seq'),
        externalUrlLarge,
        externalUrlSmall,
        now(),
        now()
      FROM records
      WHERE idProductImage is null
    ) RETURNING id_product_image
  )
SELECT updated.id_product_image FROM updated UNION ALL SELECT inserted.id_product_image FROM inserted;";
        return $sql;
    }

    /**
     * @return string
     */
    protected function createProductImageSetRelationSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.externalUrlLarge,
      input.fkProductImageSet,
      input.sortOrder,
      id_product_image_set_to_product_image as idProductImageRelation,
      id_product_image as fkProductImage
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS externalUrlLarge,
             unnest(? :: INTEGER []) AS fkProductImageSet,
             unnest(? :: INTEGER []) AS sortOrder
         ) input
      INNER JOIN spy_product_image on spy_product_image.external_url_large = input.externalUrlLarge
      LEFT JOIN spy_product_image_set_to_product_image as \"pisr\" ON pisr.fk_product_image = spy_product_image.id_product_image AND
                                                                   pisr.fk_product_image_set = input.fkProductImageSet
),
    updated AS (
    UPDATE spy_product_image_set_to_product_image
    SET
      fk_product_image = records.fkProductImage,
      fk_product_image_set = records.fkProductImageSet,
      sort_order = sortOrder
    FROM records
    WHERE records.fkProductImage = spy_product_image_set_to_product_image.fk_product_image AND
          records.fkProductImageSet = spy_product_image_set_to_product_image.fk_product_image_set
    RETURNING id_product_image_set_to_product_image, sortOrder
  ),
    inserted AS(
    INSERT INTO spy_product_image_set_to_product_image (
      id_product_image_set_to_product_image,
      fk_product_image,
      fk_product_image_set,
      sort_order
    ) (
      SELECT
        nextval('spy_product_image_set_to_product_image_pk_seq'),
        fkProductImage,
        fkProductImageSet,
        sortOrder
      FROM records
      WHERE idProductImageRelation is null
    ) RETURNING id_product_image_set_to_product_image, sort_order
  )
SELECT updated.id_product_image_set_to_product_image, updated.sortOrder FROM updated
UNION ALL SELECT inserted.id_product_image_set_to_product_image, inserted.sort_order FROM inserted;";
        return $sql;
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
                $this->addEvent(
                    ProductImageEvents::PRODUCT_IMAGE_PRODUCT_ABSTRACT_PUBLISH,
                    $productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT]
                );
                $this->addEvent(
                    ProductEvents::PRODUCT_ABSTRACT_PUBLISH,
                    $productImageSet[ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT]
                );
            } elseif ($productImageSet->getFkProduct()) {
                $this->addEvent(
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
        static::$productImageSetCollection[] = $dataSet[ProductImageHydratorStep::PRODUCT_IMAGE_SET_TRANSFER]->modifiedToArray();
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
