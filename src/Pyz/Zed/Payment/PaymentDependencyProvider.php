<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Payment;

use Pyz\Zed\Payment\Communication\Plugin\Payment\SkipFirstPaymentMethodFilterPlugin;
use Spryker\Zed\Payment\PaymentDependencyProvider as BasePaymentDependencyProvider;

class PaymentDependencyProvider extends BasePaymentDependencyProvider
{
    /**
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Payment\PaymentMethodFilterPluginInterface[]
     */
    protected function getPaymentMethodFilterPlugins()
    {
        return [
            new SkipFirstPaymentMethodFilterPlugin(),
        ];
    }
}
