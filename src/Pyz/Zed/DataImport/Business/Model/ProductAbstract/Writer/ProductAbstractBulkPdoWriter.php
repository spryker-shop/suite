<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer;

use Propel\Runtime\Propel;
use Pyz\Zed\DataImport\Business\Model\AbstractBulkPdoWriter\AbstractBulkPdoWriterTrait;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Business\Model\Writer\FlushInterface;
use Spryker\Zed\DataImport\Business\Model\Writer\WriterInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\Url\Dependency\UrlEvents;

class ProductAbstractBulkPdoWriter extends DataImporterPublisher implements WriterInterface, FlushInterface
{
    use AbstractBulkPdoWriterTrait;

    /**
     * @var array
     */
    protected static $productAbstractCollection = [];

    /**
     * @var array
     */
    protected static $productAbstractLocalizedAttributesCollection = [];

    /**
     * @var array
     */
    protected static $productCategoryCollection = [];

    /**
     * @var array
     */
    protected static $productUrlCollection = [];

    /**
     * @var array
     */
    protected static $productAbstractUpdated = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $this->prepareProductAbstractionCollection($dataSet);
        $this->prepareProductAbstractLocalizedAttributesCollection($dataSet);
        $this->prepareProductCategoryCollection($dataSet);
        $this->prepareProductUrlCollection($dataSet);

        if (count(static::$productAbstractCollection) >= ProductAbstractHydratorStep::BULK_SIZE) {
            $this->flush();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function prepareProductAbstractionCollection(DataSetInterface $dataSet): void
    {
        static::$productAbstractCollection[] = $dataSet[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_TRANSFER]->modifiedToArray();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function prepareProductAbstractLocalizedAttributesCollection(DataSetInterface $dataSet): void
    {
        foreach ($dataSet[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_LOCALIZED_TRANSFER] as $productAbstractLocalizedTransfer) {
            $localizedAttributeArray = $productAbstractLocalizedTransfer['localizedAttributeTransfer']->modifiedToArray();
            $localizedAttributeArray['abstract_sku'] = $productAbstractLocalizedTransfer['abstract_sku'];
            $localizedAttributeArray['meta_description'] = str_replace('"', '', $localizedAttributeArray['meta_description']);
            ;
            $localizedAttributeArray['description'] = str_replace('"', '', $localizedAttributeArray['description']);
            $localizedAttributeArray = $productAbstractLocalizedTransfer[ProductAbstractHydratorStep::KEY_PRODUCT_ABSTRACT_LOCALIZED_TRANSFER]->modifiedToArray();
            $localizedAttributeArray[ProductAbstractHydratorStep::KEY_ABSTRACT_SKU] = $productAbstractLocalizedTransfer[ProductAbstractHydratorStep::KEY_ABSTRACT_SKU];
            $localizedAttributeArray[ProductAbstractHydratorStep::KEY_META_DESCRIPTION] = str_replace('"', '', $localizedAttributeArray[ProductAbstractHydratorStep::KEY_META_DESCRIPTION]);
            $localizedAttributeArray[ProductAbstractHydratorStep::KEY_DESCRIPTION] = str_replace('"', '', $localizedAttributeArray[ProductAbstractHydratorStep::KEY_DESCRIPTION]);
            static::$productAbstractLocalizedAttributesCollection[] = $localizedAttributeArray;
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function prepareProductCategoryCollection(DataSetInterface $dataSet): void
    {
        foreach ($dataSet[ProductAbstractHydratorStep::PRODUCT_CATEGORY_TRANSFER] as $productCategoryTransfer) {
            $productCategoryArray = $productCategoryTransfer[ProductAbstractHydratorStep::KEY_PRODUCT_CATEGORY_TRANSFER]->modifiedToArray();
            $productCategoryArray[ProductAbstractHydratorStep::KEY_ABSTRACT_SKU] = $productCategoryTransfer[ProductAbstractHydratorStep::KEY_ABSTRACT_SKU];
            static::$productCategoryCollection[] = $productCategoryArray;
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function prepareProductUrlCollection(DataSetInterface $dataSet): void
    {
        foreach ($dataSet[ProductAbstractHydratorStep::PRODUCT_URL_TRANSFER] as $productUrlTransfer) {
            $productUrlArray = $productUrlTransfer[ProductAbstractHydratorStep::KEY_PRODUCT_URL_TRASNFER]->modifiedToArray();
            $productUrlArray[ProductAbstractHydratorStep::KEY_ABSTRACT_SKU] = $productUrlTransfer[ProductAbstractHydratorStep::KEY_ABSTRACT_SKU];
            static::$productUrlCollection[] = $productUrlArray;
        }
    }

    /**
     * @return void
     */
    protected function persistAbstractProductEntities(): void
    {
        if (!empty(static::$productAbstractCollection)) {
            $abstractSkus = $this->formatPostgresArrayString(array_column(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_SKU));
            $attributes = $this->formatPostgresArrayFromJson(array_column(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_ATTRIBUTES));
            $fkTaxSets = $this->formatPostgresArray(array_column(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_FK_TAX_SET));
            $colorCode = $this->formatPostgresArrayString(array_column(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_COLOR_CODE));
            $newFrom = $this->formatPostgresArrayString(array_column(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_NEW_FROM));
            $newTo = $this->formatPostgresArrayString(array_column(static::$productAbstractCollection, ProductAbstractHydratorStep::KEY_NEW_TO));

            $sql = $this->createAbstractProductSQL();

            $con = Propel::getConnection();
            $stmt = $con->prepare($sql);
            $stmt->execute([
                $abstractSkus,
                $attributes,
                $fkTaxSets,
                $colorCode,
                $newFrom,
                $newTo,
            ]);
            $con = Propel::getConnection();
            $stmt = $con->prepare($sql);
            $stmt->execute([
                $abstractSkus,
                $attributes,
                $fkTaxSets,
                $colorCode,
                $newFrom,
                $newTo,
            ]);

            $result = $stmt->fetchAll();

            foreach ($result as $columns) {
                static::$productAbstractUpdated[] = $columns[ProductAbstractHydratorStep::KEY_ID_PRODUCT_ABSTRACT];
            }
        }
    }

    /**
     * @return void
     */
    protected function persistAbstractProductLocalizedAttributesEntities(): void
    {
        if (!empty(static::$productAbstractLocalizedAttributesCollection)) {
            $abstractSkus = $this->formatPostgresArrayString(array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_ABSTRACT_SKU));
            $idLocale = $this->formatPostgresArray(array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_FK_LOCALE));
            $name = $this->formatPostgresArrayString(array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_NAME));
            $description = $this->formatPostgresArrayString(array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_DESCRIPTION));
            $metaTitle = $this->formatPostgresArrayString(array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_META_TITLE));
            $metaDescription = $this->formatPostgresArrayString(array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_META_DESCRIPTION));
            $metaKeywords = $this->formatPostgresArrayString(array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_META_KEYWORDS));
            $attributes = $this->formatPostgresArrayFromJson(array_column(static::$productAbstractLocalizedAttributesCollection, ProductAbstractHydratorStep::KEY_ATTRIBUTES));

            $sql = $this->createAbstractProductLocalizedAttributesSQL();

            $con = Propel::getConnection();
            $stmt = $con->prepare($sql);
            $stmt->execute([
                $abstractSkus,
                $name,
                $description,
                $metaTitle,
                $metaDescription,
                $metaKeywords,
                $idLocale,
                $attributes,
            ]);
        }
        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $abstractSkus,
            $name,
            $description,
            $metaTitle,
            $metaDescription,
            $metaKeywords,
            $idLocale,
            $attributes,
        ]);
    }

    /**
     * @return void
     */
    protected function persistAbstractProductCategoryEntities(): void
    {
        if (!empty(static::$productCategoryCollection)) {
            $abstractSkus = $this->formatPostgresArrayString(array_column(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_ABSTRACT_SKU));
            $productOrder = $this->formatPostgresArrayString(array_column(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_PRODUCT_ORDER));
            $idCategory = $this->formatPostgresArrayString(array_column(static::$productCategoryCollection, ProductAbstractHydratorStep::KEY_FK_CATEGORY));

            $sql = $this->createAbstractProductCategoriesSQL();

            $con = Propel::getConnection();
            $stmt = $con->prepare($sql);
            $stmt->execute([
                $abstractSkus,
                $productOrder,
                $idCategory,
            ]);

            $result = $stmt->fetchAll();

            foreach ($result as $columns) {
                $this->addEvent(ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH, $columns[ProductAbstractHydratorStep::KEY_ID_PRODUCT_ABSTRACT]);
                $this->addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $columns[ProductAbstractHydratorStep::KEY_ID_PRODUCT_ABSTRACT]);
            }
        }
    }

    /**
     * @return void
     */
    protected function persistAbstractProductUrlEntities(): void
    {
        if (!empty(static::$productUrlCollection)) {
            $abstractSkus = $this->formatPostgresArrayString(array_column(static::$productUrlCollection, ProductAbstractHydratorStep::KEY_ABSTRACT_SKU));
            $idLocale = $this->formatPostgresArray(array_column(static::$productUrlCollection, ProductAbstractHydratorStep::KEY_FK_LOCALE));
            $url = $this->formatPostgresArrayString(array_column(static::$productUrlCollection, ProductAbstractHydratorStep::KEY_URL));

            $sql = $this->createAbstractProductUrlsSQL();

            $con = Propel::getConnection();
            $stmt = $con->prepare($sql);

            $stmt->execute([
                $abstractSkus,
                $idLocale,
                $url,
            ]);

            $result = $stmt->fetchAll();

            foreach ($result as $columns) {
                $this->addEvent(UrlEvents::URL_PUBLISH, $columns[ProductAbstractHydratorStep::KEY_ID_URL]);
            }
        }
    }

    /**
     * @return string
     */
    protected function createAbstractProductSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.abstract_sku,
      input.attributes,
      input.fkTaxSet,
      input.colorCode,
      input.newFrom,
      input.newTo,
      id_product_abstract as idProductAbstract
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS abstract_sku,
             json_array_elements(?) AS attributes,
             unnest(?::INTEGER[]) AS fkTaxSet,
             unnest(?::VARCHAR[]) AS colorCode,
             unnest(?::TIMESTAMP[]) AS newFrom,
             unnest(?::TIMESTAMP[]) AS newTo
         ) input    
      LEFT JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
),
    updated AS (
    UPDATE spy_product_abstract
    SET
      sku = records.abstract_sku,
      attributes = records.attributes,
      updated_at = now(),
      fk_tax_set = records.fkTaxSet,
      color_code = records.colorCode,
      new_from = records.newFrom,
      new_to = records.newTo
    FROM records
    WHERE records.abstract_sku = spy_product_abstract.sku
    RETURNING id_product_abstract,sku
  ),
    inserted AS(
    INSERT INTO spy_product_abstract (
      id_product_abstract,
      sku,
      attributes,
      fk_tax_set,
      color_code,
      new_from,
      new_to
    ) (
      SELECT
        nextval('spy_product_abstract_pk_seq'),
        abstract_sku,
        attributes,
        fkTaxSet,
        colorCode,
        newFrom,
        newTo
    FROM records
    WHERE idProductAbstract is null
  ) RETURNING id_product_abstract,sku
)
SELECT updated.id_product_abstract,sku FROM updated UNION ALL SELECT inserted.id_product_abstract,sku FROM inserted;";

        return $sql;
    }

    /**
     * @return string
     */
    protected function createAbstractProductLocalizedAttributesSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.abstract_sku,
      input.name,
      input.description,
      input.metaTitle,
      input.metaDescription,
      input.metaKeywords,
      input.idLocale,
      input.attributes,
      id_product_abstract,
      id_abstract_attributes
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS abstract_sku,
             unnest(? :: VARCHAR []) AS name,
             unnest(? :: TEXT []) AS description,
             unnest(? :: VARCHAR []) AS metaTitle,
             unnest(? :: TEXT []) AS metaDescription,
             unnest(? :: VARCHAR []) AS metaKeywords,
             unnest(? :: INTEGER []) AS idLocale,
             json_array_elements(?) AS attributes
         ) input
      INNER JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
      LEFT JOIN spy_product_abstract_localized_attributes ON (spy_product_abstract_localized_attributes.fk_product_abstract = id_product_abstract and spy_product_abstract_localized_attributes.fk_locale = input.idLocale)
),
    updated AS (
    UPDATE spy_product_abstract_localized_attributes
    SET
      updated_at = now(),
      name = records.name,
      description = records.description,
      meta_title = records.metaTitle,
      meta_description = records.metaDescription,
      meta_keywords = records.metaKeywords,
      attributes = records.attributes
    FROM records
    WHERE records.id_product_abstract = spy_product_abstract_localized_attributes.fk_product_abstract and spy_product_abstract_localized_attributes.fk_locale = records.idLocale
  ),
    inserted AS(
    INSERT INTO spy_product_abstract_localized_attributes (
      id_abstract_attributes,
      name,
      description,
      meta_title,
      meta_description,
      meta_keywords,
      attributes,
      fk_locale,
      fk_product_abstract
    ) (
      SELECT
        nextval('spy_product_abstract_localized_attributes_pk_seq'),
        name,
        description,
        metaTitle,
        metaDescription,
        metaKeywords,
        attributes,
        idLocale,
        id_product_abstract
      FROM records
      WHERE id_abstract_attributes is null
    )
  )
SELECT 1;";

        return $sql;
    }

    /**
     * @return string
     */
    protected function createAbstractProductCategoriesSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
        input.abstract_sku,
        input.productOrder,
        input.IdCategory,
        id_product_category,
        id_product_abstract
    FROM (
        SELECT
         unnest(? :: VARCHAR []) AS abstract_sku,
         unnest(? :: INTEGER []) AS productOrder,
         unnest(? :: INTEGER []) AS IdCategory
     ) input
      INNER JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
      LEFT JOIN spy_product_category ON (spy_product_category.fk_product_abstract = id_product_abstract and spy_product_category.fk_category = input.IdCategory)
),
    updated AS (
        UPDATE spy_product_category
        SET
          product_order = records.productOrder,
          fk_category = records.idCategory
        FROM records
    WHERE records.id_product_abstract = spy_product_category.fk_product_abstract and spy_product_category.fk_category = records.idCategory
    RETURNING fk_product_abstract as id_product_abstract
  ),
    inserted AS(
        INSERT INTO spy_product_category (
          id_product_category,
          product_order,
          fk_category,
          fk_product_abstract
        ) (
          SELECT
            nextval('spy_product_category_pk_seq'),
            productOrder,
            IdCategory,
            id_product_abstract
          FROM records
          WHERE id_product_category is null
        ) RETURNING fk_product_abstract as id_product_abstract
      )
SELECT updated.id_product_abstract FROM updated UNION ALL SELECT inserted.id_product_abstract FROM inserted";

        return $sql;
    }

    /**
     * @return string
     */
    protected function createAbstractProductUrlsSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.abstract_sku,
      input.idLocale,
      input.url,
      id_url as idUrl,
      id_product_abstract
    FROM (
           SELECT
             unnest(?::VARCHAR []) AS abstract_sku,
             unnest(?::INTEGER []) AS idLocale,
             unnest(?::VARCHAR []) AS url
         ) input
         INNER JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
         LEFT JOIN spy_url ON (spy_url.fk_resource_product_abstract = id_product_abstract and spy_url.fk_locale = input.idLocale)
    ),
    updated AS (
        UPDATE spy_url
        SET
          url = records.url
        FROM records
        WHERE records.id_product_abstract = spy_url.fk_resource_product_abstract and records.idLocale = spy_url.fk_locale
        RETURNING id_url,id_product_abstract
  ),
    inserted AS(
        INSERT INTO spy_url (
          id_url,
          fk_resource_product_abstract,
          fk_locale,
          url
        ) (
          SELECT
            nextval('spy_url_pk_seq'),
            id_product_abstract,
            idLocale,
            url
        FROM records
        WHERE idUrl is null
  ) RETURNING id_url,fk_resource_product_abstract as id_product_abstract
)
SELECT updated.id_url,id_product_abstract FROM updated UNION ALL SELECT inserted.id_url,id_product_abstract FROM inserted;
";

        return $sql;
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->persistAbstractProductEntities();
        $this->persistAbstractProductLocalizedAttributesEntities();
        $this->persistAbstractProductCategoryEntities();
        $this->persistAbstractProductUrlEntities();

        foreach (static::$productAbstractUpdated as $abstractProductId) {
            $this->addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $abstractProductId);
        }

        $this->triggerEvents();
        $this->flushMemory();
    }

    /**
     * @return void
     */
    protected function flushMemory(): void
    {
        static::$productAbstractCollection = [];
        static::$productAbstractLocalizedAttributesCollection = [];
        static::$productCategoryCollection = [];
        static::$productUrlCollection = [];
        static::$productAbstractUpdated = [];
    }
}
