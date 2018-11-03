<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\DataFormatter;

class DataImportDataFormatter implements DataImportDataFormatterInterface
{
    /**
     * @param string $value
     * @param string $replace
     *
     * @return string
     */
    public function replaceDoubleQuotes(string $value, string $replace = ''): string
    {
        return str_replace('"', $replace, $value);
    }

    /**
     * @param array $values
     *
     * @return string
     */
    public function formatPostgresArray(array $values): string
    {
        if (is_array($values) && empty($values)) {
            return '{null}';
        }

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

    /**
     * @param array $collection
     * @param string $key
     *
     * @return array
     */
    public function getCollectionDataByKey(array $collection, string $key)
    {
        return array_column($collection, $key);
    }
}
