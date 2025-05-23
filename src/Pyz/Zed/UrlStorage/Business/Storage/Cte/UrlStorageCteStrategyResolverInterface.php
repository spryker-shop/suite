<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\UrlStorage\Business\Storage\Cte;

interface UrlStorageCteStrategyResolverInterface
{
    /**
     * @return \Pyz\Zed\UrlStorage\Business\Storage\Cte\UrlStorageCteInterface
     */
    public function resolve(): UrlStorageCteInterface;
}
