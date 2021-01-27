<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Propel\Business\CTE;

trait PostgresDataFormatterTrait
{
    /**
     * @param array $values
     *
     * @return string
     */
    public function formatPostgresArray(array $values): string
    {
        if (!$values) {
            return '{null}';
        }

        $values = array_map(function ($value) {
            return ($value === null || $value === '') ? 'NULL' : $value;
        }, $values);

        return sprintf(
            '{%s}',
            pg_escape_string(implode(',', $values))
        );
    }

    /**
     * @param array $values
     *
     * @return string
     */
    public function formatPostgresArrayString(array $values): string
    {
        return sprintf(
            '{"%s"}',
            pg_escape_string(implode('","', $values))
        );
    }

    /**
     * @param array $values
     *
     * @return string
     */
    public function formatPostgresArrayFromJson(array $values): string
    {
        return sprintf(
            '[%s]',
            pg_escape_string(implode(',', $values))
        );
    }
}
