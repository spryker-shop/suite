<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql;

class ProductAbstractStoreMariaDbSql implements ProductAbstractStoreSqlInterface
{
    /**
     * @return string
     */
    public function createAbstractProductStoreSQL(): string
    {
        return '
            INSERT INTO spy_product_abstract_store (
              id_product_abstract_store,
              fk_product_abstract,
              fk_store
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ),
            records AS (
                SELECT
                  input.abstract_sku,
                  input.store_name,
                  id_product_abstract,
                  id_store,
                  id_product_abstract_store as idProductAbstractStore
                FROM (
                       SELECT DISTINCT
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.abstractSkus, \',\', n.digit + 1), \',\', -1), \'\') as abstract_sku,
                            NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.storeNames, \',\', n.digit + 1), \',\', -1), \'\') as store_name
                       FROM (
                            SELECT ? as abstractSkus,
                                   ? as storeNames
                       ) temp
                       INNER JOIN n
                          ON LENGTH(REPLACE(abstractSkus, \',\', \'\')) <= LENGTH(abstractSkus) - n.digit
                            AND LENGTH(REPLACE(storeNames, \',\', \'\')) <= LENGTH(storeNames) - n.digit
                     ) input
                 INNER JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
                 INNER JOIN spy_store ON spy_store.name = input.store_name
                 LEFT JOIN spy_product_abstract_store ON spy_product_abstract_store.fk_product_abstract = id_product_abstract
                        AND spy_product_abstract_store.fk_store = spy_store.id_store
            )
            (
              SELECT
                idProductAbstractStore,
                id_product_abstract,
                id_store
              FROM records
            )
        ';
    }
}
