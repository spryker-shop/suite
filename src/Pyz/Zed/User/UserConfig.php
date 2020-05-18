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
     * @return array
     */
    public function getInstallerUsers()
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
            ],
            [
                'firstName' => 'Martha',
                'lastName' => 'Farmer',
                'password' => 'change123',
                'username' => 'martha@video-king.nl',
            ],
            [
                'firstName' => 'Jason',
                'lastName' => 'Weidmann',
                'password' => 'change123',
                'username' => 'jason.weidmann@budgetcamerasonline.com',
            ],
            [
                'firstName' => 'Michele',
                'lastName' => 'Nemeth',
                'password' => 'change123',
                'username' => 'michele@sony-camera-experts.com',
            ],
        ];
    }
}
