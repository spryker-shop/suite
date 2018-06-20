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
      input.localeName,
      input.concreteProductSku,
      input.abstractProductSku,
      input.fkResourceProductSet,
      id_product_image_set as idProductImageSet,
      spy_locale.id_locale,
      spy_product.id_product,
      spy_product_abstract.id_product_abstract
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS name,
             unnest(? :: VARCHAR []) AS localeName,
             unnest(? :: VARCHAR []) AS concreteProductSku,
             unnest(? :: VARCHAR []) AS abstractProductSku,
             unnest(? :: INTEGER []) AS fkResourceProductSet
         ) input
      LEFT JOIN spy_product ON spy_product.sku = concreteProductSku OR spy_product.sku is NULL
      LEFT JOIN spy_product_abstract ON spy_product_abstract.sku = abstractProductSku OR spy_product_abstract.sku is NULL
      LEFT JOIN spy_locale ON spy_locale.locale_name = localeName OR spy_locale.locale_name is NULL
      LEFT JOIN spy_product_image_set ON
                                        spy_product_image_set.fk_locale = id_locale AND
                                        (spy_product_image_set.fk_product_abstract = id_product_abstract OR
                                         spy_product_image_set.fk_product_abstract is null) AND
                                        (spy_product_image_set.fk_product = id_product OR
                                         spy_product_image_set.fk_product is null)
),
    updated AS (
    UPDATE spy_product_image_set
    SET
      name = records.name,
      fk_locale = records.id_locale,
      fk_product = records.id_product,
      fk_product_abstract = records.id_product_abstract,
      fk_resource_product_set = records.fkResourceProductSet,
      updated_at = now()
    FROM records
    WHERE
      idProductImageSet is not null AND (
        (records.id_product = spy_product_image_set.fk_product OR records.id_product is null) AND
        (records.id_product_abstract = spy_product_image_set.fk_product_abstract OR records.id_product_abstract is null) AND
        records.id_locale = spy_product_image_set.fk_locale )
    RETURNING id_product_image_set, fk_product_abstract, fk_product
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
        id_locale,
        id_product,
        id_product_abstract,
        fkResourceProductSet,
        now(),
        now()
      FROM records
      WHERE idProductImageSet is null AND (id_product is null OR id_product_abstract is null OR id_locale is null)
    ) RETURNING id_product_image_set, fk_product_abstract, fk_product
  )
SELECT updated.id_product_image_set, updated.fk_product_abstract, updated.fk_product FROM updated UNION ALL SELECT inserted.id_product_image_set, inserted.fk_product_abstract, inserted.fk_product FROM inserted;";
        return $sql;
    }

    /**
     * @return string
     */
    public function createProductImageSQL(): string
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
    public function createProductImageSetRelationSQL(): string
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
}
