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
    public function createPriceProductSQL(string $idProduct, string $productTable, string $productFkKey): string
    {
        $sql = sprintf(
            "WITH records AS (
    SELECT
      input.%1\$s,
      input.id_price_type,
      spy_price_product.id_price_product as idPriceProduct
    FROM (
           SELECT
             unnest(?::INTEGER []) AS %1\$s,
             unnest(?::INTEGER[]) AS id_price_type
         ) input
      LEFT JOIN spy_price_product ON (spy_price_product.fk_price_type = input.id_price_type AND spy_price_product.%3\$s = input.%1\$s)
),
    updated AS (
    UPDATE spy_price_product
    SET
      %3\$s = records.%1\$s,
      fk_price_type = records.id_price_type
    FROM records
    WHERE idPriceProduct IS NOT NULL AND spy_price_product.id_price_product = idPriceProduct
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
  )
    ON CONFLICT DO NOTHING
  ) SELECT 1",
            $idProduct,
            $productTable,
            $productFkKey,
        );

        return $sql;
    }

    /**
     * @param string $idProduct
     * @param string $productFkKey
     *
     * @return string
     */
    public function selectProductPriceSQL(string $idProduct, string $productFkKey): string
    {
        $sql = sprintf("WITH records AS (
    SELECT
      input.%1\$s,
      input.id_price_type,
      input.sort_key,
      spy_price_product.id_price_product
            FROM (
                   SELECT
                     unnest(?::INTEGER []) AS %1\$s,
                     unnest(?::INTEGER[]) AS id_price_type,
                     unnest(?::INTEGER[]) AS sort_key
                 ) input
      LEFT JOIN spy_price_product ON (spy_price_product.fk_price_type = input.id_price_type AND spy_price_product.%2\$s = input.%1\$s)
) SELECT records.%1\$s, records.id_price_product FROM records ORDER BY records.sort_key;", $idProduct, $productFkKey);

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
      input.order_key,
      spy_price_type.id_price_type as price_type_id
    FROM (
           SELECT
             unnest(?::VARCHAR []) AS name,
             unnest(?::INTEGER[]) AS price_mode_configuration,
             unnest(?::INTEGER[]) AS order_key
         ) input    
      LEFT JOIN spy_price_type ON spy_price_type.name = input.name
),
    updated AS (
        UPDATE spy_price_type
            SET
              name = records.name,
              price_mode_configuration = records.price_mode_configuration
            FROM records
            WHERE price_type_id IS NOT NULL AND spy_price_type.id_price_type = price_type_id
),
    inserted AS (
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
        WHERE price_type_id IS NULL
      ) ON CONFLICT DO NOTHING
) SELECT 1";

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
       input.id_store,
       input.id_currency,
       input.%3\$s,
       input.id_price_product,
       input.gross_price,
       input.net_price,
       spy_price_product_store.id_price_product_store as idPriceProductStore,
       input.price_data,
       input.price_data_checksum
    FROM (
           SELECT
             unnest(?::INTEGER[]) AS id_store,
             unnest(?::INTEGER[]) AS id_currency,
             unnest(?::INTEGER[]) AS %3\$s,
             unnest(?::INTEGER[]) AS id_price_product,
             unnest(?::INTEGER[]) AS gross_price,
             unnest(?::INTEGER[]) AS net_price,
             json_array_elements_text(?) AS price_data,
             unnest(?::VARCHAR[]) AS price_data_checksum
         ) input
            LEFT JOIN spy_price_product_store ON (
                spy_price_product_store.fk_price_product = input.id_price_product AND
                spy_price_product_store.fk_currency = id_currency AND
                spy_price_product_store.fk_store = id_store
            )
    ),
      updated AS (
        UPDATE spy_price_product_store
        SET
          gross_price = records.gross_price,
          net_price = records.net_price,
          price_data = records.price_data,
          price_data_checksum = records.price_data_checksum
        FROM records
        WHERE spy_price_product_store.fk_price_product = records.id_price_product AND
              spy_price_product_store.fk_store = records.id_store AND 
              spy_price_product_store.fk_currency = records.id_currency
        RETURNING idPriceProductStore as id_price_product_store      
      ),
        inserted AS (
            INSERT INTO spy_price_product_store (
              id_price_product_store,
              fk_store,
              fk_currency,
              fk_price_product,
              net_price,
              gross_price,
              price_data,
              price_data_checksum
            ) (
              SELECT
                nextval('spy_price_product_store_pk_seq'),
                id_store,
                id_currency,
                id_price_product,
                net_price,
                gross_price,
                price_data,
                price_data_checksum
              FROM records
              WHERE records.id_price_product IS NOT NULL AND records.idPriceProductStore IS NULL
      ) RETURNING id_price_product_store
  )
SELECT updated.id_price_product_store FROM updated UNION ALL SELECT inserted.id_price_product_store FROM inserted;", $tableName, $foreignKey, $idProduct);

        return $sql;
    }

    /**
     * @return string
     */
    public function createPriceProductDefaultSql(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.id_price_product_store,
      spy_price_product_default.id_price_product_default as price_product_default_id
    FROM (
           SELECT
             unnest(?::INTEGER[]) AS id_price_product_store
         ) input    
      LEFT JOIN spy_price_product_default ON spy_price_product_default.fk_price_product_store = input.id_price_product_store
),
    updated AS (
        UPDATE spy_price_product_default
        SET
          fk_price_product_store = records.id_price_product_store
        FROM records
        WHERE price_product_default_id IS NOT NULL AND spy_price_product_default.id_price_product_default = records.price_product_default_id
  ),
    inserted AS(
        INSERT INTO spy_price_product_default (
          id_price_product_default,
          fk_price_product_store
        ) (
          SELECT
            nextval('spy_price_product_default_pk_seq'),
            records.id_price_product_store
          FROM records
          WHERE price_product_default_id IS NULL
        ) ON CONFLICT DO NOTHING
  )
SELECT 1;";

        return $sql;
    }

    /**
     * @return string
     */
    public function convertStoreNameToId(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.sortKey,
      input.store,
      spy_store.id_store as id_store
    FROM (
           SELECT
             unnest(?::INTEGER[]) AS sortKey,
             unnest(?::VARCHAR[]) AS store
         ) input
        LEFT JOIN spy_store ON spy_store.name = input.store
) SELECT records.id_store FROM records ORDER BY records.sortKey;";

        return $sql;
    }

    /**
     * @return string
     */
    public function convertCurrencyNameToId(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.sortKey,
      input.currency,
      spy_currency.id_currency as id_currency
    FROM (
           SELECT
             unnest(?::INTEGER[]) AS sortKey,
             unnest(?::VARCHAR[]) AS currency
         ) input
        LEFT JOIN spy_currency ON spy_currency.code = input.currency
) SELECT records.id_currency FROM records ORDER BY records.sortKey;";

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
      input.sortKey,
      input.sku,
      %1\$s.%2\$s as %2\$s
    FROM (
           SELECT
             unnest(?::INTEGER[]) AS sortKey,
             unnest(?::VARCHAR[]) AS sku
         ) input
        LEFT JOIN %1\$s ON %1\$s.sku = input.sku
) SELECT records.%2\$s FROM records ORDER BY records.sortKey;", $tableName, $fieldName);

        return $sql;
    }

    /**
     * @return string
     */
    public function collectPriceTypes(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.price_type,
      input.sortKey,
      spy_price_type.id_price_type
    FROM (
           SELECT
             unnest(?::VARCHAR[]) AS price_type,
             unnest(?::INTEGER[]) AS sortKey
         ) input
        LEFT JOIN spy_price_type ON spy_price_type.name = input.price_type
) SELECT records.id_price_type FROM records ORDER BY records.sortKey;";

        return $sql;
    }
}
