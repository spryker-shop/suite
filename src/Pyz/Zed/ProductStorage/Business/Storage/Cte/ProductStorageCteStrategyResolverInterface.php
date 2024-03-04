<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductStorage\Business\Storage\Cte;

interface ProductStorageCteStrategyResolverInterface
{
    /**
     * @return \Pyz\Zed\ProductStorage\Business\Storage\Cte\ProductStorageCteStrategyInterface
     */
    public function resolve(): ProductStorageCteStrategyInterface;
}
