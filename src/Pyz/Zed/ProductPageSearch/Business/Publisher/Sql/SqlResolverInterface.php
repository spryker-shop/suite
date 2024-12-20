<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ProductPageSearch\Business\Publisher\Sql;

interface SqlResolverInterface
{
    /**
     * @return \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface
     */
    public function resolve(): ProductPagePublisherCteInterface;
}
