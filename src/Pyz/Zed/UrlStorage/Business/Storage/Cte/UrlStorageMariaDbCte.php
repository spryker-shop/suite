<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UrlStorage\Business\Storage\Cte;

use Pyz\Zed\Propel\Business\CTE\MariaDbDataFormatterTrait;
use Pyz\Zed\Propel\PropelConfig;

class UrlStorageMariaDbCte implements UrlStorageCteInterface
{
    use MariaDbDataFormatterTrait;

    /**
     * @param array $data
     *
     * @return array
     */
    public function buildParams(array $data): array
    {
        $foreignKeysRaw = array_column($data, 'fk_url');
        $rowsCount = count($foreignKeysRaw);
        $foreignKeys = $this->formatStringList($foreignKeysRaw);
        $urls = $this->formatStringList(array_column($data, 'url'));
        $formattedData = $this->formatDataStringList(array_column($data, 'data'));
        $keys = $this->formatStringList(array_column($data, 'key'));

        return [
            (string)$rowsCount,
            $foreignKeys,
            $urls,
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
            INSERT INTO spy_url_storage(
                id_url_storage,
                fk_url,
                url,
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
                    input.fk_url,
                    input.url,
                    input.data,
                    input.key,
                    id_url_storage
                FROM (
                   SELECT DISTINCT
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.fk_urls, ',', n.digit + 1), ',', -1), '') as fk_url,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.urls, ',', n.digit + 1), ',', -1), '') as url,
                        REPLACE(NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.datas, ',', n.digit + 1), ',', -1), ''), '|', ',') as data,
                        NULLIF(SUBSTRING_INDEX(SUBSTRING_INDEX(temp.keys, ',', n.digit + 1), ',', -1), '') as `key`
                   FROM (
                        SELECT ? as fk_urls,
                               ? as urls,
                               ? as datas,
                               ? as `keys`
                   ) temp
                   INNER JOIN n
                      ON LENGTH(REPLACE(fk_urls, ',', '')) <= LENGTH(fk_urls) - n.digit
                        AND LENGTH(REPLACE(urls, ',', '')) <= LENGTH(urls) - n.digit
                        AND LENGTH(REPLACE(datas, ',', '')) <= LENGTH(datas) - n.digit
                        AND LENGTH(REPLACE(`keys`, ',', '')) <= LENGTH(`keys`) - n.digit
                ) input
                LEFT JOIN spy_url_storage ON spy_url_storage.key = input.key
            )
            (
              SELECT
                id_url_storage,
                fk_url,
                url,
                data,
                `key`,
                now(),
                now()
              FROM records
            )
            ON DUPLICATE KEY UPDATE
                fk_url = records.fk_url,
                url = records.url,
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
