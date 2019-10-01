<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\QuoteApprovalWidget;

use SprykerShop\Yves\QuoteApprovalWidget\QuoteApprovalWidgetConfig as SprykerQuoteApprovalWidgetConfig;

class QuoteApprovalWidgetConfig extends SprykerQuoteApprovalWidgetConfig
{
    /**
     * @return bool
     */
    public function isWidgetVisibleOnCartPage(): bool
    {
        return false;
    }
}
