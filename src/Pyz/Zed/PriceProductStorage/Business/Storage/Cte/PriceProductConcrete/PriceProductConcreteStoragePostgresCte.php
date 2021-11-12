<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductConcrete;

use Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface;
use Pyz\Zed\Propel\Business\CTE\PostgresDataFormatterTrait;
use Pyz\Zed\Propel\PropelConfig;

class PriceProductConcreteStoragePostgresCte implements PriceProductStorageCteInterface
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
        $stores = $this->formatPostgresArrayString(array_column($data, 'store'));
        $formattedData = $this->formatPostgresArrayFromJson(array_column($data, 'data'));
        $keys = $this->formatPostgresArrayString(array_column($data, 'key'));

        return [
            $foreignKeys,
            $stores,
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
                  input.store,
                  input.data,
                  input.key,
                  id_price_product_concrete_storage
                FROM (
                       SELECT
                         unnest(? :: INTEGER []) AS fk_product,
                         unnest(? :: VARCHAR []) AS store,
                         json_array_elements(?) AS data,
                         unnest(? :: VARCHAR []) AS key
                     ) input
                  LEFT JOIN spy_price_product_concrete_storage ON spy_price_product_concrete_storage.key = input.key
                ),
                updated AS (
                UPDATE spy_price_product_concrete_storage
                SET
                  fk_product = records.fk_product,
                  store = records.store,
                  data = records.data,
                  key = records.key,
                  updated_at = now()
                FROM records
                WHERE records.key = spy_price_product_concrete_storage.key
                RETURNING spy_price_product_concrete_storage.id_price_product_concrete_storage
              ),
                inserted AS (
                INSERT INTO spy_price_product_concrete_storage(
                  id_price_product_concrete_storage,
                  fk_product,
                  store,
                  data,
                  key,
                  created_at,
                  updated_at
                ) (
                  SELECT
                    nextval('spy_price_product_concrete_storage_pk_seq'),
                    fk_product,
                    store,
                    data,
                    key,
                    now(),
                    now()
                  FROM records
                  WHERE id_price_product_concrete_storage is null
                ) RETURNING spy_price_product_concrete_storage.id_price_product_concrete_storage
              )
            SELECT updated.id_price_product_concrete_storage FROM updated
            UNION ALL
            SELECT inserted.id_price_product_concrete_storage FROM inserted;
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
