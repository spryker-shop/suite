<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql;

interface ProductImageSqlInterface
{
    /**
     * @return string
     */
    public function createProductImageSetSQL(): string;

    /**
     * @return string
     */
    public function createProductImageSQL(): string;

    /**
     * @return string
     */
    public function createProductImageSetRelationSQL(): string;
}
