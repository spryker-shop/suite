<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql;

class ProductImageSql implements ProductImageSqlInterface
{
    /**
     * @param string $idProduct
     * @param string $fkProduct
     *
     * @return string
     */
    public function createProductImageSetSQL(string $idProduct, string $fkProduct): string
    {
        $sql = sprintf("WITH records AS (
    SELECT
      input.name,
      input.id_locale,
      input.%1\$s,
      input.sortOrder,
      id_product_image_set as idProductImageSet
    FROM (
           SELECT
             unnest(? :: VARCHAR[]) AS name,
             unnest(? :: INTEGER[]) AS id_locale,
             unnest(? :: INTEGER[]) AS %1\$s,
             unnest(? :: INTEGER[]) AS sortOrder
         ) input
         LEFT JOIN spy_product_image_set ON (spy_product_image_set.fk_locale = id_locale AND spy_product_image_set.%2\$s = input.%1\$s)
         ORDER BY input.sortOrder
    ),
    updated AS (
        UPDATE spy_product_image_set
        SET
          name = records.name,
          fk_locale = records.id_locale,
          %2\$s = records.%1\$s,
          updated_at = now()
        FROM records
        WHERE
          idProductImageSet is not null AND records.%1\$s = spy_product_image_set.%2\$s AND records.id_locale = spy_product_image_set.fk_locale
        RETURNING id_product_image_set, %2\$s
  ),
    inserted AS(
        INSERT INTO spy_product_image_set (
          id_product_image_set,
          name,
          fk_locale,
          %2\$s,
          created_at,
          updated_at
        ) (
          SELECT
            nextval('spy_product_image_set_pk_seq'),
            name,
            id_locale,
            %1\$s,
            now(),
            now()
          FROM records
          WHERE idProductImageSet IS NULL AND %1\$s IS NOT NULL
    ) RETURNING id_product_image_set, %2\$s
  )
SELECT updated.id_product_image_set, updated.%2\$s as %2\$s FROM updated UNION ALL SELECT inserted.id_product_image_set, inserted.%2\$s as %2\$s FROM inserted", $idProduct, $fkProduct);

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
          input.externalUrlSmall,
          input.sortOrder,
          id_product_image as idProductImage
        FROM (
               SELECT
                 unnest(?::VARCHAR[]) AS externalUrlLarge,
                 unnest(?::VARCHAR[]) AS externalUrlSmall,
                 unnest(?::INTEGER[]) AS sortOrder
             ) input
      LEFT JOIN spy_product_image ON (spy_product_image.external_url_large = input.externalUrlLarge AND spy_product_image.external_url_small = input.externalUrlSmall)
      ORDER BY input.sortOrder
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
          WHERE idProductImage IS NULL
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
      input.orderKey,
      id_product_image_set_to_product_image as idProductImageRelation
    FROM (
           SELECT
             unnest(? :: INTEGER []) AS fkProductImage,
             unnest(? :: INTEGER []) AS fkProductImageSet,
             unnest(? :: INTEGER []) AS sortOrder,
             unnest(? :: INTEGER []) AS orderKey
         ) input
      LEFT JOIN spy_product_image_set_to_product_image as \"pisr\" ON 
      (pisr.fk_product_image = input.fkProductImage AND pisr.fk_product_image_set = input.fkProductImageSet)
      ORDER BY input.orderKey
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

    /**
     * @return string
     */
    public function convertLocaleNameToId(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.sortKey,
      input.locale,
      spy_locale.id_locale as id_locale
    FROM (
           SELECT
             unnest(?::INTEGER[]) AS sortKey,
             unnest(?::VARCHAR[]) AS locale
         ) input
        LEFT JOIN spy_locale ON spy_locale.locale_name = input.locale
) SELECT records.id_locale FROM records WHERE records.id_locale IS NOT NULL ORDER BY records.sortKey;";

        return $sql;
    }

    /**
     * @param string $tableName
     * @param string $fieldName
     *
     * @return string
     */
    public function convertProductSkuToId(string $tableName, string $fieldName): string
    {
        $sql = sprintf("WITH records AS (
    SELECT
      input.orderKey,
      input.sku,
      %1\$s.%2\$s as %2\$s
    FROM (
           SELECT
             unnest(?::INTEGER[]) AS orderKey,
             unnest(?::VARCHAR[]) AS sku
         ) input
        LEFT JOIN %1\$s ON %1\$s.sku = input.sku
) SELECT records.%2\$s FROM records ORDER BY records.orderKey;", $tableName, $fieldName);

        return $sql;
    }

    /**
     * @return string
     */
    public function convertImageNameToId(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.sort_key,
      input.external_url_large,
      spy_product_image.id_product_image
    FROM (
           SELECT
             unnest(?::INTEGER[]) AS sort_key,
             unnest(?::VARCHAR[]) AS external_url_large
         ) input
        LEFT JOIN spy_product_image ON spy_product_image.external_url_large = input.external_url_large
) SELECT records.id_product_image FROM records where id_product_image IS NOT NULL ORDER BY records.sort_key;";

        return $sql;
    }

    /**
     * @param string $fkProductKey
     *
     * @return string
     */
    public function findProductImageSetIds(string $fkProductKey): string
    {
        $sql = sprintf("WITH records AS (
    SELECT
      input.fk_locale,
      input.fk_product,
      input.sort_key,
      spy_product_image_set.id_product_image_set
    FROM (
           SELECT
             unnest(?::INTEGER[]) AS fk_locale,
             unnest(?::INTEGER[]) AS fk_product,
             unnest(?::INTEGER[]) AS sort_key
         ) input
        LEFT JOIN spy_product_image_set ON (spy_product_image_set.%1\$s = input.fk_product AND spy_product_image_set.fk_locale = input.fk_locale)
) SELECT records.id_product_image_set FROM records ORDER BY records.sort_key;", $fkProductKey);

        return $sql;
    }
}
