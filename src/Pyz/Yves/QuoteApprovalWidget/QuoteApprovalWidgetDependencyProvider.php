<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\QuoteApprovalWidget;

use SprykerShop\Yves\CartPage\Plugin\QuoteApprovalWidget\QuoteApprovalAfterOperationPlugin;
use SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetDependencyProvider as SprykerQuoteApprovalWidgetDependencyProvider;

class QuoteApprovalWidgetDependencyProvider extends SprykerQuoteApprovalWidgetDependencyProvider
{
    /**
     * @return \SprykerShop\Yves\QuoteApprovalWidgetExtension\Dependency\Plugin\QuoteApprovalAfterOperationPluginInterface[]
     */
    protected function getQuoteApprovalAfterOperationPlugins(): array
    {
        return [
            new QuoteApprovalAfterOperationPlugin(),
        ];
    }
}
