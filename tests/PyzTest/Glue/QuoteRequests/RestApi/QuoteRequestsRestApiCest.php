<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Glue\QuoteRequests\RestApi;

use PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester;
use PyzTest\Glue\QuoteRequests\RestApi\Fixtures\QuoteRequestsRestApiFixtures;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group QuoteRequests
 * @group RestApi
 * @group QuoteRequestsRestApiCest
 *  Add your own group annotations below this line
 * @group EndToEnd
 */
class QuoteRequestsRestApiCest
{
    /**
     * @var string
     */
    protected const METADATA = [
        'purchase_order_number' => 'PO-123456',
        'delivery_date' => '2046-05-20',
        'note' => 'Note for the request',
    ];

    /**
     * @var \PyzTest\Glue\QuoteRequests\RestApi\Fixtures\QuoteRequestsRestApiFixtures
     */
    protected QuoteRequestsRestApiFixtures $fixtures;

    /**
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(QuoteRequestsApiTester $I): void
    {
        /** @var \PyzTest\Glue\QuoteRequests\RestApi\Fixtures\QuoteRequestsRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(QuoteRequestsRestApiFixtures::class);

        $this->fixtures = $fixtures;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    public function testShouldCreateQuoteRequest(QuoteRequestsApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $cartUuid = $this->createNewCartAndReturnCartUuid('cart1', $I);
        $this->addProductToCart($cartUuid, $this->fixtures->getProductConcreteTransfer1()->getSku(), 3, $I);

        // Act
        $this->createQuoteRequestAndReturnRequestUuid($cartUuid, $I);

        // Assert
        $I->seeResponseCodeIs(201);
        $I->seeResponseIsJson();
        $I->assertEquals('draft', $I->grabDataFromResponseByJsonPath('$.data.attributes.status')[0]);
        $I->assertEquals(
            $this->fixtures->getProductConcreteTransfer1()->getSku(),
            $I->grabDataFromResponseByJsonPath('$.data.attributes.shownVersion.cart.items[0].sku')[0],
        );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    public function testShouldCancelQuoteRequest(QuoteRequestsApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $cartUuid = $this->createNewCartAndReturnCartUuid('cart2', $I);
        $this->addProductToCart($cartUuid, $this->fixtures->getProductConcreteTransfer1()->getSku(), 3, $I);

        $quoteRequestUuid = $this->createQuoteRequestAndReturnRequestUuid($cartUuid, $I);

        // Act
        $this->cancelQuoteRequest($quoteRequestUuid, $I);

        // Assert
        $I->seeResponseCodeIs(201);
        $I->assertEquals('canceled', $I->grabDataFromResponseByJsonPath('$.data.attributes.status')[0]);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    public function testShouldSendQuoteRequestToAgent(QuoteRequestsApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $cartUuid = $this->createNewCartAndReturnCartUuid('cart3', $I);
        $this->addProductToCart($cartUuid, $this->fixtures->getProductConcreteTransfer1()->getSku(), 3, $I);

        $quoteRequestUuid = $this->createQuoteRequestAndReturnRequestUuid($cartUuid, $I);

        // Act
        $this->sendQuoteRequestToAgent($quoteRequestUuid, $I);

        // Assert
        $I->seeResponseCodeIs(201);
        $I->assertEquals('waiting', $I->grabDataFromResponseByJsonPath('$.data.attributes.status')[0]);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    public function testShouldUpdateQuoteRequest(QuoteRequestsApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $cartUuid = $this->createNewCartAndReturnCartUuid('cart4', $I);
        $this->addProductToCart($cartUuid, $this->fixtures->getProductConcreteTransfer1()->getSku(), 3, $I);

        $quoteRequestUuid = $this->createQuoteRequestAndReturnRequestUuid($cartUuid, $I);
        $this->addProductToCart($cartUuid, $this->fixtures->getProductConcreteTransfer2()->getSku(), 1, $I);

        // Act
        $this->updateQuoteRequest($quoteRequestUuid, $cartUuid, $I);

        // Assert
        $I->seeResponseCodeIs(200);
        $I->assertEquals(
            $this->fixtures->getProductConcreteTransfer1()->getSku(),
            $I->grabDataFromResponseByJsonPath('$.data.attributes.shownVersion.cart.items[0].sku')[0],
        );
        $I->assertEquals(
            $this->fixtures->getProductConcreteTransfer2()->getSku(),
            $I->grabDataFromResponseByJsonPath('$.data.attributes.shownVersion.cart.items[1].sku')[0],
        );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    public function testShouldNotReviseQuoteRequest(QuoteRequestsApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $cartUuid = $this->createNewCartAndReturnCartUuid('cart5', $I);
        $this->addProductToCart($cartUuid, $this->fixtures->getProductConcreteTransfer1()->getSku(), 3, $I);

        $quoteRequestUuid = $this->createQuoteRequestAndReturnRequestUuid($cartUuid, $I);

        // Act
        $this->reviseQuoteRequest($quoteRequestUuid, $I);

        // Assert
        $I->seeResponseCodeIs(422);
        $I->assertEquals('Wrong Quote Request status for this operation.', $I->grabDataFromResponseByJsonPath('$.errors[0].detail')[0]);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    public function testShouldGetQuoteRequest(QuoteRequestsApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $cartUuid = $this->createNewCartAndReturnCartUuid('cart6', $I);
        $this->addProductToCart($cartUuid, $this->fixtures->getProductConcreteTransfer1()->getSku(), 3, $I);

        $quoteRequestUuid = $this->createQuoteRequestAndReturnRequestUuid($cartUuid, $I);

        // Act
        $this->getQuoteRequest($quoteRequestUuid, $I);

        // Assert
        $I->seeResponseCodeIs(200);
        $I->assertEquals($quoteRequestUuid, $I->grabDataFromResponseByJsonPath('$.data.id')[0]);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    public function testShouldGetPaginatedQuoteRequests(QuoteRequestsApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $cartUuid = $this->createNewCartAndReturnCartUuid('cart6', $I);
        $this->addProductToCart($cartUuid, $this->fixtures->getProductConcreteTransfer1()->getSku(), 3, $I);

        $quoteRequestUuid1 = $this->createQuoteRequestAndReturnRequestUuid($cartUuid, $I);
        $quoteRequestUuid2 = $this->createQuoteRequestAndReturnRequestUuid($cartUuid, $I);

        // Act
        $this->getQuoteRequests('page[offset]=0&page[limit]=2', $I);

        // Assert
        $I->seeResponseCodeIs(200);
        $I->assertEquals($quoteRequestUuid2, $I->grabDataFromResponseByJsonPath('$.data[0].id')[0]);
        $I->assertEquals($quoteRequestUuid1, $I->grabDataFromResponseByJsonPath('$.data[1].id')[0]);
    }

    /**
     * @param string $cartName
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return string
     */
    protected function createNewCartAndReturnCartUuid(string $cartName, QuoteRequestsApiTester $I): string
    {
        $I->sendPOST('/carts', [
            'data' => [
                'type' => 'carts',
                'attributes' => [
                    'priceMode' => 'GROSS_MODE',
                    'currency' => 'EUR',
                    'store' => 'DE',
                    'name' => $cartName,
                ],
            ],
        ]);

        return $I->grabDataFromResponseByJsonPath('$.data.id')[0];
    }

    /**
     * @param string $cartUuid
     * @param string $sku
     * @param int $quantity
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    protected function addProductToCart(string $cartUuid, string $sku, int $quantity, QuoteRequestsApiTester $I): void
    {
        $I->sendPOST('/carts/' . $cartUuid . '/items', [
            'data' => [
                'type' => 'items',
                'attributes' => [
                    'sku' => $sku,
                    'quantity' => $quantity,
                ],
            ],
        ]);
    }

    /**
     * @param string $cartUuid
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return string
     */
    protected function createQuoteRequestAndReturnRequestUuid(string $cartUuid, QuoteRequestsApiTester $I): string
    {
        $I->sendPOST('/quote-requests', [
            'data' => [
                'type' => 'quote-requests',
                'attributes' => [
                    'cartUuid' => $cartUuid,
                    'metadata' => static::METADATA,
                ],
            ],
        ]);

        return $I->grabDataFromResponseByJsonPath('$.data.id')[0];
    }

    /**
     * @param string $quoteRequestUuid
     * @param string $cartUuid
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    protected function updateQuoteRequest(string $quoteRequestUuid, string $cartUuid, QuoteRequestsApiTester $I): void
    {
        $I->sendPatch('/quote-requests/' . $quoteRequestUuid, [
            'data' => [
                'type' => 'quote-requests',
                'attributes' => [
                    'cartUuid' => $cartUuid,
                    'metadata' => static::METADATA,
                ],
            ],
        ]);
    }

    /**
     * @param string $quoteRequestUuid
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    protected function cancelQuoteRequest(string $quoteRequestUuid, QuoteRequestsApiTester $I): void
    {
        $I->sendPost('/quote-requests/' . $quoteRequestUuid . '/quote-request-cancel', [
            'data' => [
                'type' => 'quote-request-cancel',
                'attributes' => [],
            ],
        ]);
    }

    /**
     * @param string $quoteRequestUuid
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    protected function sendQuoteRequestToAgent(string $quoteRequestUuid, QuoteRequestsApiTester $I): void
    {
        $I->sendPost('/quote-requests/' . $quoteRequestUuid . '/quote-request-send-to-user', [
            'data' => [
                'type' => 'quote-request-send-to-user',
                'attributes' => [],
            ],
        ]);
    }

    /**
     * @param string $quoteRequestUuid
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    protected function reviseQuoteRequest(string $quoteRequestUuid, QuoteRequestsApiTester $I): void
    {
        $I->sendPost('/quote-requests/' . $quoteRequestUuid . '/quote-request-revise', [
            'data' => [
                'type' => 'quote-request-revise',
                'attributes' => [],
            ],
        ]);
    }

    /**
     * @param string $quoteRequestUuid
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    protected function getQuoteRequest(string $quoteRequestUuid, QuoteRequestsApiTester $I): void
    {
        $I->sendGet('/quote-requests/' . $quoteRequestUuid);
    }

    /**
     * @param string $query
     * @param \PyzTest\Glue\QuoteRequests\QuoteRequestsApiTester $I
     *
     * @return void
     */
    protected function getQuoteRequests(string $query, QuoteRequestsApiTester $I): void
    {
        $I->sendGet('/quote-requests?' . $query);
    }
}
