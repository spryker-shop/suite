<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\TaxApp;

use Spryker\Zed\Calculation\Communication\Plugin\Calculator\ItemTaxAmountFullAggregatorPlugin;
use Spryker\Zed\Calculation\Communication\Plugin\Calculator\PriceToPayAggregatorPlugin;
use Spryker\Zed\MerchantProfile\Communication\Plugin\TaxApp\MerchantProfileAddressCalculableObjectTaxAppExpanderPlugin;
use Spryker\Zed\MerchantProfile\Communication\Plugin\TaxApp\MerchantProfileAddressOrderTaxAppExpanderPlugin;
use Spryker\Zed\ProductOfferAvailability\Communication\Plugin\TaxApp\ProductOfferAvailabilityCalculableObjectTaxAppExpanderPlugin;
use Spryker\Zed\ProductOfferAvailability\Communication\Plugin\TaxApp\ProductOfferAvailabilityOrderTaxAppExpanderPlugin;
use Spryker\Zed\Tax\Communication\Plugin\Calculator\TaxAmountAfterCancellationCalculatorPlugin;
use Spryker\Zed\Tax\Communication\Plugin\Calculator\TaxAmountCalculatorPlugin;
use Spryker\Zed\Tax\Communication\Plugin\Calculator\TaxRateAverageAggregatorPlugin;
use Spryker\Zed\TaxApp\TaxAppDependencyProvider as SprykerTaxAppDependencyProvider;
use Spryker\Zed\TaxAppVertex\Communication\Plugin\Order\OrderCustomerWithVertexCodeExpanderPlugin;
use Spryker\Zed\TaxAppVertex\Communication\Plugin\Order\OrderExpensesWithVertexCodeExpanderPlugin;
use Spryker\Zed\TaxAppVertex\Communication\Plugin\Order\OrderItemProductOptionWithVertexCodeExpanderPlugin;
use Spryker\Zed\TaxAppVertex\Communication\Plugin\Order\OrderItemWithVertexSpecificFieldsExpanderPlugin;
use Spryker\Zed\TaxAppVertex\Communication\Plugin\Quote\CalculableObjectCustomerWithVertexCodeExpanderPlugin;
use Spryker\Zed\TaxAppVertex\Communication\Plugin\Quote\CalculableObjectExpensesWithVertexCodeExpanderPlugin;
use Spryker\Zed\TaxAppVertex\Communication\Plugin\Quote\CalculableObjectItemProductOptionWithVertexCodeExpanderPlugin;
use Spryker\Zed\TaxAppVertex\Communication\Plugin\Quote\CalculableObjectItemWithVertexSpecificFieldsExpanderPlugin;

class TaxAppDependencyProvider extends SprykerTaxAppDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\TaxAppExtension\Dependency\Plugin\CalculableObjectTaxAppExpanderPluginInterface>
     */
    protected function getCalculableObjectTaxAppExpanderPlugins(): array
    {
        return [
            new CalculableObjectCustomerWithVertexCodeExpanderPlugin(),
            new CalculableObjectExpensesWithVertexCodeExpanderPlugin(),
            new CalculableObjectItemProductOptionWithVertexCodeExpanderPlugin(),
            new CalculableObjectItemWithVertexSpecificFieldsExpanderPlugin(),
            new MerchantProfileAddressCalculableObjectTaxAppExpanderPlugin(),
            new ProductOfferAvailabilityCalculableObjectTaxAppExpanderPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\TaxAppExtension\Dependency\Plugin\OrderTaxAppExpanderPluginInterface>
     */
    protected function getOrderTaxAppExpanderPlugins(): array
    {
        return [
            new OrderCustomerWithVertexCodeExpanderPlugin(),
            new OrderExpensesWithVertexCodeExpanderPlugin(),
            new OrderItemProductOptionWithVertexCodeExpanderPlugin(),
            new OrderItemWithVertexSpecificFieldsExpanderPlugin(),
            new MerchantProfileAddressOrderTaxAppExpanderPlugin(),
            new ProductOfferAvailabilityOrderTaxAppExpanderPlugin(),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return array<\Spryker\Zed\CalculationExtension\Dependency\Plugin\CalculationPluginInterface>
     */
    protected function getFallbackQuoteCalculationPlugins(): array
    {
        return [
            new TaxAmountCalculatorPlugin(),
            new ItemTaxAmountFullAggregatorPlugin(),
            new PriceToPayAggregatorPlugin(),
            new TaxRateAverageAggregatorPlugin(),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return array<\Spryker\Zed\CalculationExtension\Dependency\Plugin\CalculationPluginInterface>
     */
    protected function getFallbackOrderCalculationPlugins(): array
    {
        return [
            new TaxAmountCalculatorPlugin(),
            new ItemTaxAmountFullAggregatorPlugin(),
            new PriceToPayAggregatorPlugin(),
            new TaxAmountAfterCancellationCalculatorPlugin(),
        ];
    }
}
