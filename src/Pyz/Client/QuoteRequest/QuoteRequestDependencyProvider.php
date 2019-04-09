<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\QuoteRequest;

use Spryker\Client\QuoteApproval\Plugin\QuoteRequest\QuoteApprovalQuoteRequestCreatePreCheckPlugin;
use Spryker\Client\QuoteRequest\QuoteRequestDependencyProvider as SprykerQuoteRequestDependencyProvider;

class QuoteRequestDependencyProvider extends SprykerQuoteRequestDependencyProvider
{
    /**
     * @return \Spryker\Client\QuoteRequestExtension\Dependency\Plugin\QuoteRequestCreatePreCheckPluginInterface[]
     */
    protected function getQuoteRequestCreatePreCheckPlugins(): array
    {
        return [
            new QuoteApprovalQuoteRequestCreatePreCheckPlugin(),
        ];
    }
}
