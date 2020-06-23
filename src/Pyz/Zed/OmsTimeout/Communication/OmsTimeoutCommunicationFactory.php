<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\OmsTimeout\Communication;

use Pyz\Zed\OmsTimeout\OmsTimeoutDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;

/**
 * @method \Pyz\Zed\OmsTimeout\Business\OmsTimeoutFacadeInterface getFacade()
 */
class OmsTimeoutCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Translator\Business\TranslatorFacadeInterface
     */
    public function getTranslatorFacade(): TranslatorFacadeInterface
    {
        return $this->getProvidedDependency(OmsTimeoutDependencyProvider::FACADE_TRANSLATOR);
    }
}
