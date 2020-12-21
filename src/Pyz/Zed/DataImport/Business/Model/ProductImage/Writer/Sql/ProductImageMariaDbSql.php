<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql;

class ProductImageMariaDbSql implements ProductImageSqlInterface
{
    /**
     * @return string
     */
    public function createProductImageSetSQL(): string
    {
        return 'INSERT INTO spy_product_image_set (
            id_product_image_set,
            name,
            fk_locale,
            fk_product,
            fk_product_abstract,
            created_at,
            updated_at
        )
        WITH RECURSIVE n(digit) AS (
            SELECT 0 as digit
            UNION ALL
            SELECT 1 + digit FROM n WHERE digit < ?
        ),
        records AS (
            SELECT
                input.name,
                input.fkLocale,
                input.fkProduct,
                input.fkProductAbstract,
                id_product_image_set as idProductImageSet
            FROM (
                     SELECT DISTINCT
                         NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.names, \',\', n.digit + 1), \',\', -1), \'\') as name,
                         NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fkLocales, \',\', n.digit + 1), \',\', -1), \'\') as fkLocale,
                         NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fkProducts, \',\', n.digit + 1), \',\', -1), \'\') as fkProduct,
                         NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fkProductAbstracts, \',\', n.digit + 1), \',\', -1), \'\') as fkProductAbstract
                     FROM (
                              SELECT ? AS names,
                                     ? AS fkLocales,
                                     ? AS fkProducts,
                                     ? AS fkProductAbstracts
                          ) temp
                              INNER JOIN n
                          ON LENGTH(REPLACE(names, \',\', \'\')) <= LENGTH(names) - n.digit
                              AND LENGTH(REPLACE(fkLocales, \',\', \'\')) <= LENGTH(fkLocales) - n.digit
                              AND LENGTH(REPLACE(fkProducts, \',\', \'\')) <= LENGTH(fkProducts) - n.digit
                              AND LENGTH(REPLACE(fkProductAbstracts, \',\', \'\')) <= LENGTH(fkProductAbstracts) - n.digit
            ) input
            LEFT JOIN spy_product_image_set ON
                spy_product_image_set.name = input.name
                    AND spy_product_image_set.fk_locale = input.fkLocale
                    AND (spy_product_image_set.fk_product_abstract = input.fkProductAbstract OR spy_product_image_set.fk_product = input.fkProduct)
            WHERE spy_product_image_set.id_product_image_set IS NULL
        )
        (
            SELECT
                idProductImageSet,
                name,
                fkLocale,
                fkProduct,
                fkProductAbstract,
                now(),
                now()
            FROM records
        ) ON DUPLICATE KEY UPDATE
            name = records.name,
            fk_locale = records.fkLocale,
            fk_product = records.fkProduct,
            fk_product_abstract = records.fkProductAbstract,
            updated_at = NOW()';
    }

    /**
     * @return string
     */
    public function createOrUpdateProductImageSQL(): string
    {
        return '
            INSERT INTO spy_product_image (id_product_image,
                                           external_url_large,
                                           external_url_small,
                                           product_image_key,
                                           created_at,
                                           updated_at)
            WITH RECURSIVE n(digit) AS (
                    SELECT 0 as digit
                    UNION ALL
                    SELECT 1 + digit
                    FROM n
                    WHERE digit < ?
            ),
            records AS (
                SELECT spy_product_image.id_product_image AS idProductImage,
                       input.externalUrlLarge,
                       input.externalUrlSmall,
                       input.productImageKey
                FROM (
                         SELECT DISTINCT NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.externalUrlLarge, \',\', n.digit + 1), \',\', -1), \'\') as externalUrlLarge,
                                         NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.externalUrlSmall, \',\', n.digit + 1), \',\', -1), \'\') as externalUrlSmall,
                                         NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.productImageKey, \',\', n.digit + 1), \',\', -1), \'\') as productImageKey
                         FROM (
                                  SELECT ? AS externalUrlLarge,
                                         ? AS externalUrlSmall,
                                         ? AS productImageKey
                              ) temp
                                  INNER JOIN n
                                             ON LENGTH(REPLACE(externalUrlLarge, \',\', \'\')) <= LENGTH(externalUrlLarge) - n.digit
                                                 AND
                                                LENGTH(REPLACE(externalUrlSmall, \',\', \'\')) <= LENGTH(externalUrlSmall) - n.digit
                                                 AND
                                                LENGTH(REPLACE(productImageKey, \',\', \'\')) <= LENGTH(productImageKey) - n.digit
                     ) input
                         LEFT JOIN spy_product_image ON spy_product_image.product_image_key = input.productImageKey
            )
            (
                SELECT records.idProductImage,
                       records.externalUrlLarge,
                       records.externalUrlSmall,
                       records.productImageKey,
                       now(),
                       now()
                FROM records
            )
        ON DUPLICATE KEY UPDATE external_url_large = records.externalUrlLarge,
                                external_url_small = records.externalUrlSmall,
                                updated_at = NOW()
        RETURNING id_product_image';
    }

    /**
     * @return string
     */
    public function createProductImageSetRelationSQL(): string
    {
        return 'INSERT INTO spy_product_image_set_to_product_image (
                id_product_image_set_to_product_image,
                fk_product_image,
                fk_product_image_set,
                sort_order
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit
                FROM n
                WHERE digit < ?
            ),
            records AS (
                SELECT spy_product_image_set_to_product_image.id_product_image_set_to_product_image AS idProductImageSetToProductImage,
                       spy_product_image.id_product_image AS idProductImage,
                       spy_product_image_set.id_product_image_set AS idProductImageSet,
                       input.sortOrder as sortOrder
                FROM (
                         SELECT DISTINCT NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.name, \',\', n.digit + 1), \',\', -1), \'\') as name,
                                         NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fkLocale, \',\', n.digit + 1), \',\', -1), \'\') as fkLocale,
                                         NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fkProduct, \',\', n.digit + 1), \',\', -1), \'\') as fkProduct,
                                         NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fkProductAbstract, \',\', n.digit + 1), \',\', -1), \'\') as fkProductAbstract,
                                         NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.sortOrder, \',\', n.digit + 1), \',\', -1), \'\') as sortOrder,
                                         NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.productImageKey, \',\', n.digit + 1), \',\', -1), \'\') as productImageKey
                         FROM (
                                  SELECT ? AS name,
                                         ? AS fkLocale,
                                         ? AS fkProduct,
                                         ? AS fkProductAbstract,
                                         ? AS sortOrder,
                                         ? AS productImageKey
                              ) temp
                                  INNER JOIN n
                                             ON LENGTH(REPLACE(name, \',\', \'\')) <= LENGTH(name) - n.digit
                                                 AND
                                                LENGTH(REPLACE(fkLocale, \',\', \'\')) <= LENGTH(fkLocale) - n.digit
                                                 AND
                                                LENGTH(REPLACE(fkProduct, \',\', \'\')) <= LENGTH(fkProduct) - n.digit
                                                 AND
                                                LENGTH(REPLACE(fkProductAbstract, \',\', \'\')) <= LENGTH(fkProductAbstract) - n.digit
                                                 AND
                                                LENGTH(REPLACE(sortOrder, \',\', \'\')) <= LENGTH(sortOrder) - n.digit
                                                 AND
                                                LENGTH(REPLACE(productImageKey, \',\', \'\')) <= LENGTH(productImageKey) - n.digit
                     ) input
                         INNER JOIN spy_product_image_set ON spy_product_image_set.name = input.name AND fk_locale = input.fkLocale AND (fk_product = input.fkProduct OR fk_product_abstract = input.fkProductAbstract)
                         INNER JOIN spy_product_image ON spy_product_image.product_image_key = input.productImageKey
                         LEFT JOIN spy_product_image_set_to_product_image ON spy_product_image_set_to_product_image.fk_product_image = spy_product_image.id_product_image AND spy_product_image_set_to_product_image.fk_product_image_set = spy_product_image_set.id_product_image_set
            )
            (
                SELECT
                    records.idProductImageSetToProductImage,
                    records.idProductImage,
                    records.idProductImageSet,
                    records.sortOrder
                FROM records
            )
            ON DUPLICATE KEY UPDATE sort_order = records.sortOrder
';
    }

    /**
     * @return string
     */
    public function findProductImageSetsByProductImageIds(): string
    {
        return 'WITH RECURSIVE
            n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit
                FROM n
                WHERE digit < ?
            ),
            touched_product_images as (
                SELECT input.idProductImage
                FROM (
                         SELECT DISTINCT NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.idProductImage, \',\', n.digit + 1), \',\', -1), \'\') as idProductImage
                         FROM (
                                  SELECT ? AS idProductImage
                              ) temp
                                  INNER JOIN n ON LENGTH(REPLACE(idProductImage, \',\', \'\')) <= LENGTH(idProductImage) - n.digit
                     ) input
            )
            SELECT DISTINCT name,
                            fk_locale,
                            fk_product,
                            fk_product_abstract
            FROM spy_product_image_set
                     INNER JOIN spy_product_image_set_to_product_image ON
                id_product_image_set = fk_product_image_set
            WHERE fk_product_image IN (
                SELECT idProductImage
                FROM touched_product_images
            )
        ';
    }
}
