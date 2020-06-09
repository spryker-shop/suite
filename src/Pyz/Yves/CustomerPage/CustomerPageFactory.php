<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage;

use SprykerShop\Yves\CustomerPage\CustomerPageFactory as SprykerCustomerPageFactory;

class CustomerPageFactory extends SprykerCustomerPageFactory
{
    /**
     * @return \SprykerEco\Client\Payone\PayoneClientInterface
     */
    public function createPayoneClient()
    {
        return $this->getProvidedDependency(CustomerPageDependencyProvider::CLIENT_PAYONE);
    }
}
