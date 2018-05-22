<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\AbstractBulkPdoWriter;

trait AbstractBulkPdoWriterTrait
{
    /**
     * @param array $values
     *
     * @return string
     */
    protected function formatPostgresArray(array $values): string
    {
        if (is_array($values) && empty($values)) {
            return '{null}';
        }

        return sprintf(
            '{%s}',
            implode(',', $values)
        );
    }

    /**
     * @param array $values
     *
     * @return string
     */
    protected function formatPostgresArrayString(array $values): string
    {
        return sprintf(
            '{"%s"}',
            implode('","', $values)
        );
    }

    /**
     * @param array $values
     *
     * @return string
     */
    protected function formatPostgresArrayFromJson(array $values): string
    {
        return sprintf(
            '[%s]',
            implode(',', $values)
        );
    }
}