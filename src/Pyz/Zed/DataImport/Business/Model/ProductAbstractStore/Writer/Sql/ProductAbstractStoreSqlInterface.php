<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql;

interface ProductAbstractStoreSqlInterface
{
    /**
     * @return string
     */
    public function createAbstractProductStoreSQL(): string;
}
