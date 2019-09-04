<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\CurrencyTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use PyzTest\Glue\Carts\CartsApiTester;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group CartRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CartRestApiCest
{
    protected const TEST_PASSWORD = 'test password';

    /**
     * @var \PyzTest\Glue\Carts\RestApi\CartsRestApiFixtures
     */
    protected $fixtures;

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
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartBecomesCustomerCartAfterCustomerLogin(CartsApiTester $I): void
    {
        $customerEmail = sprintf('%s@test.local.com', uniqid('glue-', true));

        $this->requestCustomerCreate($I, $customerEmail);

        $cartUuid = $this->requestCreateCart($I);

        $I->amUnauthorizedGlueUser($this->fixtures::ANONYMOUS_CUSTOMER_REFERENCE);

        $token = $this->requestCustomerAccessToken($I, $customerEmail);

        $this->requestCustomerLogin($I, $token);

        $this->requestFindCartByUuid($I, $cartUuid);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return string
     */
    protected function requestCreateCart(CartsApiTester $I): string
    {
        // Arrange
        $I->amUnauthorizedGlueUser($this->fixtures::ANONYMOUS_CUSTOMER_REFERENCE);

        $productConcreteTransfer = $I->haveFullProduct();
        $priceTypeTransfer = $I->havePriceType();
        $currencyId = $I->haveCurrency();

//        $priceProductOverride = [
//            PriceProductTransfer::ID_PRICE_PRODUCT => $productConcreteTransfer->getFkProductAbstract(),
//            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productConcreteTransfer->getAbstractSku(),
//            PriceProductTransfer::ID_PRODUCT => $productConcreteTransfer->getIdProductConcrete(),
//            PriceProductTransfer::PRICE_TYPE => $priceTypeTransfer,
//            PriceProductTransfer::MONEY_VALUE => [
//                MoneyValueTransfer::NET_AMOUNT => 100,
//                MoneyValueTransfer::GROSS_AMOUNT => 125,
//                MoneyValueTransfer::FK_CURRENCY => $currencyId,
//            ],
//        ];
//
//        $I->havePriceProduct($priceProductOverride);

        // Act
        $I->sendPOST('guest-cart-items',
            [
                'data' => [
                    'type' => 'guest-cart-items',
                    'attributes' => [
                        //'sku' => $productConcreteTransfer->getSku(),
                        'sku' => '013_25904584',
                        'quantity' => 1,
                    ],
                ],
            ]);

        //assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();

        return $I->grabDataFromResponseByJsonPath('$.data')[0]['id'];
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     * @param string $customerEmail
     *
     * @return string
     */
    protected function requestCustomerCreate(CartsApiTester $I, string $customerEmail): string
    {
        // Arrange
        $customerTransfer = (new CustomerTransfer())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setSalutation('Mr')
            ->setEmail($customerEmail)
            ->setNewPassword(static::TEST_PASSWORD);

        // Act
        $I->sendPOST('customers', [
            'data' => [
                'type' => 'customers',
                'attributes' => [
                    'salutation' => $customerTransfer->getSalutation(),
                    'firstName' => $customerTransfer->getFirstName(),
                    'lastName' => $customerTransfer->getLastName(),
                    'email' => $customerTransfer->getEmail(),
                    'password' => $customerTransfer->getNewPassword(),
                    'confirmPassword' => $customerTransfer->getNewPassword(),
                    'acceptedTerms' => true,
                ],
            ],
        ]);

        //assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        return $I->grabDataFromResponseByJsonPath('$.data')[0]['id'];

    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     * @param string $customerEmail
     *
     * @return string
     */
    protected function requestCustomerAccessToken(CartsApiTester $I, string $customerEmail): string
    {
        $customerTransfer = (new CustomerTransfer())
            ->setEmail($customerEmail)
            ->setNewPassword(static::TEST_PASSWORD);

        $token = $I->haveAuthorizationToGlue($customerTransfer)['accessToken'];
        $I->amAuthorizedGlueUser($token);

        //assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();

        $I->amSure('Returned resource is of type access-tokens')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('access-tokens');

        return $token;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     * @param string $token
     *
     * @return void
     */
    protected function requestCustomerLogin(CartsApiTester $I, string $token): void
    {
        $I->amAuthorizedGlueUser($token);

        //assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();

        $I->amSure('Returned resource is of type access-tokens')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('access-tokens');
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     * @param string $cartUuid
     *
     * @return void
     */
    protected function requestFindCartByUuid(CartsApiTester $I, string $cartUuid): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'carts/{cartUuid}',
                [
                    'cartUuid' => $cartUuid,
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();

        $I->amSure('Returned resource is of type carts')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('carts');
    }
}
