<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use Generated\Shared\Transfer\PayoneGetPaymentDetailTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\Customer\CustomerClientInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerEco\Yves\Payone\Handler\PayoneHandler;
use SprykerShop\Yves\CheckoutPage\CheckoutPageConfig;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCartClientInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\SuccessStep as SprykerSuccessStep;
use Symfony\Component\HttpFoundation\Request;

class SuccessStep extends SprykerSuccessStep
{
    /**
     * @var \SprykerEco\Client\Payone\PayoneClientInterface
     */
    protected $payoneClient;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected $quoteTransfer;

    /**
     * @param \Spryker\Client\Customer\CustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig $checkoutPageConfig
     * @param string $stepRoute
     * @param string $escapeRoute
     */
    public function __construct(CustomerClientInterface $customerClient, CheckoutPageToCartClientInterface $cartClient, CheckoutPageConfig $checkoutPageConfig, string $stepRoute, string $escapeRoute)
    {
        $this->customerClient = $customerClient;
        $this->cartClient = $cartClient;
        $this->checkoutPageConfig = $checkoutPageConfig;
        $this->stepRoute = $stepRoute;
        $this->escapeRoute = $escapeRoute;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(Request $request, AbstractTransfer $quoteTransfer)
    {
        $this->customerClient->markCustomerAsDirty();

        if (method_exists($quoteTransfer->getPayment(), 'getPayone')) {
            $this->quoteTransfer = $quoteTransfer;
        }

        return new QuoteTransfer();
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $dataTransfer)
    {
        $getPaymentDetailTransfer = new PayoneGetPaymentDetailTransfer();

        if ($this->quoteTransfer->getPayment()->getPaymentProvider() === PayoneHandler::PAYMENT_PROVIDER) {
            $getPaymentDetailTransfer->setOrderReference($this->quoteTransfer->getOrderReference());
            $getPaymentDetailTransfer = $this->payoneClient->getPaymentDetail($getPaymentDetailTransfer);
        }

        return [
            'quoteTransfer' => $this->quoteTransfer,
            'paymentDetail' => $getPaymentDetailTransfer->getPaymentDetail(),
        ];
    }
}
