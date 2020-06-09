<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process;

use Pyz\Yves\CheckoutPage\CheckoutPageDependencyProvider;
use Pyz\Yves\CheckoutPage\Process\Steps\SuccessStep;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use SprykerShop\Yves\CheckoutPage\Process\StepFactory as SprykerStepFactory;
use SprykerShop\Yves\HomePage\Plugin\Provider\HomePageControllerProvider;

class StepFactory extends SprykerStepFactory
{
    /**
     * @return \Pyz\Yves\CheckoutPage\Process\Steps\SuccessStep|\SprykerShop\Yves\CheckoutPage\Process\Steps\SuccessStep
     */
    public function createSuccessStep()
    {
        return new SuccessStep(
            $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_CUSTOMER),
            $this->getProvidedDependency(CheckoutPageDependencyProvider::CLIENT_PAYONE),
            $this->getConfig(),
            CheckoutPageControllerProvider::CHECKOUT_SUCCESS,
            HomePageControllerProvider::ROUTE_HOME
        );
    }
}
