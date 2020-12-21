<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql;

class ProductStockMariaDbSql implements ProductStockSqlInterface
{
    /**
     * @return string
     */
    public function createStockSQL(): string
    {
        return '
            INSERT INTO spy_stock (
                name
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
                ),
                records AS (
                    SELECT
                      input.name as inputName,
                      id_stock as idStock,
                      spy_stock.name as spyStockName
                    FROM (
                           SELECT DISTINCT
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.names, \',\', n.digit + 1), \',\', -1), \'\') as name
                           FROM (
                                SELECT ? as names
                           ) temp
                           INNER JOIN n
                              ON LENGTH(REPLACE(names, \',\', \'\')) <= LENGTH(names) - n.digit
                         ) input
                     LEFT JOIN spy_stock ON spy_stock.name = input.name
                )
                (
                  SELECT
                    inputName
                  FROM records
                )
            ON DUPLICATE KEY UPDATE name = records.inputName';
    }

    /**
     * @return string
     */
    public function createStockProductSQL(): string
    {
        return '
            INSERT INTO spy_stock_product (
                  id_stock_product,
                  fk_product,
                  fk_stock,
                  quantity,
                  is_never_out_of_stock
                )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
                ),
                records AS (
                    SELECT
                      input.sku as inputSku,
                      input.stock_name as inputStockName,
                      input.quantity as inputQuantity,
                      input.is_never_out_of_stock as inputIsNeverOutOfStock,
                      id_stock_product as idStockProduct,
                      id_stock as fkStock,
                      id_product
                    FROM (
                           SELECT DISTINCT
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.skus, \',\', n.digit + 1), \',\', -1), \'\') as sku,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.stockNames, \',\', n.digit + 1), \',\', -1), \'\') as stock_name,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.quantities, \',\', n.digit + 1), \',\', -1), \'\') as quantity,
                                NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.isNeverOutOfStocks, \',\', n.digit + 1), \',\', -1), \'\') as is_never_out_of_stock
                           FROM (
                                SELECT ? as skus,
                                       ? as stockNames,
                                       ? as quantities,
                                       ? as isNeverOutOfStocks
                           ) temp
                           INNER JOIN n
                              ON LENGTH(REPLACE(skus, \',\', \'\')) <= LENGTH(skus) - n.digit
                                AND LENGTH(REPLACE(stockNames, \',\', \'\')) <= LENGTH(stockNames) - n.digit
                                AND LENGTH(REPLACE(quantities, \',\', \'\')) <= LENGTH(quantities) - n.digit
                                AND LENGTH(REPLACE(isNeverOutOfStocks, \',\', \'\')) <= LENGTH(isNeverOutOfStocks) - n.digit

                         ) input
                    INNER JOIN spy_stock on spy_stock.name = input.stock_name
                    INNER JOIN spy_product on spy_product.sku = input.sku
                    LEFT JOIN spy_stock_product ON spy_stock_product.fk_product = id_product
                        AND spy_stock_product.fk_stock = spy_stock.id_stock
                )
                (
                  SELECT
                    idStockProduct,
                    fkStock,
                    id_product,
                    inputQuantity,
                    inputIsNeverOutOfStock
                  FROM records
                )
                ON DUPLICATE KEY UPDATE fk_product = records.id_product,
                  fk_stock = records.fkStock,
                  quantity = records.inputQuantity,
                  is_never_out_of_stock = records.inputIsNeverOutOfStock
                RETURNING id_stock_product';
    }

    /**
     * @return string
     */
    public function createAbstractAvailabilitySQL(): string
    {
        return '
            INSERT INTO spy_availability_abstract (
              id_availability_abstract,
              abstract_sku,
              quantity,
              fk_store
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.sku,
                  input.qty,
                  input.store,
                  spy_availability_abstract.id_availability_abstract as idAvailabilityAbstract
                FROM (
                   SELECT DISTINCT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.skus, \',\', n.digit + 1), \',\', -1), \'\') as sku,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.qtys, \',\', n.digit + 1), \',\', -1), \'\') as qty,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.stores, \',\', n.digit + 1), \',\', -1), \'\') as store
                   FROM (
                        SELECT ? as skus,
                               ? as qtys,
                               ? as stores
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(skus, \',\', \'\')) <= LENGTH(skus) - n.digit
                        AND LENGTH(REPLACE(qtys, \',\', \'\')) <= LENGTH(qtys) - n.digit
                        AND LENGTH(REPLACE(stores, \',\', \'\')) <= LENGTH(stores) - n.digit
                 ) input
                LEFT JOIN spy_availability_abstract ON spy_availability_abstract.abstract_sku = input.sku AND spy_availability_abstract.fk_store = input.store
            )
            (
              SELECT
                idAvailabilityAbstract,
                records.sku,
                records.qty,
                records.store
              FROM records
            )
            ON DUPLICATE KEY UPDATE abstract_sku = records.sku,
                                    quantity = records.qty
            RETURNING id_availability_abstract';
    }

    /**
     * @return string
     */
    public function createAvailabilitySQL(): string
    {
        return '
            INSERT INTO spy_availability (
              id_availability,
              sku,
              quantity,
              is_never_out_of_stock,
              fk_availability_abstract,
              fk_store
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.sku,
                  input.qty,
                  input.is_never_out_of_stock,
                  input.store,
                  id_availability as idAvailability,
                  id_availability_abstract as idAvailabilityAbstract
                FROM (
                   SELECT DISTINCT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.skus, \',\', n.digit + 1), \',\', -1), \'\') as sku,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.qtys, \',\', n.digit + 1), \',\', -1), \'\') as qty,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.stores, \',\', n.digit + 1), \',\', -1), \'\') as store,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.is_never_out_of_stocks, \',\', n.digit + 1), \',\', -1), \'\') as is_never_out_of_stock
                   FROM (
                        SELECT ? as skus,
                               ? as qtys,
                               ? as stores,
                               ? as is_never_out_of_stocks
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(skus, \',\', \'\')) <= LENGTH(skus) - n.digit
                        AND LENGTH(REPLACE(qtys, \',\', \'\')) <= LENGTH(qtys) - n.digit
                        AND LENGTH(REPLACE(stores, \',\', \'\')) <= LENGTH(stores) - n.digit
                        AND LENGTH(REPLACE(is_never_out_of_stocks, \',\', \'\')) <= LENGTH(is_never_out_of_stocks) - n.digit
                ) input
                INNER JOIN spy_product ON spy_product.sku = input.sku
                INNER JOIN spy_product_abstract ON spy_product_abstract.id_product_abstract = spy_product.fk_product_abstract
                INNER JOIN spy_availability_abstract ON spy_availability_abstract.abstract_sku = spy_product_abstract.sku AND spy_availability_abstract.fk_store = input.store
                LEFT JOIN spy_availability ON spy_availability.sku = input.sku AND spy_availability.fk_store = input.store
            )
            (
              SELECT
                records.idAvailability,
                records.sku,
                records.qty,
                records.is_never_out_of_stock,
                records.idAvailabilityAbstract,
                records.store
              FROM records
              WHERE records.idAvailability is null
            )
            ON DUPLICATE KEY UPDATE sku = records.sku,
              quantity = records.qty,
              is_never_out_of_stock = records.is_never_out_of_stock
            RETURNING id_availability';
    }
}
