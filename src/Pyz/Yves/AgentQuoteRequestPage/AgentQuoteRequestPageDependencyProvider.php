<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\AgentQuoteRequestPage;

use SprykerShop\Yves\AgentQuoteRequestPage\AgentQuoteRequestPageDependencyProvider as SprykerAgentQuoteRequestPageDependencyProvider;
use SprykerShop\Yves\AgentQuoteRequestPage\Plugin\AgentQuoteRequestPage\DeliveryDateMetadataFieldPlugin;
use SprykerShop\Yves\AgentQuoteRequestPage\Plugin\AgentQuoteRequestPage\NoteMetadataFieldPlugin;
use SprykerShop\Yves\AgentQuoteRequestPage\Plugin\AgentQuoteRequestPage\PurchaseOrderNumberMetadataFieldPlugin;

class AgentQuoteRequestPageDependencyProvider extends SprykerAgentQuoteRequestPageDependencyProvider
{
    /**
     * @return \SprykerShop\Yves\AgentQuoteRequestPageExtension\Dependency\Plugin\AgentQuoteRequestFormMetadataFieldPluginInterface[]
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
