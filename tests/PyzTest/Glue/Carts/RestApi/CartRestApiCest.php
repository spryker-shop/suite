<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\CustomerTransfer;
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
    protected const RESOURCE_TYPE_ACCESS_TOKENS = 'access-tokens';
    protected const RESOURCE_TYPE_GUEST_CART_ITEMS = 'guest-cart-items';
    protected const RESOURCE_TYPE_CARTS = 'carts';

    protected const ANONYMOUS_CUSTOMER_REFERENCE = '666';
    protected const TEST_PASSWORD = 'test password';
    protected const NON_EXISTING_CUSTOMER_EMAIL = 'test_non_existing_email@spryker.com';
    protected const NON_EXISTING_CART_UUID = 'non-existing-cart-uuid';

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

        $cartUuid = $this->requestCreateGuestCart($I);

        $I->amUnauthorizedGlueUser(static::ANONYMOUS_CUSTOMER_REFERENCE);

        $this->requestCustomerLogin($I, $customerEmail);

        $this->requestFindCartByUuid($I, $cartUuid);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCustomerCreateWithoutEmail(CartsApiTester $I): void
    {
        // Arrange
        $customerTransfer = $this->createCustomerTransfer();

        // Act
        $I->sendPOST('customers', [
            'data' => [
                'type' => 'customers',
                'attributes' => [
                    'salutation' => $customerTransfer->getSalutation(),
                    'firstName' => $customerTransfer->getFirstName(),
                    'lastName' => $customerTransfer->getLastName(),
                    'password' => $customerTransfer->getNewPassword(),
                    'confirmPassword' => $customerTransfer->getNewPassword(),
                    'acceptedTerms' => true,
                ],
            ],
        ]);

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCustomerCreateWithoutPassword(CartsApiTester $I): void
    {
        // Arrange
        $customerTransfer = $this->createCustomerTransfer();
        $customerTransfer->setEmail(static::NON_EXISTING_CUSTOMER_EMAIL);

        // Act
        $I->sendPOST('customers', [
            'data' => [
                'type' => 'customers',
                'attributes' => [
                    'salutation' => $customerTransfer->getSalutation(),
                    'firstName' => $customerTransfer->getFirstName(),
                    'lastName' => $customerTransfer->getLastName(),
                    'email' => $customerTransfer->getEmail(),
                    'confirmPassword' => $customerTransfer->getNewPassword(),
                    'acceptedTerms' => true,
                ],
            ],
        ]);

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCustomerCreateWithNonAcceptedTerms(CartsApiTester $I): void
    {
        // Arrange
        $customerTransfer = $this->createCustomerTransfer();

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
                    'acceptedTerms' => false,
                ],
            ],
        ]);

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCreateGuestCartWithoutAnonymousCustomerUniqueId(CartsApiTester $I): void
    {
        // Act
        $I->sendPOST(
            static::RESOURCE_TYPE_GUEST_CART_ITEMS,
            [
                'data' => [
                    'type' => static::RESOURCE_TYPE_GUEST_CART_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer()->getSku(),
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCreateGuestCartWithoutSku(CartsApiTester $I): void
    {
        $I->amUnauthorizedGlueUser(static::ANONYMOUS_CUSTOMER_REFERENCE);

        // Act
        $I->sendPOST(
            static::RESOURCE_TYPE_GUEST_CART_ITEMS,
            [
                'data' => [
                    'type' => static::RESOURCE_TYPE_GUEST_CART_ITEMS,
                    'attributes' => [
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCreateGuestCartWithoutQuantity(CartsApiTester $I): void
    {
        $I->amUnauthorizedGlueUser(static::ANONYMOUS_CUSTOMER_REFERENCE);

        // Act
        $I->sendPOST(
            static::RESOURCE_TYPE_GUEST_CART_ITEMS,
            [
                'data' => [
                    'type' => static::RESOURCE_TYPE_GUEST_CART_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer()->getSku(),
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestCustomerLoginWithNonExistingCustomer(CartsApiTester $I): void
    {
        // Arrange
        $customerTransfer = (new CustomerTransfer())
            ->setEmail(static::NON_EXISTING_CUSTOMER_EMAIL)
            ->setNewPassword('');

        // Act
        $I->haveAuthorizationToGlue($customerTransfer);

        //assert
        $I->seeResponseCodeIs(HttpCode::UNAUTHORIZED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function requestFindCartByUuidWithNonExistingCartUuid(CartsApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'carts/{cartUuid}',
                [
                    'cartUuid' => static::NON_EXISTING_CART_UUID,
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return string
     */
    protected function requestCreateGuestCart(CartsApiTester $I): string
    {
        // Arrange
        $I->amUnauthorizedGlueUser(static::ANONYMOUS_CUSTOMER_REFERENCE);

        // Act
        $I->sendPOST(
            static::RESOURCE_TYPE_GUEST_CART_ITEMS,
            [
                'data' => [
                    'type' => static::RESOURCE_TYPE_GUEST_CART_ITEMS,
                    'attributes' => [
                        'sku' => $this->fixtures->getProductConcreteTransfer()->getSku(),
                        'quantity' => 1,
                    ],
                ],
            ]
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();

        $I->amSure('Returned resource is of type guest-carts')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('guest-carts');

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
        $customerTransfer = $this->createCustomerTransfer();
        $customerTransfer->setEmail($customerEmail);

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

        $I->amSure('Returned resource is of type customers')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('customers');

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
    protected function requestCustomerLogin(CartsApiTester $I, string $customerEmail): string
    {
        // Arrange
        $customerTransfer = (new CustomerTransfer())
            ->setEmail($customerEmail)
            ->setNewPassword(static::TEST_PASSWORD);

        $token = $I->haveAuthorizationToGlue($customerTransfer)['accessToken'];

        // Act
        $I->amAuthorizedGlueUser($token);

        //assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure(sprintf('Returned resource is of type %s', static::RESOURCE_TYPE_ACCESS_TOKENS))
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(static::RESOURCE_TYPE_ACCESS_TOKENS);

        return $token;
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
                sprintf('%s/{cartUuid}', static::RESOURCE_TYPE_CARTS),
                [
                    'cartUuid' => $cartUuid,
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure(sprintf('Returned resource is of type %s', static::RESOURCE_TYPE_CARTS))
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(static::RESOURCE_TYPE_CARTS);

        $I->amSure('Returned resource has correct id')
            ->whenI()
            ->seeSingleResourceIdEqualTo($cartUuid);
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function createCustomerTransfer(): CustomerTransfer
    {
        return (new CustomerTransfer())
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setSalutation('Mr')
            ->setNewPassword(static::TEST_PASSWORD);
    }
}
