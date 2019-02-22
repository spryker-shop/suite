<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\AgentQuoteRequestPage;

use SprykerShop\Yves\QuoteRequestPage\Plugin\QuoteRequestPage\DeliveryDateMetadataFieldPlugin;
use SprykerShop\Yves\QuoteRequestPage\Plugin\QuoteRequestPage\NoteMetadataFieldPlugin;
use SprykerShop\Yves\QuoteRequestPage\Plugin\QuoteRequestPage\PurchaseOrderNumberMetadataFieldPlugin;
use SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageDependencyProvider as SprykerAgentQuoteRequestPageDependencyProvider;

class AgentQuoteRequestPageDependencyProvider extends SprykerAgentQuoteRequestPageDependencyProvider
{
    /**
     * @return \SprykerShop\Yves\QuoteRequestPageExtension\Dependency\Plugin\QuoteRequestFormMetadataFieldPluginInterface[]
     */
    protected function getAgentQuoteRequestFormMetadataFieldPlugins(): array
    {
        return [
            new PurchaseOrderNumberMetadataFieldPlugin(),
            new DeliveryDateMetadataFieldPlugin(),
            new NoteMetadataFieldPlugin(),
        ];
    }
}
