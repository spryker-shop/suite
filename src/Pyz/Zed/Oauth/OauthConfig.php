<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oauth;

use Spryker\Shared\Config\Config;
use Spryker\Zed\Oauth\OauthConfig as SprykerOauthConfig;

class OauthConfig extends SprykerOauthConfig
{
    /**
     * @uses \Spryker\Zed\OauthCompanyUserConnector\OauthCompanyUserConnectorConfig::SCOPE_COMPANY_USER
     */
    public const SCOPE_COMPANY_USER = 'company_user';

    /**
     * @uses \Spryker\Zed\OauthCustomerConnector\OauthCustomerConnectorConfig::SCOPE_CUSTOMER
     */
    public const SCOPE_CUSTOMER = 'customer';

    /**
     * @uses \Spryker\Shared\OauthCustomerConnector\OauthCustomerConnectorConstants::OAUTH_CLIENT_IDENTIFIER
     */
    protected const OAUTH_CUSTOMER_CLIENT_IDENTIFIER = 'OAUTH_CLIENT_IDENTIFIER';

    /**
     * @uses \Spryker\Shared\OauthCustomerConnector\OauthCustomerConnectorConstants::OAUTH_CLIENT_SECRET
     */
    protected const OAUTH_CUSTOMER_CLIENT_SECRET = 'OAUTH_CLIENT_SECRET';

    protected const OAUTH_CUSTOMER_CLIENT_NAME = 'Customer client';

    /**
     * @return string[]
     */
    public function getScopes(): array
    {
        return [
            static::SCOPE_CUSTOMER,
            static::SCOPE_COMPANY_USER,
        ];
    }

    /**
     * @return array
     */
    public function getClients(): array
    {
        return [
            Config::get(static::OAUTH_CUSTOMER_CLIENT_IDENTIFIER) => [
                static::OAUTH_CUSTOMER_CLIENT_NAME => Config::get(static::OAUTH_CUSTOMER_CLIENT_SECRET),
            ],
        ];
    }
}
