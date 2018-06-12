<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql;

class ProductAbstractStoreSql implements ProductAbstractStoreSqlInterface
{
    /**
     * @return string
     */
    public function createAbstractProductStoreSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.abstract_sku,
      input.store_name,
      id_product_abstract,
      id_store
    FROM (
           SELECT
             unnest(?::VARCHAR[]) AS abstract_sku,
             unnest(?::VARCHAR[]) AS store_name
         ) input
         INNER JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
         INNER JOIN spy_store ON spy_store.name = input.store_name
),
    inserted AS(
        INSERT INTO spy_product_abstract_store (
          id_product_abstract_store,
          fk_product_abstract,
          fk_store
        ) (
          SELECT
            nextval('id_product_abstract_store_pk_seq'),
            id_product_abstract,
            id_store
        FROM records
    ) ON CONFLICT DO NOTHING
)
SELECT 1;";

        return $sql;
    }
}
