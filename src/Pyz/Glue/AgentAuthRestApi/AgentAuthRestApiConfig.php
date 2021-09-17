<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\AgentAuthRestApi;

use Spryker\Glue\AgentAuthRestApi\AgentAuthRestApiConfig as SprykerAgentAuthRestApiConfig;
use Spryker\Glue\QuoteRequestAgentsRestApi\QuoteRequestAgentsRestApiConfig;

class AgentAuthRestApiConfig extends SprykerAgentAuthRestApiConfig
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getAgentResources(): array
    {
        return [
            static::RESOURCE_AGENT_CUSTOMER_IMPERSONATION_ACCESS_TOKENS,
            static::RESOURCE_AGENT_CUSTOMER_SEARCH,
            QuoteRequestAgentsRestApiConfig::RESOURCE_AGENT_QUOTE_REQUESTS,
        ];
    }
}
