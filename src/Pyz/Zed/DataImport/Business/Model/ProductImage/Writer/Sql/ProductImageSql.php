<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql;

class ProductImageSql implements ProductImageSqlInterface
{
    /**
     * @return string
     */
    public function createProductImageSetSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.name,
      input.fkLocale,
      input.fkProduct,
      input.fkProductAbstract,
      id_product_image_set as idProductImageSet
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS name,
             unnest(? :: INTEGER []) AS fkLocale,
             unnest(? :: INTEGER []) AS fkProduct,
             unnest(? :: INTEGER []) AS fkProductAbstract,
             unnest(? :: INTEGER []) AS sortOrder
         ) input
      LEFT JOIN spy_product_image_set ON
        spy_product_image_set.fk_locale = input.fkLocale AND
        (spy_product_image_set.fk_product_abstract = input.fkProductAbstract OR
        spy_product_image_set.fk_product_abstract is null) AND
        (spy_product_image_set.fk_product = input.fkProduct OR
        spy_product_image_set.fk_product is null)
       ORDER BY input.sortOrder
),
    updated AS (
    UPDATE spy_product_image_set
    SET
      name = records.name,
      fk_locale = records.fkLocale,
      fk_product = records.fkProduct,
      fk_product_abstract = records.fkProductAbstract,
      updated_at = now()
    FROM records
    WHERE
      idProductImageSet is not null AND (
      (records.fkProduct = spy_product_image_set.fk_product OR records.fkProduct is null) AND
      (records.fkProductAbstract = spy_product_image_set.fk_product_abstract OR records.fkProductAbstract is null) AND
      records.fkLocale = spy_product_image_set.fk_locale )
    RETURNING id_product_image_set, fk_product, fk_product_abstract
  ),
    inserted AS(
    INSERT INTO spy_product_image_set (
      id_product_image_set,
      name,
      fk_locale,
      fk_product,
      fk_product_abstract,
      created_at,
      updated_at
    ) (
      SELECT
        nextval('spy_product_image_set_pk_seq'),
        name,
        fkLocale,
        fkProduct,
        fkProductAbstract,
        now(),
        now()
      FROM records
      WHERE idProductImageSet is null AND (fkProduct is null OR fkProductAbstract is null OR fkLocale is null)
    ) RETURNING id_product_image_set, fk_product, fk_product_abstract
  )
SELECT updated.id_product_image_set, updated.fk_product, updated.fk_product_abstract FROM updated 
UNION ALL SELECT inserted.id_product_image_set, inserted.fk_product, inserted.fk_product_abstract FROM inserted;";

        return $sql;
    }

    /**
     * @return string
     */
    public function createProductImageSQL(): string
    {
        $sql = "WITH 
    records AS (
        SELECT
          input.externalUrlLarge,
          input.externalUrlSmall
        FROM (
               SELECT
                 unnest(?::VARCHAR[]) AS externalUrlLarge,
                 unnest(?::VARCHAR[]) AS externalUrlSmall
             ) input
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
        ) RETURNING id_product_image
  )
SELECT inserted.id_product_image FROM inserted;";

        return $sql;
    }

    /**
     * @return string
     */
    public function createProductImageSetRelationSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.fkProductImage,
      input.fkProductImageSet,
      input.sortOrder,
      id_product_image_set_to_product_image as idProductImageRelation
    FROM (
           SELECT
             unnest(? :: INTEGER []) AS fkProductImage,
             unnest(? :: INTEGER []) AS fkProductImageSet,
             unnest(? :: INTEGER []) AS sortOrder
         ) input
      LEFT JOIN spy_product_image_set_to_product_image as \"pisr\" ON 
      (pisr.fk_product_image = input.fkProductImage AND pisr.fk_product_image_set = input.fkProductImageSet)
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
    ) ON CONFLICT DO NOTHING
  )
SELECT 1;";

        return $sql;
    }
}
