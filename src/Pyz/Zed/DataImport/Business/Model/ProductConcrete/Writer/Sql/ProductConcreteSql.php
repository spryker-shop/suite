<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql;

class ProductConcreteSql implements ProductConcreteSqlInterface
{
    /**
     * @return string
     */
    public function createConcreteProductSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.discount,
      input.warehouses,
      input.concrete_sku,
      input.is_active,
      input.attributes,
      input.sku_product_abstract,
      id_product as idProduct,
      id_product_abstract as fk_product_abstract
    FROM (
           SELECT
             unnest(? :: INTEGER []) AS discount,
             unnest(? :: TEXT []) AS warehouses,
             unnest(? :: VARCHAR []) AS concrete_sku,
             unnest(? :: BOOLEAN []) AS is_active,
             json_array_elements(?) AS attributes,
             unnest(? :: VARCHAR []) AS sku_product_abstract
         ) input
      LEFT JOIN spy_product ON spy_product.sku = input.concrete_sku
      INNER JOIN spy_product_abstract a on sku_product_abstract = a.sku 
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
    public function createConcreteProductLocalizedAttributesSQL(): string
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
    public function createConcreteProductSearchSQL(): string
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
    public function createConcreteProductBundleSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.sku,
      input.bundled_product_sku,
      input.quantity,
      spy_product1.id_product as id_product,
      spy_product2.id_product as fk_bundled_product,
      id_product_bundle as idProductBundle
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS bundled_product_sku,
             unnest(? :: VARCHAR []) AS sku,
             unnest(? :: INTEGER []) AS quantity
         ) input
      JOIN spy_product as spy_product1 ON spy_product1.sku = input.sku
      JOIN spy_product as spy_product2 ON spy_product2.sku = input.bundled_product_sku
      LEFT JOIN spy_product_bundle ON (spy_product_bundle.fk_product = spy_product1.id_product
                                       and spy_product_bundle.fk_bundled_product = spy_product2.id_product)
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
}
