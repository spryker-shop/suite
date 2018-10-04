<?php

namespace Pyz\Zed\Payment;

use Pyz\Zed\Payment\Communication\Plugin\Payment\TestFilterPlugin;
use Spryker\Zed\Payment\PaymentDependencyProvider as BasePaymentDependencyProvider;

class PaymentDependencyProvider extends BasePaymentDependencyProvider
{
    /**
     * @return \Spryker\Zed\Payment\Dependency\Plugin\Payment\PaymentMethodFilterPluginInterface[]
     */
    protected function getPaymentMethodFilterPlugins()
    {
        return [
            new TestFilterPlugin(),
        ];
    }
}
