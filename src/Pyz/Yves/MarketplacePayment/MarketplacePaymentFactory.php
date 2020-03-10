<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MarketplacePayment;

use Pyz\Yves\MarketplacePayment\Expander\MarketplacePaymentExpander;
use Pyz\Yves\MarketplacePayment\Expander\MarketplacePaymentExpanderInterface;
use Pyz\Yves\MarketplacePayment\Form\DataProvider\InvoiceFormDataProvider;
use Pyz\Yves\MarketplacePayment\Form\InvoiceSubForm;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface;

/**
 * @method \Pyz\Yves\MarketplacePayment\MarketplacePaymentConfig getConfig()
 */
class MarketplacePaymentFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Yves\MarketplacePayment\Expander\MarketplacePaymentExpanderInterface
     */
    public function createMarketplacePaymentExpander(): MarketplacePaymentExpanderInterface
    {
        return new MarketplacePaymentExpander();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\SubFormInterface
     */
    public function createInvoiceSubForm(): SubFormInterface
    {
        return new InvoiceSubForm();
    }

    /**
     * @return \Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface
     */
    public function createInvoiceFormDataProvider(): StepEngineFormDataProviderInterface
    {
        return new InvoiceFormDataProvider();
    }
}
