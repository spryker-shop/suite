<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CompanyPage;

use SprykerShop\Yves\CompanyPage\CompanyPageDependencyProvider as SprykerShopCompanyPageDependencyProvider;
use SprykerShop\Yves\CompanyUserInvitationWidget\Plugin\CompanyPage\CompanyUserInvitationWidgetPlugin;

class CompanyPageDependencyProvider extends SprykerShopCompanyPageDependencyProvider
{
    /**
     * @return string[]
     */
    protected function getCompanyUserOverviewWidgetPlugins(): array
    {
        return [
            CompanyUserInvitationWidgetPlugin::class,
        ];
    }
}
