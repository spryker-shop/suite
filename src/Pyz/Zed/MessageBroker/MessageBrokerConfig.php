<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MessageBroker;

use Spryker\Shared\MessageBrokerAws\MessageBrokerAwsConstants;
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

    /**
     * Additional method to support MB1 and MB2 in parallel.
     * Will be removed when MB1 is deprecated and removed.
     *
     * @api
     *
     * @return bool
     */
    public function isDevelopmentMessageBrokerVersion2Enabled(): bool
    {
        return $this->get(MessageBrokerAwsConstants::HTTP_CHANNEL_SENDER_BASE_URL)
            && $this->get(MessageBrokerAwsConstants::HTTP_CHANNEL_RECEIVER_BASE_URL);
    }
}
