<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\QuoteApproval;

use Spryker\Client\QuoteApproval\QuoteApprovalDependencyProvider as SprykerQuoteApprovalDependencyProvider;
use Spryker\Client\QuoteRequest\Plugin\QuoteApproval\QuoteRequestQuoteApprovalCreatePreCheckPlugin;

class QuoteApprovalDependencyProvider extends SprykerQuoteApprovalDependencyProvider
{
    /**
     * @return \Spryker\Client\QuoteApprovalExtension\Dependency\Plugin\QuoteApprovalCreatePreCheckPluginInterface[]
     */
    protected function getQuoteApprovalCreatePreCheckPlugins(): array
    {
        return [
            new QuoteRequestQuoteApprovalCreatePreCheckPlugin(),
        ];
    }
}
