<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock\Writer;

class ProductStockSql implements ProductStockSqlInterface
{
    /**
     * @return string
     */
    public function createStockSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.name as inputName,
      id_stock as idStock,
      spy_stock.name as spyStockName
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS name
         ) input
    LEFT JOIN spy_stock ON spy_stock.name = input.name
),
    updated AS (
    UPDATE spy_stock
    SET
      name = records.inputName
    FROM records
    WHERE idStock is not null and spyStockName is null
    RETURNING idStock
  ),
    inserted AS(
    INSERT INTO spy_stock (
      id_stock,
      name
    ) (
      SELECT
        nextval('spy_stock_pk_seq'),
        inputName
      FROM records
      WHERE idStock is null AND inputName <> ''
    ) RETURNING id_stock
  )
SELECT updated.idStock FROM updated UNION ALL SELECT inserted.id_stock FROM inserted;";

        return $sql;
    }

    /**
     * @return string
     */
    public function createStockProductSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.fk_product,
      input.stockName,
      input.quantity,
      input.is_never_out_of_stock,
      id_stock_product as idStockProduct,
      id_stock as fkStock
    FROM (
           SELECT
             unnest(? :: INTEGER []) AS fk_product,
             unnest(? :: VARCHAR []) AS stockName,
             unnest(? :: INTEGER []) AS quantity,
             unnest(? :: BOOLEAN []) AS is_never_out_of_stock
         ) input
      INNER JOIN spy_stock on spy_stock.name = stockName
      LEFT JOIN spy_stock_product ON spy_stock_product.fk_product = input.fk_product
                                     AND spy_stock_product.fk_stock = spy_stock.id_stock
),
    updated AS (
    UPDATE spy_stock_product
    SET
      fk_product = records.fk_product,
      fk_stock = records.fkStock,
      quantity = records.quantity,
      is_never_out_of_stock = records.is_never_out_of_stock
    FROM records
    WHERE spy_stock_product.fk_product = records.fk_product AND fk_stock = records.fkStock
    RETURNING idStockProduct
  ),
    inserted AS(
    INSERT INTO spy_stock_product (
      id_stock_product,
      fk_product,
      fk_stock,
      quantity,
      is_never_out_of_stock
    ) (
      SELECT
        nextval('spy_stock_product_pk_seq'),
        fk_product,
        fkStock,
        quantity,
        is_never_out_of_stock
      FROM records
      WHERE idStockProduct is null
    ) RETURNING id_stock_product
  )
SELECT updated.idStockProduct FROM updated UNION ALL SELECT inserted.id_stock_product FROM inserted;";

        return $sql;
    }
}
