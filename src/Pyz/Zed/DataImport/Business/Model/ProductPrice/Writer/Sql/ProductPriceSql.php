<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql;

class ProductPriceSql implements ProductPriceSqlInterface
{
    /**
     * @param string $idProduct
     * @param string $productTable
     * @param string $productFkKey
     *
     * @return string
     */
    public function createProductPriceSQL(string $idProduct, string $productTable, string $productFkKey): string
    {
        $sql = sprintf(
            "WITH records AS (
    SELECT
      input.sku,
      input.price_type_name,
      %2\$s.%1\$s,
      spy_price_type.id_price_type,
      spy_price_product.id_price_product as idPriceProduct
    FROM (
           SELECT
             unnest(?::VARCHAR []) AS sku,
             unnest(?::VARCHAR[]) AS price_type_name
         ) input
      INNER JOIN spy_price_type ON spy_price_type.name = input.price_type_name
      INNER JOIN %2\$s ON %2\$s.sku = input.sku    
      LEFT JOIN spy_price_product ON (spy_price_product.fk_price_type = spy_price_type.id_price_type AND spy_price_product.%3\$s = %1\$s)
),
    updated AS (
    UPDATE spy_price_product
    SET
      %3\$s = records.%1\$s,
      fk_price_type = records.id_price_type
    FROM records
    WHERE idPriceProduct IS NOT NULL AND spy_price_product.id_price_product = idPriceProduct
    RETURNING id_price_product, spy_price_product.%3\$s as %1\$s
  ),
    inserted AS(
    INSERT INTO spy_price_product (
      id_price_product,
      fk_price_type,
      %3\$s
    ) (
      SELECT
        nextval('spy_price_product_pk_seq'),
        id_price_type,
        %1\$s
    FROM records
    WHERE idPriceProduct IS NULL
  ) ON CONFLICT (fk_price_type, %3\$s) DO NOTHING
     RETURNING id_price_product, %3\$s as %1\$s
  )
SELECT updated.id_price_product,%1\$s FROM updated UNION ALL SELECT inserted.id_price_product,%1\$s FROM inserted;",
            $idProduct,
            $productTable,
            $productFkKey
        );

        return $sql;
    }

    /**
     * @return string
     */
    public function createPriceTypeSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.name,
      input.price_mode_configuration,
      spy_price_type.id_price_type as idPriceType
    FROM (
           SELECT
             DISTINCT(unnest(?::VARCHAR [])) AS name,
             unnest(?::INTEGER[]) AS price_mode_configuration
         ) input    
      LEFT JOIN spy_price_type ON spy_price_type.name = input.name
),
    updated AS (
    UPDATE spy_price_type
    SET
      name = records.name,
      price_mode_configuration = records.price_mode_configuration
    FROM records
    WHERE idPriceType IS NOT NULL AND spy_price_type.id_price_type = idPriceType
  ),
    inserted AS(
    INSERT INTO spy_price_type (
      id_price_type,
      name,
      price_mode_configuration
    ) (
      SELECT
        nextval('spy_price_type_pk_seq'),
        name,
        price_mode_configuration
    FROM records
    WHERE idPriceType IS NULL
  )
  )
SELECT 1;";

        return $sql;
    }

    /**
     * @param string $tableName
     * @param string $foreignKey
     * @param string $idProduct
     *
     * @return string
     */
    public function createPriceProductStoreSql(string $tableName, string $foreignKey, string $idProduct): string
    {
        $sql = sprintf("WITH records AS (
    SELECT
      input.gross_price,
      input.net_price,
      input.sku,
      spy_currency.id_currency,
      spy_store.id_store,
      spy_price_product.id_price_product,
      spy_price_product_store.id_price_product_store as idProductStore
      FROM (
           SELECT
             unnest(?::INTEGER []) AS gross_price,
             unnest(?::INTEGER[]) AS net_price,
             unnest(?::VARCHAR[]) AS currency,
             unnest(?::VARCHAR[]) AS store,
             unnest(?::VARCHAR[]) AS sku,
             unnest(?::VARCHAR[]) AS price_type
         ) input  
      INNER JOIN spy_price_type ON spy_price_type.name = input.price_type
      INNER JOIN spy_store ON spy_store.name = input.store
      INNER JOIN spy_currency ON spy_currency.code = input.currency
      INNER JOIN %1\$s ON %1\$s.sku = input.sku
      INNER JOIN spy_price_product ON (spy_price_product.%2\$s = %1\$s.%3\$s AND spy_price_product.fk_price_type = spy_price_type.id_price_type) 
      LEFT JOIN spy_price_product_store ON (spy_price_product_store.fk_price_product = id_price_product AND spy_price_product_store.fk_currency = id_currency AND spy_price_product_store.fk_store = spy_store.id_store)
),
 updated AS (
    UPDATE spy_price_product_store
    SET
      gross_price = records.gross_price,
      net_price = records.net_price
    FROM records
    WHERE spy_price_product_store.fk_store = records.id_store AND 
    spy_price_product_store.fk_price_product = records.id_price_product AND 
    spy_price_product_store.fk_currency = records.id_currency
  ),
    inserted AS(
    INSERT INTO spy_price_product_store (
      id_price_product_store,
      fk_currency,
      fk_store,
      gross_price,
      net_price,
      fk_price_product
    ) (
      SELECT
        nextval('spy_price_product_store_pk_seq'),
        id_currency,
        id_store,
        gross_price,
        net_price,
        id_price_product
    FROM records
    WHERE idProductStore IS NULL
  ) RETURNING id_price_product_store
  ),
    insertedDefault AS (
    INSERT INTO spy_price_product_default (
      id_price_product_default,
      fk_price_product_store
    ) (
      SELECT
        nextval('spy_price_product_default_pk_seq'),
        id_price_product_store
    FROM inserted
    )
  )
SELECT 1;", $tableName, $foreignKey, $idProduct);

        return $sql;
    }

    /**
     * @return string
     */
    public function createPriceProductDefaultSql(): string
    {
        $sql = "WITH records AS (
    SELECT
    spy_price_product_default.id_price_product_default as idPriceProductDefault,
      spy_price_product_store.id_price_product_store as idPriceProductStore
    FROM spy_price_product_store
    LEFT JOIN spy_price_product_default ON spy_price_product_store.id_price_product_store = spy_price_product_default.fk_price_product_store
),
    updated AS (
    UPDATE spy_price_product_default
    SET
      fk_price_product_store = records.idPriceProductStore
    FROM records
    WHERE id_price_product_default = records.idPriceProductDefault
    RETURNING id_price_product_default
  ),
    inserted AS(
    INSERT INTO spy_price_product_default (
      id_price_product_default,
      fk_price_product_store
    ) (
      SELECT
        nextval('spy_price_product_default_pk_seq'),
        records.idPriceProductStore
      FROM records
      WHERE idPriceProductDefault is null
    ) RETURNING id_price_product_default
  )
SELECT updated.id_price_product_default FROM updated UNION ALL SELECT inserted.id_price_product_default FROM inserted;";

        return $sql;
    }
}
