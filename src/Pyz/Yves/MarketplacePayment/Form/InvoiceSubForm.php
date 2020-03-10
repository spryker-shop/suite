<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MarketplacePayment\Form;

use Pyz\Shared\MarketplacePayment\MarketplacePaymentConfig;
use Spryker\Yves\StepEngine\Dependency\Form\AbstractSubFormType;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormProviderNameInterface;

class InvoiceSubForm extends AbstractSubFormType implements SubFormInterface, SubFormProviderNameInterface
{
    protected const PAYMENT_METHOD = 'invoice';

    /**
     * @return string
     */
    public function getProviderName(): string
    {
        return MarketplacePaymentConfig::PAYMENT_PROVIDER_NAME;
    }

    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        return MarketplacePaymentConfig::PAYMENT_METHOD_MARKETPLACE_PAYMENT_INVOICE;
    }

    /**
     * @return string
     */
    public function getPropertyPath(): string
    {
        return MarketplacePaymentConfig::PAYMENT_METHOD_MARKETPLACE_PAYMENT_INVOICE;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return sprintf(
            '%s%s%s',
            MarketplacePaymentConfig::PAYMENT_PROVIDER_NAME,
            DIRECTORY_SEPARATOR,
            static::PAYMENT_METHOD
        );
    }
}
