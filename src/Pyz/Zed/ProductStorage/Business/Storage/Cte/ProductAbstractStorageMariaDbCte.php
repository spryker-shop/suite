<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage\Business\Storage\Cte;

use Pyz\Zed\Propel\Business\CTE\MariaDbDataFormatterTrait;
use Pyz\Zed\Propel\PropelConfig;

class ProductAbstractStorageMariaDbCte implements ProductStorageCteStrategyInterface
{
    use MariaDbDataFormatterTrait;

    /**
     * @param array $data
     *
     * @return array
     */
    public function buildParams(array $data): array
    {
        $foreignKeysRaw = array_column($data, 'fk_product_abstract');
        $rowsCount = count($foreignKeysRaw);
        $foreignKeys = $this->formatStringList($foreignKeysRaw);
        $stores = $this->formatStringList(array_column($data, 'store'));
        $locales = $this->formatStringList(array_column($data, 'locale'));
        $formattedData = $this->formatDataStringList(array_column($data, 'data'));
        $keys = $this->formatStringList(array_column($data, 'key'));

        return [
            (string)$rowsCount,
            $foreignKeys,
            $stores,
            $locales,
            $formattedData,
            $keys,
        ];
    }

    /**
     * @return string
     */
    public function getSql(): string
    {
        return <<<SQL
            INSERT INTO spy_product_abstract_storage(
                id_product_abstract_storage,
                fk_product_abstract,
                store,
                locale,
                data,
                `key`,
                created_at,
                updated_at
            )
            WITH RECURSIVE n(digit) AS (
                SELECT 0 as digit
                UNION ALL
                SELECT 1 + digit FROM n WHERE digit < ?
            ), records AS (
                SELECT
                  input.fk_product_abstract,
                  input.store,
                  input.locale,
                  input.data,
                  input.key,
                  id_product_abstract_storage
                FROM (
                   SELECT DISTINCT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fk_product_abstracts, ',', n.digit + 1), ',', -1), '') as fk_product_abstract,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.stores, ',', n.digit + 1), ',', -1), '') as store,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.locales, ',', n.digit + 1), ',', -1), '') as locale,
                        REPLACE(NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.datas, ',', n.digit + 1), ',', -1), ''), '|', ',') as data,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.keys, ',', n.digit + 1), ',', -1), '') as `key`
                   FROM (
                        SELECT ? as fk_product_abstracts,
                               ? as stores,
                               ? as locales,
                               ? as datas,
                               ? as `keys`
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(fk_product_abstracts, ',', '')) <= LENGTH(fk_product_abstracts) - n.digit
                        AND LENGTH(REPLACE(stores, ',', '')) <= LENGTH(stores) - n.digit
                        AND LENGTH(REPLACE(locales, ',', '')) <= LENGTH(locales) - n.digit
                        AND LENGTH(REPLACE(datas, ',', '')) <= LENGTH(datas) - n.digit
                        AND LENGTH(REPLACE(`keys`, ',', '')) <= LENGTH(`keys`) - n.digit
                 ) input
                LEFT JOIN spy_product_abstract_storage ON spy_product_abstract_storage.key = input.key
            )
            (
              SELECT
                id_product_abstract_storage,
                fk_product_abstract,
                store,
                locale,
                data,
                `key`,
                now(),
                now()
              FROM records
            )
            ON DUPLICATE KEY UPDATE
                fk_product_abstract = records.fk_product_abstract,
                store = records.store,
                locale = records.locale,
                data = records.data,
                `key` = records.key,
                updated_at = now()
        SQL;
    }

    /**
     * @return array<string>
     */
    public function getCompatibleEngines(): array
    {
        return [
            PropelConfig::DB_ENGINE_MYSQL,
        ];
    }
}
