<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Propel\Business;

use Spryker\Zed\Propel\Business\PropelFacadeInterface as SprykerPropelFacadeInterface;

interface PropelFacadeInterface extends SprykerPropelFacadeInterface
{
    /**
     * Specification:
     * - Checks if common table expressions are supported by the database engine in use.
     *
     * @api
     *
     * @return bool
     */
    public function checkCteSupport(): bool;
}
