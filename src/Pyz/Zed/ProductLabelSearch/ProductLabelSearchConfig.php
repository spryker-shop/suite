<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ProductLabelSearch;

use Spryker\Shared\Publisher\PublisherConfig;
use Spryker\Zed\ProductLabelSearch\ProductLabelSearchConfig as SprykerProductLabelSearchConfig;

class ProductLabelSearchConfig extends SprykerProductLabelSearchConfig
{
    /**
     * @return string|null
     */
    public function getEventQueueName(): ?string
    {
        return PublisherConfig::PUBLISH_QUEUE;
    }
}
