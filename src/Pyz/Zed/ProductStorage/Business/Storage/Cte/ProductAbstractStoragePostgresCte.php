<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage\Business\Storage\Cte;

use Pyz\Zed\Propel\Business\CTE\PostgresDataFormatterTrait;
use Pyz\Zed\Propel\PropelConfig;

class ProductAbstractStoragePostgresCte implements ProductStorageCteStrategyInterface
{
    use PostgresDataFormatterTrait;

    /**
     * @param array $data
     *
     * @return array
     */
    public function buildParams(array $data): array
    {
        $foreignKeys = $this->formatPostgresArray(array_column($data, 'fk_product_abstract'));
        $stores = $this->formatPostgresArrayString(array_column($data, 'store'));
        $locales = $this->formatPostgresArrayString(array_column($data, 'locale'));
        $formattedData = $this->formatPostgresArrayFromJson(array_column($data, 'data'));
        $keys = $this->formatPostgresArrayString(array_column($data, 'key'));

        return [
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
            WITH records AS (
                SELECT
                  input.fk_product_abstract,
                  input.store,
                  input.locale,
                  input.data,
                  input.key,
                  id_product_abstract_storage
                FROM (
                       SELECT
                         unnest(? :: INTEGER []) AS fk_product_abstract,
                         unnest(? :: VARCHAR []) AS store,
                         unnest(? :: VARCHAR []) AS locale,
                         json_array_elements(?) AS data,
                         unnest(? :: VARCHAR []) AS key
                     ) input
                  LEFT JOIN spy_product_abstract_storage ON spy_product_abstract_storage.key = input.key
                ),
                updated AS (
                UPDATE spy_product_abstract_storage
                SET
                  fk_product_abstract = records.fk_product_abstract,
                  store = records.store,
                  locale = records.locale,
                  data = records.data,
                  key = records.key,
                  updated_at = now()
                FROM records
                WHERE records.key = spy_product_abstract_storage.key
                RETURNING spy_product_abstract_storage.id_product_abstract_storage
              ),
                inserted AS (
                INSERT INTO spy_product_abstract_storage(
                  id_product_abstract_storage,
                  fk_product_abstract,
                  store,
                  locale,
                  data,
                  key,
                  created_at,
                  updated_at
                ) (
                  SELECT
                    nextval('spy_product_abstract_storage_pk_seq'),
                    fk_product_abstract,
                    store,
                    locale,
                    data,
                    key,
                    now(),
                    now()
                  FROM records
                  WHERE id_product_abstract_storage is null
                ) RETURNING spy_product_abstract_storage.id_product_abstract_storage
              )
            SELECT updated.id_product_abstract_storage FROM updated
            UNION ALL
            SELECT inserted.id_product_abstract_storage FROM inserted;
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
