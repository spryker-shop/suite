<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\MerchantRelationship;

use Spryker\Zed\CommentMerchantRelationRequestConnector\Communication\Plugin\MerchantRelationship\CopyCommentThreadToMerchantRelationshipPostCreatePlugin;
use Spryker\Zed\CommentMerchantRelationshipConnector\Communication\Plugin\MerchantRelationship\CommentThreadMerchantRelationshipExpanderPlugin;
use Spryker\Zed\CompanyUnitAddress\Communication\Plugin\MerchantRelationship\CompanyUnitAddressMerchantRelationshipExpanderPlugin;
use Spryker\Zed\MerchantRelationship\MerchantRelationshipDependencyProvider as SprykerMerchantRelationshipDependencyProvider;
use Spryker\Zed\MerchantRelationshipProductList\Communication\Plugin\MerchantRelationship\ProductListMerchantRelationshipCreateValidatorPlugin;
use Spryker\Zed\MerchantRelationshipProductList\Communication\Plugin\MerchantRelationship\ProductListMerchantRelationshipExpanderPlugin;
use Spryker\Zed\MerchantRelationshipProductList\Communication\Plugin\MerchantRelationship\ProductListMerchantRelationshipPostCreatePlugin;
use Spryker\Zed\MerchantRelationshipProductList\Communication\Plugin\MerchantRelationship\ProductListMerchantRelationshipPostUpdatePlugin;
use Spryker\Zed\MerchantRelationshipProductList\Communication\Plugin\MerchantRelationship\ProductListMerchantRelationshipUpdateValidatorPlugin;
use Spryker\Zed\MerchantRelationshipProductList\Communication\Plugin\MerchantRelationship\ProductListRelationshipMerchantRelationshipPreDeletePlugin;
use Spryker\Zed\MerchantRelationshipSalesOrderThreshold\Communication\Plugin\MerchantRelationship\MerchantRelationshipSalesOrderThresholdMerchantRelationshipPreDeletePlugin;

class MerchantRelationshipDependencyProvider extends SprykerMerchantRelationshipDependencyProvider
{
    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPreDeletePluginInterface>
     */
    protected function getMerchantRelationshipPreDeletePlugins(): array
    {
        return [
            new MerchantRelationshipSalesOrderThresholdMerchantRelationshipPreDeletePlugin(),
            new ProductListRelationshipMerchantRelationshipPreDeletePlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostCreatePluginInterface>
     */
    protected function getMerchantRelationshipPostCreatePlugins(): array
    {
        return [
            new ProductListMerchantRelationshipPostCreatePlugin(),
            new CopyCommentThreadToMerchantRelationshipPostCreatePlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostUpdatePluginInterface>
     */
    protected function getMerchantRelationshipPostUpdatePlugins(): array
    {
        return [
            new ProductListMerchantRelationshipPostUpdatePlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipCreateValidatorPluginInterface>
     */
    protected function getMerchantRelationshipCreateValidatorPlugins(): array
    {
        return [
            new ProductListMerchantRelationshipCreateValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipUpdateValidatorPluginInterface>
     */
    protected function getMerchantRelationshipUpdateValidatorPlugins(): array
    {
        return [
            new ProductListMerchantRelationshipUpdateValidatorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipExpanderPluginInterface>
     */
    protected function getMerchantRelationshipExpanderPlugins(): array
    {
        return [
            new ProductListMerchantRelationshipExpanderPlugin(),
            new CommentThreadMerchantRelationshipExpanderPlugin(),
            new CompanyUnitAddressMerchantRelationshipExpanderPlugin(),
        ];
    }
}
