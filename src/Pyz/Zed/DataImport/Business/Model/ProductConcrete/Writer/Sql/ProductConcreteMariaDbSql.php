<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql;

class ProductConcreteMariaDbSql implements ProductConcreteSqlInterface
{
    /**
     * @return string
     */
    public function createConcreteProductSQL(): string
    {
        return '
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
        )
        WITH RECURSIVE n(digit) AS (
            SELECT 0 as digit
            UNION ALL
            SELECT 1 + digit FROM n WHERE digit < ?
            ),
            records AS (
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
                   SELECT DISTINCT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.discounts, \',\', n.digit + 1), \',\', -1), \'\') as discount,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.warehouseses, \',\', n.digit + 1), \',\', -1), \'\') as warehouses,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.concreteSkuses, \',\', n.digit + 1), \',\', -1), \'\') as concrete_sku,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.isActives, \',\', n.digit + 1), \',\', -1), \'\') as is_active,
                        REPLACE(NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.attributeses, \',\', n.digit + 1), \',\', -1), \'\'), \'|\', \',\') as attributes,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.skuProductAbstracts, \',\', n.digit + 1), \',\', -1), \'\') as sku_product_abstract
                   FROM (
                        SELECT ? as discounts,
                               ? as warehouseses,
                               ? as concreteSkuses,
                               ? as isActives,
                               ? as attributeses,
                               ? as skuProductAbstracts
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(discounts, \',\', \'\')) <= LENGTH(discounts) - n.digit
                        AND LENGTH(REPLACE(warehouseses, \',\', \'\')) <= LENGTH(warehouseses) - n.digit
                        AND LENGTH(REPLACE(concreteSkuses, \',\', \'\')) <= LENGTH(concreteSkuses) - n.digit
                        AND LENGTH(REPLACE(isActives, \',\', \'\')) <= LENGTH(isActives) - n.digit
                        AND LENGTH(REPLACE(attributeses, \',\', \'\')) <= LENGTH(attributeses) - n.digit
                        AND LENGTH(REPLACE(skuProductAbstracts, \',\', \'\')) <= LENGTH(skuProductAbstracts) - n.digit
                ) input
                LEFT JOIN spy_product ON spy_product.sku = input.concrete_sku
                INNER JOIN spy_product_abstract a on sku_product_abstract = a.sku
            )
            (
              SELECT
                idProduct,
                discount,
                warehouses,
                concrete_sku,
                is_active,
                attributes,
                fk_product_abstract,
                now(),
                now()
              FROM records
            )
        ON DUPLICATE KEY UPDATE discount = records.discount,
          warehouses = records.warehouses,
          sku = records.concrete_sku,
          fk_product_abstract = records.fk_product_abstract,
          is_active = records.is_active,
          attributes = records.attributes,
          updated_at = now()
        RETURNING id_product, sku
        ';
    }

    /**
     * @return string
     */
    public function createConcreteProductLocalizedAttributesSQL(): string
    {
        return '
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
        )
        WITH RECURSIVE n(digit) AS (
            SELECT 0 as digit
            UNION ALL
            SELECT 1 + digit FROM n WHERE digit < ?
            ),
            records AS (
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
                   SELECT DISTINCT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.skus, \',\', n.digit + 1), \',\', -1), \'\') as sku,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.names, \',\', n.digit + 1), \',\', -1), \'\') as name,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.descriptions, \',\', n.digit + 1), \',\', -1), \'\') as description,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.attributeses, \',\', n.digit + 1), \',\', -1), \'\') as attributes,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.isCompletes, \',\', n.digit + 1), \',\', -1), \'\') as is_complete,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.idLocales, \',\', n.digit + 1), \',\', -1), \'\') as id_locale
                   FROM (
                        SELECT ? as skus,
                               ? as names,
                               ? as descriptions,
                               ? as attributeses,
                               ? as isCompletes,
                               ? as idLocales
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(skus, \',\', \'\')) <= LENGTH(skus) - n.digit
                        AND LENGTH(REPLACE(names, \',\', \'\')) <= LENGTH(names) - n.digit
                        AND LENGTH(REPLACE(descriptions, \',\', \'\')) <= LENGTH(descriptions) - n.digit
                        AND LENGTH(REPLACE(attributeses, \',\', \'\')) <= LENGTH(attributeses) - n.digit
                        AND LENGTH(REPLACE(isCompletes, \',\', \'\')) <= LENGTH(isCompletes) - n.digit
                        AND LENGTH(REPLACE(idLocales, \',\', \'\')) <= LENGTH(idLocales) - n.digit
                ) input
                INNER JOIN spy_product ON spy_product.sku = input.sku
                LEFT JOIN spy_product_localized_attributes ON (
                    spy_product_localized_attributes.fk_product = id_product
                    AND spy_product_localized_attributes.fk_locale = input.id_locale
                )
            )
            (
              SELECT
                idProductAttributes,
                name,
                description,
                attributes,
                is_complete,
                id_locale,
                id_product,
                now(),
                now()
              FROM records
            )
        ON DUPLICATE KEY UPDATE fk_product = records.id_product,
          fk_locale = records.id_locale,
          name = records.name,
          description = records.description,
          attributes = records.attributes,
          is_complete = records.is_complete,
          updated_at = now()
        RETURNING id_product_attributes
        ';
    }

    /**
     * @return string
     */
    public function createConcreteProductSearchSQL(): string
    {
        return '
        INSERT INTO spy_product_search (
          id_product_search,
          fk_product,
          fk_locale,
          is_searchable
        )
        WITH RECURSIVE n(digit) AS (
            SELECT 0 as digit
            UNION ALL
            SELECT 1 + digit FROM n WHERE digit < ?
            ),
            records AS (
                SELECT
                  input.id_locale,
                  input.sku,
                  input.is_searchable,
                  id_product,
                  id_product_search as idProductSearch
                FROM (
                   SELECT DISTINCT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.idLocales, \',\', n.digit + 1), \',\', -1), \'\') as id_locale,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.skus, \',\', n.digit + 1), \',\', -1), \'\') as sku,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.isSearchables, \',\', n.digit + 1), \',\', -1), \'\') as is_searchable
                   FROM (
                        SELECT ? as idLocales,
                               ? as skus,
                               ? as isSearchables
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(idLocales, \',\', \'\')) <= LENGTH(idLocales) - n.digit
                        AND LENGTH(REPLACE(skus, \',\', \'\')) <= LENGTH(skus) - n.digit
                        AND LENGTH(REPLACE(isSearchables, \',\', \'\')) <= LENGTH(isSearchables) - n.digit
                ) input
                INNER JOIN spy_product ON spy_product.sku = input.sku
                  LEFT JOIN spy_product_search ON (spy_product_search.fk_product = id_product and spy_product_search.fk_locale = input.id_locale)
            )
            (
              SELECT
                idProductSearch,
                id_product,
                id_locale,
                is_searchable
              FROM records
            )
        ON DUPLICATE KEY UPDATE fk_product = records.id_product,
          fk_locale = records.id_locale,
          is_searchable = records.is_searchable
        RETURNING id_product_search
        ';
    }

    /**
     * @return string
     */
    public function createConcreteProductBundleSQL(): string
    {
        return '
        INSERT INTO spy_product_bundle (
          id_product_bundle,
          fk_bundled_product,
          fk_product,
          quantity,
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
                  input.sku,
                  input.bundled_product_sku,
                  input.quantity,
                  spy_product1.id_product as id_product,
                  spy_product2.id_product as fk_bundled_product,
                  id_product_bundle as idProductBundle
                FROM (
                   SELECT DISTINCT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.bundledProductSkus, \',\', n.digit + 1), \',\', -1), \'\') as bundled_product_sku,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.skus, \',\', n.digit + 1), \',\', -1), \'\') as sku,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.quantities, \',\', n.digit + 1), \',\', -1), \'\') as quantity
                   FROM (
                        SELECT ? as bundledProductSkus,
                               ? as skus,
                               ? as quantities
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(bundledProductSkus, \',\', \'\')) <= LENGTH(bundledProductSkus) - n.digit
                        AND LENGTH(REPLACE(skus, \',\', \'\')) <= LENGTH(skus) - n.digit
                        AND LENGTH(REPLACE(quantities, \',\', \'\')) <= LENGTH(quantities) - n.digit
                ) input
                JOIN spy_product as spy_product1 ON spy_product1.sku = input.sku
                    JOIN spy_product as spy_product2 ON spy_product2.sku = input.bundled_product_sku
                    LEFT JOIN spy_product_bundle ON (spy_product_bundle.fk_product = spy_product1.id_product
                        and spy_product_bundle.fk_bundled_product = spy_product2.id_product)
            )
            (
              SELECT
                idProductBundle,
                fk_bundled_product,
                id_product,
                quantity,
                now(),
                now()
              FROM records
            )
        ON DUPLICATE KEY UPDATE updated_at = now(),
          fk_bundled_product = records.fk_bundled_product,
          fk_product = records.id_product,
          quantity = records.quantity
        RETURNING id_product_bundle
        ';
    }
}
