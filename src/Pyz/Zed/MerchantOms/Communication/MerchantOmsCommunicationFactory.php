<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MerchantOms\Communication;

use Pyz\Zed\MerchantOms\MerchantOmsDependencyProvider;
use Pyz\Zed\Oms\Business\OmsFacadeInterface;
use Spryker\Zed\MerchantOms\Communication\MerchantOmsCommunicationFactory as SprykerMerchantOmsCommunicationFactory;

/**
 * @method \Spryker\Zed\MerchantOms\MerchantOmsConfig getConfig()
 * @method \Spryker\Zed\MerchantOms\Business\MerchantOmsFacadeInterface getFacade()
 * @method \Spryker\Zed\MerchantOms\Persistence\MerchantOmsRepositoryInterface getRepository()
 */
class MerchantOmsCommunicationFactory extends SprykerMerchantOmsCommunicationFactory
{
    /**
     * @return \Pyz\Zed\Oms\Business\OmsFacadeInterface
     */
    public function getOmsFacade(): OmsFacadeInterface
    {
        return $this->getProvidedDependency(MerchantOmsDependencyProvider::FACADE_OMS);
    }
}
