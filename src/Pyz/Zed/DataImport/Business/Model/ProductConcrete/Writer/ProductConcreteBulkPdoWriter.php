<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer;

use Propel\Runtime\Propel;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteHydratorStep;
use Pyz\Zed\DataImport\Business\Model\AbstractBulkPdoWriter\AbstractBulkPdoWriterTrait;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Business\Model\Writer\FlushInterface;
use Spryker\Zed\DataImport\Business\Model\Writer\WriterInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;

class ProductConcreteBulkPdoWriter extends DataImporterPublisher implements WriterInterface, FlushInterface
{
    use AbstractBulkPdoWriterTrait;

    const BULK_SIZE = 100;

    /**
     * @var array
     */
    protected static $productConcreteCollection = [];

    /**
     * @var array
     */
    protected static $productLocalizedAttributesCollection = [];

    /**
     * @var array
     */
    protected static $productBundleCollection = [];

    /**
     * @var array
     */
    protected static $productSearchCollection = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet)
    {
        $this->collectProductConcrete($dataSet);
        $this->collectProductConcreteLocalizedAttributes($dataSet);
        $this->collectProductConcreteBundle($dataSet);

        if (count(static::$productConcreteCollection) >= static::BULK_SIZE) {
            $this->writeEntities();
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->writeEntities();
    }

    /**
     * @return void
     */
    protected function writeEntities()
    {
        $this->persistConcreteProductEntities();
        $this->persistConcreteProductLocalizedAttributesEntities();
        $this->persistConcreteProductSearchEntities();
        $this->persistConcreteProductBundleEntities();
        $this->triggerEvents();
        $this->flushMemory();
    }

    /**
     * @return void
     */
    protected function persistConcreteProductEntities()
    {
        $sku = $this->formatPostgresArrayString(
            array_column(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_SKU)
        );
        $attributes = $this->formatPostgresArrayFromJson(
            array_column(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_ATTRIBUTES)
        );
        $discount = $this->formatPostgresArray(
            array_column(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_DISCOUNT)
        );
        $warehouses = $this->formatPostgresArrayString(
            array_column(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_WAREHOUSES)
        );
        $isActive = $this->formatPostgresArray(
            array_column(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_IS_ACTIVE)
        );
        $fkProductAbstract = $this->formatPostgresArray(
            array_column(static::$productConcreteCollection, ProductConcreteHydratorStep::KEY_FK_PRODUCT_ABSTRACT)
        );

        $sql = $this->createConcreteProductSQL();

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $discount,
            $warehouses,
            $sku,
            $isActive,
            $attributes,
            $fkProductAbstract,
        ]);

        $this->addProductConcreteChangeEvent($stmt->fetchAll());
    }

    /**
     * @param array $result
     *
     * @return void
     */
    protected function addProductConcreteChangeEvent($result)
    {
        foreach ($result as $columns) {
            $this->addEvent(
                ProductEvents::PRODUCT_CONCRETE_PUBLISH,
                $columns[ProductConcreteHydratorStep::KEY_ID_PRODUCT]
            );
        }
    }

    /**
     * @return void
     */
    protected function persistConcreteProductLocalizedAttributesEntities()
    {
        if (!empty(static::$productLocalizedAttributesCollection)) {
            $spyProduct = array_column(
                static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_SPY_PRODUCT
            );
            $sku = $this->formatPostgresArrayString(
                array_column($spyProduct, ProductConcreteHydratorStep::KEY_SKU)
            );
            $idLocale = $this->formatPostgresArray(
                array_column(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_FK_LOCALE)
            );
            $name = $this->formatPostgresArrayString(
                array_column(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_NAME)
            );
            $isComplete = $this->formatPostgresArray(
                array_column(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_IS_COMPLETE)
            );
            $description = $this->formatPostgresArrayString(
                array_column(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_DESCRIPTION)
            );
            $attributes = $this->formatPostgresArrayFromJson(
                array_column(static::$productLocalizedAttributesCollection, ProductConcreteHydratorStep::KEY_ATTRIBUTES)
            );

            $sql = $this->createConcreteProductLocalizedAttributesSQL();

            $con = Propel::getConnection();
            $stmt = $con->prepare($sql);
            $stmt->execute([
                $sku,
                $name,
                $description,
                $attributes,
                $isComplete,
                $idLocale,
            ]);
        }
    }

    /**
     * @return void
     */
    protected function persistConcreteProductSearchEntities()
    {
        if (!empty(static::$productSearchCollection)) {
            $idLocale = $this->formatPostgresArray(
                array_column(static::$productSearchCollection, ProductConcreteHydratorStep::KEY_FK_LOCALE)
            );
            $isSearchable = $this->formatPostgresArray(
                array_column(static::$productSearchCollection, ProductConcreteHydratorStep::KEY_IS_SEARCHABLE)
            );
            $sku = $this->formatPostgresArray(
                array_column(static::$productSearchCollection, ProductConcreteHydratorStep::KEY_SKU)
            );

            $sql = $this->createConcreteProductSearchSQL();

            $con = Propel::getConnection();
            $stmt = $con->prepare($sql);
            $stmt->execute([
                $idLocale,
                $sku,
                $isSearchable,
            ]);
        }
    }

    /**
     * @return void
     */
    protected function persistConcreteProductBundleEntities()
    {
        if (!empty(static::$productBundleCollection)) {
            $sku = $this->formatPostgresArray(
                array_column(static::$productBundleCollection, ProductConcreteHydratorStep::KEY_SKU)
            );
            $fkBundledProduct = $this->formatPostgresArray(
                array_column(static::$productBundleCollection, ProductConcreteHydratorStep::KEY_FK_BUNDLED_PRODUCT)
            );
            $quantity = $this->formatPostgresArray(
                array_column(static::$productBundleCollection, ProductConcreteHydratorStep::KEY_QUANTITY)
            );

            $sql = $this->createConcreteProductBundleSQL();

            $con = Propel::getConnection();
            $stmt = $con->prepare($sql);
            $stmt->execute([
                $fkBundledProduct,
                $sku,
                $quantity,
            ]);
        }
    }

    /**
     * @return string
     */
    protected function createConcreteProductSQL()
    {
        $sql = "WITH records AS (
    SELECT
      input.discount,
      input.warehouses,
      input.concrete_sku,
      input.is_active,
      input.attributes,
      input.fk_product_abstract,
      id_product as idProduct
    FROM (
           SELECT
             unnest(? :: INTEGER []) AS discount,
             unnest(? :: TEXT []) AS warehouses,
             unnest(? :: VARCHAR []) AS concrete_sku,
             unnest(? :: BOOLEAN []) AS is_active,
             json_array_elements(?) AS attributes,
             unnest(? :: INTEGER []) AS fk_product_abstract
         ) input    
      LEFT JOIN spy_product ON spy_product.sku = input.concrete_sku
),
    updated AS (
    UPDATE spy_product
    SET
      discount = records.discount,    
      warehouses = records.warehouses,  
      sku = records.concrete_sku,
      fk_product_abstract = records.fk_product_abstract,
      is_active = records.is_active,
      attributes = records.attributes,
      updated_at = now()
    FROM records
    WHERE records.concrete_sku = spy_product.sku
    RETURNING id_product,sku
  ),
    inserted AS(
    INSERT INTO spy_product (
      id_product,
      discount,    
      warehouses,
      sku,
      is_active,
      attributes,
      fk_product_abstract,
      created_at,
      updated_at
    ) (
      SELECT
        nextval('spy_product_pk_seq'),
        discount,   
        warehouses,
        concrete_sku,
        is_active,
        attributes,
        fk_product_abstract,
        now(),
        now()
    FROM records
    WHERE idProduct is null
  ) RETURNING id_product,sku
)
SELECT updated.id_product,sku FROM updated UNION ALL SELECT inserted.id_product,sku FROM inserted;";
        return $sql;
    }

    /**
     * @return string
     */
    protected function createConcreteProductLocalizedAttributesSQL()
    {
        $sql = "WITH records AS (
    SELECT
      input.sku,
      input.name,
      input.description,
      input.attributes,
      input.is_complete,
      input.id_locale,
      id_product,
      id_product_attributes as idProductAttributes
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS sku,
             unnest(? :: VARCHAR []) AS name,
             unnest(? :: TEXT []) AS description,
             json_array_elements(?) AS attributes,
             unnest(? :: BOOLEAN []) AS is_complete,
             unnest(? :: INTEGER []) AS id_locale
         ) input
      INNER JOIN spy_product ON spy_product.sku = input.sku
      LEFT JOIN spy_product_localized_attributes ON (spy_product_localized_attributes.fk_product = id_product and spy_product_localized_attributes.fk_locale = input.id_locale)
),
    updated AS (
    UPDATE spy_product_localized_attributes
    SET
      fk_product = records.id_product,
      fk_locale = records.id_locale,
      name = records.name,
      description = records.description,
      attributes = records.attributes,
      is_complete = records.is_complete,
      updated_at = now()
    FROM records
    WHERE records.id_product = spy_product_localized_attributes.fk_product and spy_product_localized_attributes.fk_locale = records.id_locale
    RETURNING id_product_attributes
  ),
    inserted AS(
    INSERT INTO spy_product_localized_attributes (
      id_product_attributes,
      name,
      description,
      attributes,
      is_complete,
      fk_locale,
      fk_product,
      created_at,
      updated_at
    ) (
      SELECT
        nextval('spy_product_localized_attributes_pk_seq'),
        name,
        description,
        attributes,
        is_complete,
        id_locale,
        id_product,
        now(),
        now()
      FROM records
      WHERE idProductAttributes is null
    ) RETURNING id_product_attributes
  )
SELECT updated.id_product_attributes FROM updated UNION ALL SELECT inserted.id_product_attributes FROM inserted;";

        return $sql;
    }

    /**
     * @return string
     */
    protected function createConcreteProductSearchSQL()
    {
        $sql = "WITH records AS (
    SELECT
      input.id_locale,
      input.sku,
      input.is_searchable,
      id_product,
      id_product_search as idProductSearch
    FROM (
           SELECT
             unnest(? :: INTEGER []) AS id_locale,
             unnest(? :: VARCHAR []) AS sku,
             unnest(? :: BOOLEAN []) AS is_searchable
         ) input
      INNER JOIN spy_product ON spy_product.sku = input.sku
      LEFT JOIN spy_product_search ON (spy_product_search.fk_product = id_product and spy_product_search.fk_locale = input.id_locale)
),
    updated AS (
    UPDATE spy_product_search
    SET
      fk_product = records.id_product,
      fk_locale = records.id_locale,
      is_searchable = records.is_searchable
    FROM records
    WHERE records.id_product = spy_product_search.fk_product and spy_product_search.fk_locale = records.id_locale
    RETURNING id_product_search
  ),
    inserted AS(
    INSERT INTO spy_product_search (
      id_product_search,
      fk_product,
      fk_locale,
      is_searchable
    ) (
      SELECT
        nextval('spy_product_search_pk_seq'),
        id_product,
        id_locale,
        is_searchable
      FROM records
      WHERE idProductSearch is null
    ) RETURNING id_product_search
  )
SELECT updated.id_product_search FROM updated UNION ALL SELECT inserted.id_product_search FROM inserted;";
        return $sql;
    }

    /**
     * @return string
     */
    protected function createConcreteProductBundleSQL()
    {
        $sql = "WITH records AS (
    SELECT
      input.sku,
      input.fk_bundled_product,
      input.quantity,
      id_product,
      id_product_bundle as idProductBundle
    FROM (
           SELECT
             unnest(? :: INTEGER []) AS fk_bundled_product,
             unnest(? :: VARCHAR []) AS sku,
             unnest(? :: INTEGER []) AS quantity
         ) input
    INNER JOIN spy_product ON spy_product.sku = input.sku
    LEFT JOIN spy_product_bundle ON (spy_product_bundle.fk_product = id_product and spy_product_bundle.fk_bundled_product = input.fk_bundled_product)
),
    updated AS (
    UPDATE spy_product_bundle
    SET
      updated_at = now(),
      fk_bundled_product = records.fk_bundled_product,
      fk_product = records.id_product,
      quantity = records.quantity
    FROM records WHERE spy_product_bundle.fk_product = records.id_product and spy_product_bundle.fk_bundled_product = records.fk_bundled_product
    RETURNING id_product_bundle
  ),
    inserted AS(
    INSERT INTO spy_product_bundle (
      id_product_bundle,
      fk_bundled_product,
      fk_product,
      quantity,
      created_at,
      updated_at
    ) (
      SELECT
        nextval('spy_product_bundle_pk_seq'),
        fk_bundled_product,
        id_product,
        quantity,
        now(),
        now()
      FROM records
      WHERE idProductBundle is null
    ) RETURNING id_product_bundle
  )
SELECT updated.id_product_bundle FROM updated UNION ALL SELECT inserted.id_product_bundle FROM inserted;";

        return $sql;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductConcrete(DataSetInterface $dataSet)
    {
        static::$productConcreteCollection[] = $dataSet[ProductConcreteHydratorStep::PRODUCT_CONCRETE_TRANSFER]->modifiedToArray();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductConcreteLocalizedAttributes(DataSetInterface $dataSet)
    {
        foreach ($dataSet[ProductConcreteHydratorStep::PRODUCT_CONCRETE_LOCALIZED_TRANSFER] as $productConcreteLocalizedTransfer) {
            $localizedAttributeArray = $productConcreteLocalizedTransfer[ProductConcreteHydratorStep::KEY_PRODUCT_CONCRETE_LOCALIZED_TRANSFER]->modifiedToArray();
            $productSearchArray = $productConcreteLocalizedTransfer[ProductConcreteHydratorStep::KEY_PRODUCT_SEARCH_TRANSFER]->modifiedToArray();
            $productSearchArray[ProductConcreteHydratorStep::KEY_SKU] = $productConcreteLocalizedTransfer[ProductConcreteHydratorStep::KEY_SKU];
            $localizedAttributeArray[ProductConcreteHydratorStep::KEY_DESCRIPTION] = str_replace(
                '"',
                '',
                $localizedAttributeArray[ProductConcreteHydratorStep::KEY_DESCRIPTION]
            );

            static::$productLocalizedAttributesCollection[] = $localizedAttributeArray;
            static::$productSearchCollection[] = $productSearchArray;
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductConcreteBundle(DataSetInterface $dataSet)
    {
        foreach ($dataSet[ProductConcreteHydratorStep::PRODUCT_BUNDLE_TRANSFER] as $productConcreteBundleTransfer) {
            $productConcreteBundleArray = $productConcreteBundleTransfer[ProductConcreteHydratorStep::KEY_PRODUCT_BUNDLE_TRANSFER]->modifiedToArray();
            $productConcreteBundleArray[ProductConcreteHydratorStep::KEY_SKU] = $productConcreteBundleTransfer[ProductConcreteHydratorStep::KEY_SKU];
            static::$productBundleCollection[] = $productConcreteBundleArray;
        }
    }

    /**
     * @return void
     */
    protected function flushMemory()
    {
        static::$productConcreteCollection = [];
        static::$productLocalizedAttributesCollection = [];
        static::$productSearchCollection = [];
        static::$productBundleCollection = [];
    }
}
