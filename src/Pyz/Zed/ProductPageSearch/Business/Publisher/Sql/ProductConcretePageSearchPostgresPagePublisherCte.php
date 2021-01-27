<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Business\Publisher\Sql;

use Pyz\Zed\Propel\Business\CTE\PostgresDataFormatterTrait;
use Spryker\Zed\Propel\PropelConfig;

class ProductConcretePageSearchPostgresPagePublisherCte implements ProductPagePublisherCteInterface
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
        $locales = $this->formatPostgresArrayString(array_column($data, 'locale'));
        $dataField = $this->formatPostgresArrayFromJson(array_column($data, 'data'));
        $structuredData = $this->formatPostgresArrayFromJson(array_column($data, 'structured_data'));
        $keys = $this->formatPostgresArrayString(array_column($data, 'key'));

        return [
            $foreignKeys,
            $stores,
            $locales,
            $dataField,
            $structuredData,
            $keys,
        ];
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
                  input.locale,
                  input.data,
                  input.structured_data,
                  input.key,
                  id_product_concrete_page_search
                FROM (
                       SELECT
                         unnest(? :: INTEGER []) AS fk_product,
                         unnest(? :: VARCHAR []) AS store,
                         unnest(? :: VARCHAR []) AS locale,
                         json_array_elements(?) AS data,
                         json_array_elements(?) AS structured_data,
                         unnest(? :: VARCHAR []) AS key
                     ) input
                  LEFT JOIN spy_product_concrete_page_search ON spy_product_concrete_page_search.key = input.key
                ),
                updated AS (
                UPDATE spy_product_concrete_page_search
                SET
                  fk_product = records.fk_product,
                  store = records.store,
                  locale = records.locale,
                  data = records.data,
                  structured_data = records.structured_data,
                  key = records.key,
                  updated_at = now()
                FROM records
                WHERE records.key = spy_product_concrete_page_search.key
                RETURNING spy_product_concrete_page_search.id_product_concrete_page_search
              ),
                inserted AS (
                INSERT INTO spy_product_concrete_page_search(
                  id_product_concrete_page_search,
                  fk_product,
                  store,
                  locale,
                  data,
                  structured_data,
                  key,
                  created_at,
                  updated_at
                ) (
                  SELECT
                    nextval('spy_product_concrete_page_search_pk_seq'),
                    fk_product,
                    store,
                    locale,
                    data,
                    structured_data,
                    key,
                    now(),
                    now()
                  FROM records
                  WHERE id_product_concrete_page_search is null
                ) RETURNING spy_product_concrete_page_search.id_product_concrete_page_search
              )
            SELECT updated.id_product_concrete_page_search FROM updated
            UNION ALL
            SELECT inserted.id_product_concrete_page_search FROM inserted;
        SQL;
    }
}
