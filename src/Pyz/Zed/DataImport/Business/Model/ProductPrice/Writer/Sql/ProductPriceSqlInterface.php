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
    public function createProductPriceSQL(string $idProduct, string $productTable, string $productFkKey): string;

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
}
