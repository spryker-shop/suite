<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql;

class ProductAbstractSql implements ProductAbstractSqlInterface
{
    /**
     * @return string
     */
    public function createAbstractProductSQL(): string
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
      new_to,
      created_at,
      updated_at
    ) (
      SELECT
        nextval('spy_product_abstract_pk_seq'),
        abstract_sku,
        attributes,
        fkTaxSet,
        colorCode,
        newFrom,
        newTo,
        now(),
        now()
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
    public function createAbstractProductLocalizedAttributesSQL(): string
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
    public function createAbstractProductCategoriesSQL(): string
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
    public function createAbstractProductUrlsSQL(): string
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
}
