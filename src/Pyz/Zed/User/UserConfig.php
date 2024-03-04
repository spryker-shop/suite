<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\User;

use Spryker\Zed\User\UserConfig as SprykerUserConfig;

class UserConfig extends SprykerUserConfig
{
    /**
     * @var bool
     */
    protected const IS_POST_SAVE_PLUGINS_ENABLED_AFTER_USER_STATUS_CHANGE = true;

    /**
     * @return array<array<string, mixed>>
     */
    public function getInstallerUsers(): array
    {
        return [
            [
                'firstName' => 'Admin',
                'lastName' => 'Spryker',
                'username' => 'admin@spryker.com',
                'password' => 'change123',
                'localeName' => 'en_US',
            ],
            [
                'firstName' => 'Admin',
                'lastName' => 'German',
                'password' => 'change123',
                'username' => 'admin_de@spryker.com',
                'localeName' => 'de_DE',
            ],
            [
                'firstName' => 'Harald',
                'lastName' => 'Schmidt',
                'password' => 'change123',
                'username' => 'harald@spryker.com',
                'localeName' => 'en_US',
            ],
            [
                'firstName' => 'Richard',
                'lastName' => 'Gere',
                'password' => 'change123',
                'username' => 'richard@spryker.com',
                'localeName' => 'en_US',
            ],
            [
                'firstName' => 'Martha',
                'lastName' => 'Farmer',
                'password' => 'change123',
                'username' => 'martha@video-king.nl',
                'localeName' => 'en_US',
            ],
            [
                'firstName' => 'Jason',
                'lastName' => 'Weidmann',
                'password' => 'change123',
                'username' => 'jason.weidmann@budgetcamerasonline.com',
                'localeName' => 'en_US',
            ],
            [
                'firstName' => 'Michele',
                'lastName' => 'Nemeth',
                'password' => 'change123',
                'username' => 'michele@sony-experts.com',
                'localeName' => 'en_US',
            ],
            [
                'firstName' => 'Vitaliy',
                'lastName' => 'Smith',
                'password' => 'change123',
                'username' => 'agent123@spryker.com',
                'isAgent' => 1,
                'localeName' => 'en_US',
            ],
            [
                'firstName' => 'Agent',
                'lastName' => 'Merchant',
                'password' => 'change123',
                'username' => 'agent-merchant@spryker.com',
                'isMerchantAgent' => 1,
                'localeName' => 'en_US',
            ],
        ];
    }
}
