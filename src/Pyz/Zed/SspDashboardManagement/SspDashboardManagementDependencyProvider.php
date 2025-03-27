<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\SspDashboardManagement;

use SprykerFeature\Zed\SspDashboardManagement\SspDashboardManagementDependencyProvider as SprykerSspDashboardManagementDependencyProvider;
use SprykerFeature\Zed\SspFileManagement\Communication\Plugin\SspDashboardManagement\SspFileDashboardDataProviderPlugin;
use SprykerFeature\Zed\SspInquiryManagement\Communication\Plugin\SspDashboardManagement\SspInquiryDashboardDataProviderPlugin;

class SspDashboardManagementDependencyProvider extends SprykerSspDashboardManagementDependencyProvider
{
    /**
     * @return array<int, \SprykerFeature\Zed\SspDashboardManagement\Dependency\Plugin\DashboardDataProviderPluginInterface>
     */
    protected function getDashboardDataProviderPlugins(): array
    {
        return [
            new SspInquiryDashboardDataProviderPlugin(),
            new SspFileDashboardDataProviderPlugin(),
        ];
    }
}
