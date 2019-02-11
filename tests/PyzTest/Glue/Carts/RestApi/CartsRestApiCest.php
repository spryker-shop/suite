<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Carts\CartsApiTester;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group CartsRestApiCest
 * Add your own group annotations below this line
 */
class CartsRestApiCest
{
    /**
     * @var \PyzTest\Glue\Carts\RestApi\CartsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function _authorize(CartsApiTester $I): void
    {
        $I->amAuthorizedGlueUser(
            $this->fixtures->getGlueToken(),
            $this->fixtures->getCustomer()
        );
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CartsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(CartsRestApiFixtures::class);
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function unableToCreateRegularCartUntilMultiCartIsSupported(CartsApiTester $I): void
    {
        $priceMode = 'GROSS_MODE';
        $currency = 'EUR';
        $store = 'DE';

        $I->sendPOST('carts', [
            'data' => [
                'type' => 'carts',
                'attributes' => [
                    'priceMode' => $priceMode,
                    'currency' => $currency,
                    'store' => $store,
                ],
            ],
        ]);
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @before _authorize
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestForAddingAnItemIntoTheRegularCart(CartsApiTester $I): void
    {
        $sku = '198_19692589';

        $I->sendPOST($I->formatUrl('carts/{cartId}/items', ['cartId' => $this->fixtures->getCartId()]), [
            'data' => [
                'type' => 'items',
                'attributes' => [
                    'sku' => $sku,
                    'quantity' => 1,
                ],
            ],
        ]);
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('response has correct type and cart id is the same')->whenI()->seeResponseJsonPathContains([
            'type' => 'carts',
            'id' => $this->fixtures->getCartId(),
        ], '$.data');

        $I->amSure('response has priceMode, currency, discounts and store returned')->whenI()->seeResponseJsonPathContains([
            'priceMode' => 'GROSS_MODE',
            'currency' => 'EUR',
            'store' => 'DE',
        ], '$.data.attributes');

        $I->amSure('response has totals returned')->whenI()->seeResponseJsonPathContains([
            'expenseTotal' => 0,
            'discountTotal' => 0,
            'taxTotal' => 411,
            'subtotal' => 6277,
            'grandTotal' => 6277,
        ], '$.data.attributes.totals');

        $I->amSure('response has self link')->whenI()->seeResponseJsonPathContains([
            'self' => $I->formatFullUrl('carts/{cartId}', [
                'cartId' => $this->fixtures->getCartId(),
            ]),
        ], '$.data.links');
    }
}
