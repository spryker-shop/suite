<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Yves\MultiFactorAuth;

use Spryker\Yves\MultiFactorAuth\MultiFactorAuthDependencyProvider as SprykerMultiFactorAuthDependencyProvider;
use Spryker\Yves\MultiFactorAuth\Plugin\Factors\Email\CustomerEmailMultiFactorAuthPlugin;

class MultiFactorAuthDependencyProvider extends SprykerMultiFactorAuthDependencyProvider
{
    /**
     * @return array<\Spryker\Shared\MultiFactorAuthExtension\Dependency\Plugin\MultiFactorAuthPluginInterface>
     */
    protected function getCustomerMultiFactorAuthPlugins(): array
    {
        return [
            new CustomerEmailMultiFactorAuthPlugin(),
        ];
    }
}
