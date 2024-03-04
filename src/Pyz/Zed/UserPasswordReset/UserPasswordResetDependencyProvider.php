<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UserPasswordReset;

use Spryker\Zed\MerchantUserPasswordResetMail\Communication\Plugin\UserPasswordReset\MailMerchantUserPasswordResetRequestStrategyPlugin;
use Spryker\Zed\UserPasswordReset\UserPasswordResetDependencyProvider as SprykerUserPasswordResetDependencyProvider;
use Spryker\Zed\UserPasswordResetMail\Communication\Plugin\UserPasswordReset\MailUserPasswordResetRequestStrategyPlugin;

class UserPasswordResetDependencyProvider extends SprykerUserPasswordResetDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\UserPasswordResetExtension\Dependency\Plugin\UserPasswordResetRequestStrategyPluginInterface>
     */
    public function getUserPasswordResetRequestStrategyPlugins(): array
    {
        return [
            new MailMerchantUserPasswordResetRequestStrategyPlugin(),
            new MailUserPasswordResetRequestStrategyPlugin(),
        ];
    }
}
