<?php

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
