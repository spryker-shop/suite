<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage\Business\Storage\Cte;

use Pyz\Zed\Propel\Business\CTE\PostgresDataFormatterTrait;
use Pyz\Zed\Propel\PropelConfig;

class ProductConcreteStoragePostgresCte implements ProductStorageCteStrategyInterface
{
    use PostgresDataFormatterTrait;

    /**
     * @param array $data
     *
     * @return array
     */
    public function buildParams(array $data): array
    {
        $foreignKeys = $this->formatPostgresArray(array_column($data, 'fk_product'));
        $locales = $this->formatPostgresArrayString(array_column($data, 'locale'));
        $formattedData = $this->formatPostgresArrayFromJson(array_column($data, 'data'));
        $keys = $this->formatPostgresArrayString(array_column($data, 'key'));

        return [
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
            WITH records AS (
                SELECT
                  input.fk_product,
                  input.locale,
                  input.data,
                  input.key,
                  id_product_concrete_storage
                FROM (
                       SELECT
                         unnest(? :: INTEGER []) AS fk_product,
                         unnest(? :: VARCHAR []) AS locale,
                         json_array_elements(?) AS data,
                         unnest(? :: VARCHAR []) AS key
                     ) input
                  LEFT JOIN spy_product_concrete_storage ON spy_product_concrete_storage.key = input.key
                ),
                updated AS (
                UPDATE spy_product_concrete_storage
                SET
                  fk_product = records.fk_product,
                  locale = records.locale,
                  data = records.data,
                  key = records.key,
                  updated_at = now()
                FROM records
                WHERE records.key = spy_product_concrete_storage.key
                RETURNING spy_product_concrete_storage.id_product_concrete_storage
              ),
                inserted AS (
                INSERT INTO spy_product_concrete_storage(
                  id_product_concrete_storage,
                  fk_product,
                  locale,
                  data,
                  key,
                  created_at,
                  updated_at
                ) (
                  SELECT
                    nextval('spy_product_concrete_storage_pk_seq'),
                    fk_product,
                    locale,
                    data,
                    key,
                    now(),
                    now()
                  FROM records
                  WHERE id_product_concrete_storage is null
                ) RETURNING spy_product_concrete_storage.id_product_concrete_storage
              )
            SELECT updated.id_product_concrete_storage FROM updated
            UNION ALL
            SELECT inserted.id_product_concrete_storage FROM inserted;
        SQL;
    }

    /**
     * @return array<string>
     */
    public function getCompatibleEngines(): array
    {
        return [
            PropelConfig::DB_ENGINE_PGSQL,
        ];
    }
}
