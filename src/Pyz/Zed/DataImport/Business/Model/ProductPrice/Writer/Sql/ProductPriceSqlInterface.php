<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql;

interface ProductPriceSqlInterface
{
    /**
     * @param string $idProduct
     * @param string $productTable
     * @param string $productFkKey
     *
     * @return string
     */
    public function createPriceProductSQL(string $idProduct, string $productTable, string $productFkKey): string;

    /**
     * @param string $idProduct
     * @param string $productFkKey
     *
     * @return string
     */
    public function selectProductPriceSQL(string $idProduct, string $productFkKey): string;

    /**
     * @return string
     */
    public function collectPriceTypes(): string;

    /**
     * @return string
     */
    public function createPriceTypeSQL(): string;

    /**
     * @param string $tableName
     * @param string $foreignKey
     * @param string $idProduct
     *
     * @return string
     */
    public function createPriceProductStoreSql(string $tableName, string $foreignKey, string $idProduct): string;

    /**
     * @return string
     */
    public function createPriceProductDefaultSql(): string;

    /**
     * @return string
     */
    public function convertStoreNameToId(): string;

    /**
     * @return string
     */
    public function convertCurrencyNameToId(): string;

    /**
     * @param string $tableName
     * @param string $fieldName
     *
     * @return string
     */
    public function convertProductSkuToId(string $tableName, string $fieldName): string;
}
