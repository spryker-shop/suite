<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\OrderAmendments;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use Spryker\Glue\CartReorderRestApi\CartReorderRestApiConfig;
use SprykerTest\Glue\Testify\Tester\ApiEndToEndTester;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class OrderAmendmentsApiTester extends ApiEndToEndTester
{
    use _generated\OrderAmendmentsApiTesterActions;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function authorizeCustomerToGlue(CustomerTransfer $customerTransfer): void
    {
        $oauthResponseTransfer = $this->haveAuthorizationToGlue($customerTransfer);
        $this->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());
    }

    /**
     * @param string $orderReference
     *
     * @return void
     */
    public function assertResponseContainsAmendmentOrderReference(string $orderReference): void
    {
        $attributes = $this->getDataFromResponseByJsonPath('$.data.attributes');

        $this->assertArrayHasKey('amendmentOrderReference', $attributes);
        $this->assertSame($orderReference, $attributes['amendmentOrderReference']);
    }

    /**
     * @param string $quoteName
     *
     * @return void
     */
    public function assertResponseContainsCorrectCartName(string $quoteName): void
    {
        $attributes = $this->getDataFromResponseByJsonPath('$.data.attributes');

        $this->assertArrayHasKey('name', $attributes);
        $this->assertSame($quoteName, $attributes['name']);
    }

    /**
     * @return string
     */
    public function getCartReorderUrl(): string
    {
        return $this->formatUrl('{cartReorderResource}?include=items', [
            'cartReorderResource' => CartReorderRestApiConfig::RESOURCE_CART_REORDER,
        ]);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function haveProductWithPriceAndStock(): ProductConcreteTransfer
    {
        $storeTransfer = $this->getLocator()->store()->facade()->getCurrentStore();
        $productConcreteTransfer = $this->haveFullProduct();

        $this->haveProductInStockForStore($storeTransfer, [
            StockProductTransfer::SKU => $productConcreteTransfer->getSku(),
            StockProductTransfer::IS_NEVER_OUT_OF_STOCK => 1,
        ]);

        $this->havePriceProduct([
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
            PriceProductTransfer::SKU_PRODUCT => $productConcreteTransfer->getSku(),
            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
            PriceProductTransfer::PRICE_TYPE_NAME => 'DEFAULT',
            PriceProductTransfer::MONEY_VALUE => [
                MoneyValueTransfer::NET_AMOUNT => 777,
                MoneyValueTransfer::GROSS_AMOUNT => 888,
            ],
        ]);

        return $productConcreteTransfer;
    }
}
