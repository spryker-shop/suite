<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UrlStorage\Business\Storage\Cte;

use Pyz\Zed\Propel\Business\CTE\PostgresDataFormatterTrait;
use Pyz\Zed\Propel\PropelConfig;

class UrlStoragePostgresCte implements UrlStorageCteInterface
{
    use PostgresDataFormatterTrait;

    /**
     * @param array $data
     *
     * @return array
     */
    public function buildParams(array $data): array
    {
        $foreignKeys = $this->formatPostgresArray(array_column($data, 'fk_url'));
        $urls = $this->formatPostgresArrayString(array_column($data, 'url'));
        $formattedData = $this->formatPostgresArrayFromJson(array_column($data, 'data'));
        $keys = $this->formatPostgresArrayString(array_column($data, 'key'));

        return [
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
            WITH records AS (
                SELECT
                  input.fk_url,
                  input.url,
                  input.data,
                  input.key,
                  id_url_storage
                FROM (
                       SELECT
                         unnest(? :: INTEGER []) AS fk_url,
                         unnest(? :: VARCHAR []) AS url,
                         json_array_elements(?) AS data,
                         unnest(? :: VARCHAR []) AS key
                     ) input
                  LEFT JOIN spy_url_storage ON spy_url_storage.key = input.key
                ),
                updated AS (
                UPDATE spy_url_storage
                SET
                  fk_url = records.fk_url,
                  url = records.url,
                  data = records.data,
                  key = records.key,
                  updated_at = now()
                FROM records
                WHERE records.key = spy_url_storage.key
                RETURNING spy_url_storage.id_url_storage
              ),
                inserted AS (
                INSERT INTO spy_url_storage(
                  id_url_storage,
                  fk_url,
                  url,
                  data,
                  key,
                  created_at,
                  updated_at
                ) (
                  SELECT
                    nextval('spy_url_storage_pk_seq'),
                    fk_url,
                    url,
                    data,
                    key,
                    now(),
                    now()
                  FROM records
                  WHERE id_url_storage is null
                ) RETURNING spy_url_storage.id_url_storage
              )
            SELECT updated.id_url_storage FROM updated
            UNION ALL
            SELECT inserted.id_url_storage FROM inserted;
        SQL;
    }

    /**
     * @return string[]
     */
    public function getCompatibleEngines(): array
    {
        return [
            PropelConfig::DB_ENGINE_PGSQL,
        ];
    }
}
