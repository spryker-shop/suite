<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesPaymentMerchant;

use Spryker\Zed\SalesPaymentMerchant\SalesPaymentMerchantDependencyProvider as SprykerSalesPaymentMerchantDependencyProvider;
use Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface;
use Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Communication\Plugin\SalesPaymentMerchant\PayoutAmountMerchantPayoutCalculatorPlugin;
use Spryker\Zed\SalesPaymentMerchantSalesMerchantCommission\Communication\Plugin\SalesPaymentMerchant\PayoutReverseAmountMerchantPayoutCalculatorPlugin;

class SalesPaymentMerchantDependencyProvider extends SprykerSalesPaymentMerchantDependencyProvider
{
    /**
     * @return \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface|null
     */
    protected function getMerchantPayoutAmountCalculatorPlugin(): ?MerchantPayoutCalculatorPluginInterface
    {
        return new PayoutAmountMerchantPayoutCalculatorPlugin();
    }

    /**
     * @return \Spryker\Zed\SalesPaymentMerchantExtension\Communication\Dependency\Plugin\MerchantPayoutCalculatorPluginInterface|null
     */
    protected function getMerchantPayoutReverseAmountCalculatorPlugin(): ?MerchantPayoutCalculatorPluginInterface
    {
        return new PayoutReverseAmountMerchantPayoutCalculatorPlugin();
    }
}
