<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql;

interface ProductAbstractSqlInterface
{
    /**
     * @return string
     */
    public function createAbstractProductSQL(): string;

    /**
     * @return string
     */
    public function createAbstractProductLocalizedAttributesSQL(): string;

    /**
     * @return string
     */
    public function createAbstractProductCategoriesSQL(): string;

    /**
     * @return string
     */
    public function createAbstractProductUrlsSQL(): string;
}
