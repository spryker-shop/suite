<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MessageBroker;

use Spryker\Zed\MessageBroker\MessageBrokerConfig as SprykerMessageBrokerConfig;

class MessageBrokerConfig extends SprykerMessageBrokerConfig
{
    /**
     * @return array<string>
     */
    public function getDefaultWorkerChannels(): array
    {
        return [
            'payment-events',
            'app-events',
            'payment-method-commands',
            'asset-commands',
            'product-review-commands',
            'search-commands',
            'product-commands',
            'merchant-commands',
            'merchant-app-events',
            'tax-commands',
        ];
    }
}
