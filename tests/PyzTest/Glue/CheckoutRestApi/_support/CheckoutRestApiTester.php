<?php

namespace PyzTest\Glue\CheckoutRestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use SprykerTest\Glue\Testify\Tester\ApiEndToEndTester;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class CheckoutRestApiTester extends ApiEndToEndTester
{
    use _generated\CheckoutRestApiTesterActions;

    protected const PRODUCT_AMOUNT_IN_CART = 1;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return string
     */
    public function createCartWithItems(
        CustomerTransfer $customerTransfer,
        ProductConcreteTransfer $productConcreteTransfer
    ): string {
        $idCart = $this->createCart($customerTransfer);

        $url = sprintf('%s/%s/%s', CartsRestApiConfig::RESOURCE_CARTS, $idCart, CartsRestApiConfig::RESOURCE_CART_ITEMS);

        $this->sendPOST($this->formatUrl($url), [
            'data' => [
                'type' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                'attributes' => [
                    'sku' => $productConcreteTransfer->getSku(),
                    'quantity' => static::PRODUCT_AMOUNT_IN_CART,
                ],
            ],
        ]);

        return $idCart;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     */
    public function createCart(CustomerTransfer $customerTransfer): string
    {
        $this->customerLogIn($customerTransfer);

        $this->sendPOST(CartsRestApiConfig::RESOURCE_CARTS, [
            'data' => [
                'type' => CartsRestApiConfig::RESOURCE_CARTS,
                'attributes' => [
                    'priceMode' => 'GROSS_MODE',
                    'currency' => 'EUR',
                    'store' => 'DE',
                    'name' => 'My Cart',
                ],
            ],
        ]);

        return $this->grabDataFromResponseByJsonPath('$.data.id')[0];
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function customerLogIn(CustomerTransfer $customerTransfer): void
    {
        $accessToken = $this->haveAuthorizationToGlue($customerTransfer)['accessToken'];
        $this->amAuthorizedGlueUser($accessToken);
    }
}
