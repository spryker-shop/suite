<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProductStorage\Business\Storage\Cte;

interface PriceProductStorageCteStrategyResolverInterface
{
    /**
     * @return \Pyz\Zed\PriceProductStorage\Business\Storage\Cte\PriceProductStorageCteInterface
     */
    public function resolve(): PriceProductStorageCteInterface;
}
