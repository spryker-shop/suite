<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\SspAssetManagement;

use SprykerFeature\Zed\SspAssetManagement\SspAssetManagementDependencyProvider as SprykerSspAssetManagementDependencyProvider;
use SprykerFeature\Zed\SspInquiryManagement\Communication\Plugin\SspAssetManagement\SspInquirySspAssetManagementExpanderPlugin;

class SspAssetManagementDependencyProvider extends SprykerSspAssetManagementDependencyProvider
{
    /**
     * @return array<\SprykerFeature\Zed\SspAssetManagement\Dependency\Plugin\SspAssetManagementExpanderPluginInterface>
     */
    protected function getSspAssetManagementExpanderPlugins(): array
    {
        return [
            new SspInquirySspAssetManagementExpanderPlugin(),
        ];
    }
}
