<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage\Business\Storage\Cte;

use Pyz\Zed\Propel\Business\CTE\MariaDbDataFormatterTrait;
use Pyz\Zed\Propel\PropelConfig;

class ProductConcreteStorageMariaDbCte implements ProductStorageCteStrategyInterface
{
    use MariaDbDataFormatterTrait;

    /**
     * @param array $data
     *
     * @return array
     */
    public function buildParams(array $data): array
    {
        $foreignKeysRaw = array_column($data, 'fk_product');
        $rowsCount = count($foreignKeysRaw);
        $foreignKeys = $this->formatStringList($foreignKeysRaw);
        $locales = $this->formatStringList(array_column($data, 'locale'));
        $formattedData = $this->formatDataStringList(array_column($data, 'data'));
        $keys = $this->formatStringList(array_column($data, 'key'));

        return [
            (string)$rowsCount,
            $foreignKeys,
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
            INSERT INTO spy_product_concrete_storage(
                id_product_concrete_storage,
                fk_product,
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
                    input.fk_product,
                    input.locale,
                    input.data,
                    input.key,
                    id_product_concrete_storage
                FROM (
                   SELECT DISTINCT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fk_products, ',', n.digit + 1), ',', -1), '') as fk_product,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.locales, ',', n.digit + 1), ',', -1), '') as locale,
                        REPLACE(NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.datas, ',', n.digit + 1), ',', -1), ''), '|', ',') as data,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.keys, ',', n.digit + 1), ',', -1), '') as `key`
                   FROM (
                        SELECT ? as fk_products,
                               ? as locales,
                               ? as datas,
                               ? as `keys`
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(fk_products, ',', '')) <= LENGTH(fk_products) - n.digit
                        AND LENGTH(REPLACE(locales, ',', '')) <= LENGTH(locales) - n.digit
                        AND LENGTH(REPLACE(datas, ',', '')) <= LENGTH(datas) - n.digit
                        AND LENGTH(REPLACE(`keys`, ',', '')) <= LENGTH(`keys`) - n.digit
                 ) input
                LEFT JOIN spy_product_concrete_storage ON spy_product_concrete_storage.key = input.key
            )
            (
              SELECT
                id_product_concrete_storage,
                fk_product,
                locale,
                data,
                `key`,
                now(),
                now()
              FROM records
            )
            ON DUPLICATE KEY UPDATE
                fk_product = records.fk_product,
                locale = records.locale,
                data = records.data,
                `key` = records.key,
                updated_at = now()
        SQL;
    }

    /**
     * @return string[]
     */
    public function getCompatibleEngines(): array
    {
        return [
            PropelConfig::DB_ENGINE_MYSQL,
        ];
    }
}
