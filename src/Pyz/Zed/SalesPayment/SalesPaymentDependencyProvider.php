<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\SalesPayment;

use Spryker\Zed\GiftCard\Communication\Plugin\SalesPayment\GiftCardPaymentMapKeyBuilderStrategyPlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\SalesPayment\PaymentGiftCardSalesPaymentPreDeletePlugin;
use Spryker\Zed\GiftCardBalance\Communication\Plugin\SalesPayment\GiftCardBalanceLogSalesPaymentPreDeletePlugin;
use Spryker\Zed\SalesPayment\SalesPaymentDependencyProvider as SprykerSalesPaymentDependencyProvider;

class SalesPaymentDependencyProvider extends SprykerSalesPaymentDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\PaymentMapKeyBuilderStrategyPluginInterface>
     */
    protected function getPaymentMapKeyBuilderStrategyPlugins(): array
    {
        return [
            new GiftCardPaymentMapKeyBuilderStrategyPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\SalesPaymentExtension\Dependency\Plugin\SalesPaymentPreDeletePluginInterface>
     */
    protected function getSalesPaymentPreDeletePlugins(): array
    {
        return [
            new PaymentGiftCardSalesPaymentPreDeletePlugin(),
            new GiftCardBalanceLogSalesPaymentPreDeletePlugin(),
        ];
    }
}
