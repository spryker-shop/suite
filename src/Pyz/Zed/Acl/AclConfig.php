<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Acl;

use Spryker\Shared\Acl\AclConstants;
use Spryker\Zed\Acl\AclConfig as SprykerAclConfig;

class AclConfig extends SprykerAclConfig
{
    protected const RULE_TYPE_DENY = 'deny';
    protected const GROUP_MERCHANT_ADMIN = 'Merchant Admin';

    /**
     * @return array
     */
    public function getInstallerRules()
    {
        $installerRules = parent::getInstallerRules();
        $installerRules = $this->addMerchantPortalInstallerRules($installerRules);

        return $installerRules;
    }

    /**
     * @return array
     */
    public function getInstallerUsers()
    {
        return [
            'admin@spryker.com' => [
                'group' => AclConstants::ROOT_GROUP,
            ],
            'admin_de@spryker.com' => [
                'group' => AclConstants::ROOT_GROUP,
            ],
            'martha@video-king.nl' => [
                'group' => static::GROUP_MERCHANT_ADMIN,
            ],
            'harald@spryker.com' => [
                'group' => static::GROUP_MERCHANT_ADMIN,
            ],
            'jason.weidmann@budgetcamerasonline.com' => [
                'group' => static::GROUP_MERCHANT_ADMIN,
            ],
            'michele@sony-experts.com' => [
                'group' => static::GROUP_MERCHANT_ADMIN,
            ],
            //this is related to existent username and will be searched into the database
        ];
    }

    /**
     * @param string[][] $installerRules
     *
     * @return string[][]
     */
    protected function addMerchantPortalInstallerRules(array $installerRules): array
    {
        $bundleNames = [
            'dashboard-merchant-portal-gui',
            'merchant-profile-merchant-portal-gui',
            'product-merchant-portal-gui',
            'product-offer-merchant-portal-gui',
            'security-merchant-portal-gui',
            'sales-merchant-portal-gui',
            'user-merchant-portal-gui',
            'dummy-merchant-portal-gui',
        ];

        foreach ($bundleNames as $bundleName) {
            $installerRules[] = [
                'bundle' => $bundleName,
                'controller' => AclConstants::VALIDATOR_WILDCARD,
                'action' => AclConstants::VALIDATOR_WILDCARD,
                'type' => static::RULE_TYPE_DENY,
                'role' => AclConstants::ROOT_ROLE,
            ];
        }

        return $installerRules;
    }
}
