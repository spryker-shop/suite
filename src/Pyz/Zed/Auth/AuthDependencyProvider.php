<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Auth;

use Spryker\Zed\AuthMailConnector\Communication\Plugin\AuthPasswordResetMailSenderPlugin;
use Spryker\Zed\Kernel\Container;

use Spryker\Zed\Auth\AuthDependencyProvider as SprykerAuthDependencyProvider;

/**
 * @method \Spryker\Zed\Auth\AuthConfig getConfig()
 */
class AuthDependencyProvider extends SprykerAuthDependencyProvider
{
    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Auth\Dependency\Plugin\AuthPasswordResetSenderInterface|null
     */
    protected function getPasswordResetNotificationSender(Container $container)
    {
        return new AuthPasswordResetMailSenderPlugin();
    }
}
