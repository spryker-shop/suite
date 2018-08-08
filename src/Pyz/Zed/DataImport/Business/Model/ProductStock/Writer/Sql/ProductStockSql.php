<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql;

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
      input.sku,
      input.stockName,
      input.quantity,
      input.is_never_out_of_stock,
      id_stock_product as idStockProduct,
      id_stock as fkStock,
      id_product
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS sku,
             unnest(? :: VARCHAR []) AS stockName,
             unnest(? :: INTEGER []) AS quantity,
             unnest(? :: BOOLEAN []) AS is_never_out_of_stock
         ) input
      INNER JOIN spy_stock on spy_stock.name = stockName
      INNER JOIN spy_product on spy_product.sku = input.sku
      LEFT JOIN spy_stock_product ON spy_stock_product.fk_product = id_product
       AND spy_stock_product.fk_stock = spy_stock.id_stock
),
    updated AS (
    UPDATE spy_stock_product
    SET
      fk_product = records.id_product,
      fk_stock = records.fkStock,
      quantity = records.quantity,
      is_never_out_of_stock = records.is_never_out_of_stock
    FROM records
    WHERE spy_stock_product.fk_product = records.id_product AND fk_stock = records.fkStock
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
        id_product,
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

    /**
     * @return string
     */
    public function createAvailabilityProductSQL(): string
    {
        $sql = "WITH product_availability AS (
    SELECT
      input.sku,
      CASE WHEN SUM(CASE WHEN ssp.is_never_out_of_stock THEN 1 ELSE 0 END) > 0 THEN TRUE 
        ELSE FALSE END
      AS is_never_out_of_stock,
      CASE
      WHEN
        (SUM(CASE WHEN ssp.is_never_out_of_stock THEN 1 ELSE 0 END) = 0) AND
        (
        SUM(
                 CASE WHEN ssp.quantity IS NULL THEN 0 ELSE ssp.quantity END
             ) - SUM(
            CASE WHEN spy_oms_product_reservation.reservation_quantity IS NULL THEN 0 ELSE spy_oms_product_reservation.reservation_quantity END
        ) > 0
        )
        THEN SUM(
                 CASE WHEN ssp.quantity IS NULL THEN 0 ELSE ssp.quantity END
             ) - SUM(
            CASE WHEN spy_oms_product_reservation.reservation_quantity IS NULL THEN 0 ELSE spy_oms_product_reservation.reservation_quantity END
        )
        ELSE 0
      END
        AS quantity,
      spy_store.id_store as fk_store,
      spy_product_abstract.sku as abstract_sku,
      spy_availability.id_availability as idAvailability
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS sku,
             unnest(? :: VARCHAR []) AS store_name
         ) input
      INNER JOIN spy_store ON spy_store.name = input.store_name
      INNER JOIN spy_product ON spy_product.sku = input.sku
      INNER JOIN spy_product_abstract ON spy_product_abstract.id_product_abstract = spy_product.fk_product_abstract
      LEFT JOIN (
            SELECT spy_stock_product.* FROM spy_stock_product
            INNER JOIN spy_stock ON spy_stock.id_stock = spy_stock_product.fk_stock AND spy_stock.name IN (SELECT(unnest(? :: VARCHAR [])))
      ) as ssp  ON ssp.fk_product = spy_product.id_product
      LEFT JOIN spy_oms_product_reservation ON spy_oms_product_reservation.sku = input.sku AND spy_oms_product_reservation.fk_store = spy_store.id_store
      LEFT JOIN spy_availability ON spy_availability.sku = input.sku AND spy_availability.fk_store = spy_store.id_store
      GROUP BY input.sku, spy_store.id_store, spy_product_abstract.sku, spy_availability.id_availability
    ),
    product_abstract_availability AS (
    SELECT
      product_availability.abstract_sku,
      CASE
        WHEN SUM(CASE WHEN product_availability.is_never_out_of_stock THEN 1 ELSE 0 END) = 0
        THEN SUM(product_availability.quantity) 
        ELSE 0
      END 
      AS quantity,
      product_availability.fk_store,
      spy_availability_abstract.id_availability_abstract as idAvailabilityAbstract
    FROM product_availability
    LEFT JOIN spy_availability_abstract ON spy_availability_abstract.abstract_sku = product_availability.abstract_sku 
        AND spy_availability_abstract.fk_store = product_availability.fk_store
    GROUP BY product_availability.abstract_sku, product_availability.fk_store, spy_availability_abstract.id_availability_abstract
    ),
    updated_product_abstract AS (
    UPDATE spy_availability_abstract
    SET
      abstract_sku = product_abstract_availability.abstract_sku,
      quantity = product_abstract_availability.quantity
    FROM product_abstract_availability
    WHERE spy_availability_abstract.id_availability_abstract = product_abstract_availability.idAvailabilityAbstract
    RETURNING spy_availability_abstract.abstract_sku as abstractSku,id_availability_abstract
  ),
    inserted_product_abstract AS (
    INSERT INTO spy_availability_abstract (
      id_availability_abstract,
      abstract_sku,
      quantity,
      fk_store
    ) (
      SELECT
        nextval('spy_availability_abstract_pk_seq'),
        abstract_sku,
        quantity,
        fk_store
      FROM product_abstract_availability
      WHERE idAvailabilityAbstract is null
    ) RETURNING abstract_sku as abstractSku,id_availability_abstract
  ),
  product_availability_results AS (
    SELECT
      product_availability.sku,
      product_availability.quantity,
      product_availability.fk_store,
      product_availability.is_never_out_of_stock,
      product_availability.idAvailability,
      apa.id_availability_abstract
    FROM product_availability
    INNER JOIN (
        SELECT inserted_product_abstract.abstractSku, id_availability_abstract FROM inserted_product_abstract 
        UNION ALL 
        SELECT updated_product_abstract.abstractSku, id_availability_abstract FROM updated_product_abstract
    ) as apa ON apa.abstractSku = product_availability.abstract_sku
  ),
  updated_product AS (
    UPDATE spy_availability
    SET
      is_never_out_of_stock = product_availability_results.is_never_out_of_stock,
      quantity = product_availability_results.quantity
    FROM product_availability_results
    WHERE spy_availability.id_availability = product_availability_results.idAvailability
    RETURNING id_availability
  ),
    inserted_product AS (
    INSERT INTO spy_availability (
      id_availability,
      fk_availability_abstract,
      sku,
      quantity,
      is_never_out_of_stock,
      fk_store
    ) (
      SELECT
        nextval('spy_availability_pk_seq'),
        id_availability_abstract,
        sku,
        quantity,
        is_never_out_of_stock,
        fk_store
      FROM product_availability_results
      WHERE idAvailability is null
    ) RETURNING id_availability
  )
  SELECT inserted_product.id_availability FROM inserted_product UNION ALL SELECT updated_product.id_availability FROM updated_product";

        return $sql;
    }
}
