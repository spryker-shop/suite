<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\DataFormatter;

interface DataImportDataFormatterInterface
{
    /**
     * @param string $value
     * @param string $replace
     *
     * @return string
     */
    public function replaceDoubleQuotes(string $value, string $replace = ''): string;

    /**
     * @param array<mixed> $values
     *
     * @return string
     */
    public function formatPostgresArray(array $values): string;

    /**
     * @param array<mixed> $values
     *
     * @return string
     */
    public function formatPostgresArrayString(array $values): string;

    /**
     * @param array<mixed> $values
     *
     * @return string
     */
    public function formatPostgresArrayBoolean(array $values): string;

    /**
     * @param array<mixed> $values
     *
     * @return string
     */
    public function formatPostgresArrayFromJson(array $values): string;

    /**
     * @param array<mixed> $collection
     * @param string $key
     *
     * @return array<mixed>
     */
    public function getCollectionDataByKey(array $collection, string $key): array;

    /**
     * @param array<mixed> $priceData
     *
     * @return string
     */
    public function formatPostgresPriceDataString(array $priceData): string;

    /**
     * @param array<mixed> $values
     * @param int|null $minimumLength
     *
     * @return string
     */
    public function formatStringList(array $values, ?int $minimumLength = null): string;

    /**
     * @param array<mixed> $values
     * @param int|null $minimumLength
     *
     * @return string
     */
    public function formatPriceStringList(array $values, ?int $minimumLength = null): string;
}
