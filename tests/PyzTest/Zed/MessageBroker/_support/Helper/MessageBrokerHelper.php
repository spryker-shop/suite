<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\MessageBroker\Helper;

use Codeception\Module;
use Spryker\Zed\MessageBroker\MessageBrokerDependencyProvider;
use SprykerTest\Zed\Testify\Helper\Business\DependencyProviderHelperTrait;

class MessageBrokerHelper extends Module
{
    use DependencyProviderHelperTrait;

    /**
     * @return void
     */
    public function setupMessageBrokerValidationPlugins(): void
    {
        $this->getDependencyProviderHelper()->setDependency(MessageBrokerDependencyProvider::PLUGINS_EXTERNAL_VALIDATOR, []);
    }
}