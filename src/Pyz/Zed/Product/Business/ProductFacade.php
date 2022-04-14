<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business;

use Spryker\Zed\Product\Business\ProductFacade as SprykerProductFacade;
use Spryker\Zed\Product\Business\ProductFacadeInterface;
use Spryker\Zed\ProductStorage\Dependency\Facade\ProductStorageToProductInterface;

class ProductFacade extends SprykerProductFacade implements ProductFacadeInterface, ProductStorageToProductInterface
{
}
