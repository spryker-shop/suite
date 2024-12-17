<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

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
    public function createOrUpdateProductImageSQL(): string;

    /**
     * @return string
     */
    public function createProductImageSetRelationSQL(): string;

    /**
     * @return string
     */
    public function findProductImageSetsByProductImageIds(): string;
}
