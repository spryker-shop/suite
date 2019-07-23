<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql;

interface ProductImageSqlInterface
{
    /**
     * @param string $idProduct
     * @param string $fkProduct
     *
     * @return string
     */
    public function createProductImageSetSQL(string $idProduct, string $fkProduct): string;

    /**
     * @return string
     */
    public function createProductImageSQL(): string;

    /**
     * @return string
     */
    public function createProductImageSetRelationSQL(): string;

    /**
     * @return string
     */
    public function convertLocaleNameToId(): string;

    /**
     * @param string $tableName
     * @param string $fieldName
     *
     * @return string
     */
    public function convertProductSkuToId(string $tableName, string $fieldName): string;

    /**
     * @return string
     */
    public function convertImageNameToId(): string;

    /**
     * @param string $fkProductKey
     *
     * @return string
     */
    public function findProductImageSetIds(string $fkProductKey): string;
}
