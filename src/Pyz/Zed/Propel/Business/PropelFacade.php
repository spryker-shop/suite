<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Propel\Business;

use Spryker\Zed\Propel\Business\PropelFacade as SprykerPropelFacade;

/**
 * @method \Pyz\Zed\Propel\Business\PropelBusinessFactory getFactory()
 */
class PropelFacade extends SprykerPropelFacade implements PropelFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return bool
     */
    public function checkCteSupport(): bool
    {
        return $this->getFactory()->createCteSupportChecker()->checkCteSupport();
    }
}
