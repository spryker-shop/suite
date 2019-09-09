<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CheckoutDataRestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
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
class CheckoutDataRestApiTester extends ApiEndToEndTester
{
    use _generated\CheckoutDataRestApiTesterActions;

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

        $this->sendPOST($this->formatUrl('carts/{idCart}/items', ['idCart' => $idCart]), [
            'data' => [
                'type' => 'items',
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

        $this->sendPOST('carts', [
            'data' => [
                'type' => 'carts',
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
