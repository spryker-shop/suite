<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Api;

use Spryker\Zed\Api\ApiDependencyProvider as SprykerApiDependencyProvider;
use Spryker\Zed\Api\Communication\Plugin\ApiRequestTransferFilterHeaderDataPlugin;
use Spryker\Zed\Api\Communication\Plugin\ApiRequestTransferFilterServerDataPlugin;
use Spryker\Zed\CustomerApi\Communication\Plugin\Api\CustomerApiResourcePlugin;
use Spryker\Zed\CustomerApi\Communication\Plugin\Api\CustomerApiValidatorPlugin;
use Spryker\Zed\MerchantRelationshipApi\Communication\Plugin\Api\MerchantRelationshipApiResourcePlugin;
use Spryker\Zed\MerchantRelationshipApi\Communication\Plugin\Api\MerchantRelationshipApiValidatorPlugin;
use Spryker\Zed\ProductApi\Communication\Plugin\Api\ProductApiResourcePlugin;
use Spryker\Zed\ProductApi\Communication\Plugin\Api\ProductApiValidatorPlugin;

class ApiDependencyProvider extends SprykerApiDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface>
     */
    protected function getApiResourcePluginCollection(): array
    {
        return [
            new CustomerApiResourcePlugin(),
            new ProductApiResourcePlugin(),
            new MerchantRelationshipApiResourcePlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\ApiExtension\Dependency\Plugin\ApiValidatorPluginInterface>
     */
    protected function getApiValidatorPluginCollection(): array
    {
        return [
            new CustomerApiValidatorPlugin(),
            new ProductApiValidatorPlugin(),
            new MerchantRelationshipApiValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\Api\Communication\Plugin\ApiRequestTransferFilterPluginInterface>
     */
    protected function getApiRequestTransferFilterPluginCollection(): array
    {
        return [
            new ApiRequestTransferFilterServerDataPlugin(),
            new ApiRequestTransferFilterHeaderDataPlugin(),
        ];
    }
}
