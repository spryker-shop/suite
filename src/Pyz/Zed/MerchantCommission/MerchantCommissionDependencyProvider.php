<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantCommission;

use Spryker\Zed\CategoryMerchantCommissionConnector\Communication\Plugin\MerchantCommission\CategoryMerchantCommissionItemCollectorRulePlugin;
use Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\FixedMerchantCommissionCalculatorPlugin;
use Spryker\Zed\MerchantCommission\Communication\Plugin\MerchantCommission\PercentageMerchantCommissionCalculatorPlugin;
use Spryker\Zed\MerchantCommission\Communication\Plugin\RuleEngine\ItemSkuCollectorRulePlugin;
use Spryker\Zed\MerchantCommission\Communication\Plugin\RuleEngine\PriceModeDecisionRulePlugin;
use Spryker\Zed\MerchantCommission\MerchantCommissionDependencyProvider as SprykerMerchantCommissionDependencyProvider;
use Spryker\Zed\PriceProductMerchantCommissionConnector\Communication\Plugin\MerchantCommission\PriceProductMerchantCommissionItemCollectorRulePlugin;
use Spryker\Zed\ProductMerchantCommissionConnector\Communication\Plugin\MerchantCommission\ProductAttributeMerchantCommissionItemCollectorRulePlugin;

class MerchantCommissionDependencyProvider extends SprykerMerchantCommissionDependencyProvider
{
    /**
     * @return list<\Spryker\Zed\MerchantCommissionExtension\Communication\Dependency\Plugin\MerchantCommissionCalculatorPluginInterface>
     */
    protected function getMerchantCommissionCalculatorPlugins(): array
    {
        return [
            new FixedMerchantCommissionCalculatorPlugin(),
            new PercentageMerchantCommissionCalculatorPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\CollectorRulePluginInterface>
     */
    protected function getRuleEngineCollectorRulePlugins(): array
    {
        return [
            new ItemSkuCollectorRulePlugin(),
            new ProductAttributeMerchantCommissionItemCollectorRulePlugin(),
            new PriceProductMerchantCommissionItemCollectorRulePlugin(),
            new CategoryMerchantCommissionItemCollectorRulePlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\RuleEngineExtension\Communication\Dependency\Plugin\DecisionRulePluginInterface>
     */
    protected function getRuleEngineDecisionRulePlugins(): array
    {
        return [
            new PriceModeDecisionRulePlugin(),
        ];
    }
}
