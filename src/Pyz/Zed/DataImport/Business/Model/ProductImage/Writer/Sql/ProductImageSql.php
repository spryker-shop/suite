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
    SELECT DISTINCT
      input.name,
      input.fkLocale,
      input.fkProduct,
      input.fkProductAbstract
    FROM (
       SELECT
         unnest(? :: VARCHAR []) AS name,
         unnest(? :: INTEGER []) AS fkLocale,
         unnest(? :: INTEGER []) AS fkProduct,
         unnest(? :: INTEGER []) AS fkProductAbstract
      ) input
      LEFT JOIN spy_product_image_set ON
        spy_product_image_set.name = input.name 
        AND spy_product_image_set.fk_locale = input.fkLocale 
        AND (spy_product_image_set.fk_product_abstract = input.fkProductAbstract OR spy_product_image_set.fk_product = input.fkProduct)
      WHERE spy_product_image_set.id_product_image_set IS NULL
)
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
    )
 ";

        return $sql;
    }

    /**
     * @return string
     */
    public function createOrUpdateProductImageSQL(): string
    {
        $sql = "WITH 
    records AS (
        SELECT
          input.externalUrlLarge,
          input.externalUrlSmall,
          input.productImageKey,
          id_product_image AS idProductImage
        FROM (
            SELECT
                unnest(?::VARCHAR[]) AS externalUrlLarge,
                unnest(?::VARCHAR[]) AS externalUrlSmall,
                unnest(?::VARCHAR[]) AS productImageKey
            ) input
            LEFT JOIN spy_product_image ON spy_product_image.product_image_key = productImageKey
        ),
    inserted as (
        INSERT INTO spy_product_image (
          id_product_image,
          external_url_large,
          external_url_small,
          product_image_key,
          created_at,
          updated_at
        ) (
          SELECT
            nextval('spy_product_image_pk_seq'),
            externalUrlLarge,
            externalUrlSmall,
            productImageKey,
            now(),
            now()
          FROM records
          WHERE idProductImage IS NULL
        )
        RETURNING id_product_image
    ),
    updated as (
        UPDATE spy_product_image
        SET external_url_large = externalUrlLarge,
            external_url_small = externalUrlSmall
        FROM records
        WHERE idProductImage IS NOT NULL 
            AND product_image_key = productImageKey
        RETURNING id_product_image
    )
    SELECT id_product_image
    FROM inserted
    UNION ALL SELECT id_product_image
    FROM updated";

        return $sql;
    }

    /**
     * @return string
     */
    public function createProductImageSetRelationSQL(): string
    {
        $sql = "WITH
    records AS (
        SELECT
          id_product_image,
          id_product_image_set,
          input.sortOrder as sort_order,
          id_product_image_set_to_product_image
        FROM (
            SELECT
                unnest(? :: VARCHAR []) AS name,
                unnest(? :: INTEGER []) AS fkLocale,
                unnest(? :: INTEGER []) AS fkProduct,
                unnest(? :: INTEGER []) AS fkProductAbstract,
                unnest(? :: INTEGER []) AS sortOrder,
                unnest(? :: VARCHAR []) AS productImageKey
            ) input
            INNER JOIN spy_product_image_set ON
                spy_product_image_set.name = input.name 
                AND fk_locale = fkLocale 
                AND (fk_product = fkProduct OR fk_product_abstract = fkProductAbstract)
            INNER JOIN spy_product_image ON
                product_image_key = productImageKey
            LEFT JOIN spy_product_image_set_to_product_image ON 
                fk_product_image = id_product_image 
                AND fk_product_image_set = id_product_image_set
    ),
    updated AS (
        UPDATE spy_product_image_set_to_product_image
        SET sort_order = records.sort_order
        FROM records
        WHERE id_product_image = fk_product_image 
            AND id_product_image_set = fk_product_image_set
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
                id_product_image,
                id_product_image_set,
                records.sort_order
            FROM records
            WHERE id_product_image_set_to_product_image IS NULL
        ) ON CONFLICT DO NOTHING
    )
    SELECT 1;";

        return $sql;
    }

    /**
     * @return string
     */
    public function findProductImageSetsByProductImageIds(): string
    {
        $sql = "WITH 
    touched_product_images as (
        SELECT unnest((? :: INTEGER [])) as id_product_image
    )
    SELECT DISTINCT
        name,
        fk_locale,
        fk_product,
        fk_product_abstract
    FROM spy_product_image_set
    INNER JOIN spy_product_image_set_to_product_image ON
        id_product_image_set = fk_product_image_set
    WHERE fk_product_image IN (
        SELECT id_product_image FROM touched_product_images
    )";

        return $sql;
    }
}
