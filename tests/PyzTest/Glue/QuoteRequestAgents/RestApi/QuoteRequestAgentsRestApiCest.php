<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Glue\QuoteRequestAgents\RestApi;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester;
use PyzTest\Glue\QuoteRequestAgents\RestApi\Fixtures\QuoteRequestAgentsRestApiFixtures;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group QuoteRequestAgents
 * @group RestApi
 * @group QuoteRequestAgentsRestApiCest
 *  Add your own group annotations below this line
 * @group EndToEnd
 */
class QuoteRequestAgentsRestApiCest
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
     * @var \PyzTest\Glue\QuoteRequestAgents\RestApi\Fixtures\QuoteRequestAgentsRestApiFixtures
     */
    protected QuoteRequestAgentsRestApiFixtures $fixtures;

    /**
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(QuoteRequestAgentsApiTester $I): void
    {
        /** @var \PyzTest\Glue\QuoteRequestAgents\RestApi\Fixtures\QuoteRequestAgentsRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(QuoteRequestAgentsRestApiFixtures::class);

        $this->fixtures = $fixtures;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    public function testShouldGetPaginatedQuoteRequests(QuoteRequestAgentsApiTester $I): void
    {
        // Arrange
        $productConcreteTransfer1 = $this->fixtures->getProductConcreteTransfer1();
        $productConcreteTransfer2 = $this->fixtures->getProductConcreteTransfer2();
        $quoteRequestUuid1 = $this->createRequestReadyForAgentReviewAndReturnRequestUuid($productConcreteTransfer1, $I);
        $quoteRequestUuid2 = $this->createRequestReadyForAgentReviewAndReturnRequestUuid($productConcreteTransfer2, $I);

        $I->authorizeAgentToGlue($this->fixtures->getUserTransfer());

        // Act
        $this->getAgentQuoteRequests('page[offset]=0&page[limit]=2', $I);

        // Assert
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->assertEquals($quoteRequestUuid2, $I->grabDataFromResponseByJsonPath('$.data[0].id')[0]);
        $I->assertEquals($quoteRequestUuid1, $I->grabDataFromResponseByJsonPath('$.data[1].id')[0]);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    public function testShouldGetQuoteRequest(QuoteRequestAgentsApiTester $I): void
    {
        // Arrange
        $productConcreteTransfer1 = $this->fixtures->getProductConcreteTransfer1();
        $quoteRequestUuid = $this->createRequestReadyForAgentReviewAndReturnRequestUuid($productConcreteTransfer1, $I);

        $I->authorizeAgentToGlue($this->fixtures->getUserTransfer());

        // Act
        $this->getAgentQuoteRequest($quoteRequestUuid, $I);

        // Assert
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $I->assertEquals('waiting', $I->grabDataFromResponseByJsonPath('$.data.attributes.status')[0]);
        $I->assertEquals(
            $this->fixtures->getProductConcreteTransfer1()->getSku(),
            $I->grabDataFromResponseByJsonPath('$.data.attributes.shownVersion.cart.items[0].sku')[0],
        );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    public function testShouldCancelQuoteRequest(QuoteRequestAgentsApiTester $I): void
    {
        // Arrange
        $productConcreteTransfer1 = $this->fixtures->getProductConcreteTransfer1();
        $quoteRequestUuid = $this->createRequestReadyForAgentReviewAndReturnRequestUuid($productConcreteTransfer1, $I);

        $I->authorizeAgentToGlue($this->fixtures->getUserTransfer());

        // Act
        $this->cancelAgentQuoteRequest($quoteRequestUuid, $I);

        // Assert
        $I->seeResponseCodeIs(201);
        $I->assertEquals('canceled', $I->grabDataFromResponseByJsonPath('$.data.attributes.status')[0]);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    public function testShouldReviseQuoteRequest(QuoteRequestAgentsApiTester $I): void
    {
        // Arrange
        $productConcreteTransfer1 = $this->fixtures->getProductConcreteTransfer1();
        $quoteRequestUuid = $this->createRequestReadyForAgentReviewAndReturnRequestUuid($productConcreteTransfer1, $I);

        $I->authorizeAgentToGlue($this->fixtures->getUserTransfer());

        // Act
        $this->reviseAgentQuoteRequest($quoteRequestUuid, $I);

        // Assert
        $I->seeResponseCodeIs(201);
        $I->assertEquals('in-progress', $I->grabDataFromResponseByJsonPath('$.data.attributes.status')[0]);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    public function testShouldUpdateQuoteRequest(QuoteRequestAgentsApiTester $I): void
    {
        // Arrange
        $productConcreteTransfer1 = $this->fixtures->getProductConcreteTransfer1();
        $quoteRequestUuid = $this->createRequestReadyForAgentReviewAndReturnRequestUuid($productConcreteTransfer1, $I);
        $groupKey = $I->grabDataFromResponseByJsonPath('$.data.attributes.shownVersion.cart.items[0].groupKey')[0];

        $I->authorizeAgentToGlue($this->fixtures->getUserTransfer());
        $this->reviseAgentQuoteRequest($quoteRequestUuid, $I);

        // Act
        $this->updateAgentQuoteRequest($quoteRequestUuid, [
            'unitPriceMap' => [$groupKey => 666],
            'metadata' => [
                'purchase_order_number' => 'PO-123456',
                'delivery_date' => '2046-05-20',
                'note' => 'Updated note',
            ],
        ], $I);

        // Assert
        $I->seeResponseCodeIs(200);
        $I->assertEquals(666, $I->grabDataFromResponseByJsonPath('$.data.attributes.shownVersion.cart.items[0].unitPrice')[0]);
        $I->assertEquals('Updated note', $I->grabDataFromResponseByJsonPath('$.data.attributes.shownVersion.metadata.note')[0]);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    public function testShouldSendQuoteRequestToCustomer(QuoteRequestAgentsApiTester $I): void
    {
        // Arrange
        $productConcreteTransfer1 = $this->fixtures->getProductConcreteTransfer1();
        $quoteRequestUuid = $this->createRequestReadyForAgentReviewAndReturnRequestUuid($productConcreteTransfer1, $I);

        $I->authorizeAgentToGlue($this->fixtures->getUserTransfer());
        $this->reviseAgentQuoteRequest($quoteRequestUuid, $I);

        $this->updateAgentQuoteRequest($quoteRequestUuid, ['metadata' => ['note' => 'No discounts, sorry dude :(']], $I);

        // Act
        $this->sendAgentQuoteRequestToCustomer($quoteRequestUuid, $I);

        // Assert
        $I->seeResponseCodeIs(201);
        $I->assertEquals('ready', $I->grabDataFromResponseByJsonPath('$.data.attributes.status')[0]);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    public function testShouldConvertQuoteRequestToQuote(QuoteRequestAgentsApiTester $I): void
    {
        // Arrange
        $productConcreteTransfer1 = $this->fixtures->getProductConcreteTransfer1();
        $quoteRequestUuid = $this->createRequestReadyForAgentReviewAndReturnRequestUuid($productConcreteTransfer1, $I);

        $I->authorizeAgentToGlue($this->fixtures->getUserTransfer());
        $this->reviseAgentQuoteRequest($quoteRequestUuid, $I);
        $this->sendAgentQuoteRequestToCustomer($quoteRequestUuid, $I);

        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        // Act
        $this->convertQuoteRequestToQuote($quoteRequestUuid, $I);

        // Assert
        $I->seeResponseCodeIs(201);
        $I->assertTrue($I->grabDataFromResponseByJsonPath('$.data.attributes.isLocked')[0]);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return string
     */
    protected function createRequestReadyForAgentReviewAndReturnRequestUuid(
        ProductConcreteTransfer $productConcreteTransfer,
        QuoteRequestAgentsApiTester $I,
    ): string {
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $cartUuid = $this->createNewCartAndReturnCartUuid('cart', $I);
        $this->addProductToCart($cartUuid, $productConcreteTransfer->getSku(), 1, $I);

        $quoteRequestUuid = $this->createQuoteRequestAndReturnRequestUuid($cartUuid, $I);
        $this->sendQuoteRequestToAgent($quoteRequestUuid, $I);

        return $quoteRequestUuid;
    }

    /**
     * @param string $cartName
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return string
     */
    protected function createNewCartAndReturnCartUuid(string $cartName, QuoteRequestAgentsApiTester $I): string
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
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    protected function addProductToCart(string $cartUuid, string $sku, int $quantity, QuoteRequestAgentsApiTester $I): void
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
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return string
     */
    protected function createQuoteRequestAndReturnRequestUuid(string $cartUuid, QuoteRequestAgentsApiTester $I): string
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
     * @param array<string, mixed> $attributes
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    protected function updateAgentQuoteRequest(string $quoteRequestUuid, array $attributes, QuoteRequestAgentsApiTester $I): void
    {
        $I->sendPatch('/agent-quote-requests/' . $quoteRequestUuid, [
            'data' => [
                'type' => 'agent-quote-requests',
                'attributes' => $attributes,
            ],
        ]);
    }

    /**
     * @param string $quoteRequestUuid
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    protected function cancelAgentQuoteRequest(string $quoteRequestUuid, QuoteRequestAgentsApiTester $I): void
    {
        $I->sendPost('/agent-quote-requests/' . $quoteRequestUuid . '/agent-quote-request-cancel', [
            'data' => [
                'type' => 'agent-quote-request-cancel',
                'attributes' => [],
            ],
        ]);
    }

    /**
     * @param string $quoteRequestUuid
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    protected function sendQuoteRequestToAgent(string $quoteRequestUuid, QuoteRequestAgentsApiTester $I): void
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
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    protected function sendAgentQuoteRequestToCustomer(string $quoteRequestUuid, QuoteRequestAgentsApiTester $I): void
    {
        $I->sendPost('/agent-quote-requests/' . $quoteRequestUuid . '/agent-quote-request-send-to-customer', [
            'data' => [
                'type' => 'agent-quote-request-send-to-customer',
                'attributes' => [],
            ],
        ]);
    }

    /**
     * @param string $quoteRequestUuid
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    protected function convertQuoteRequestToQuote(string $quoteRequestUuid, QuoteRequestAgentsApiTester $I): void
    {
        $I->sendPost('/quote-requests/' . $quoteRequestUuid . '/quote-request-convert-to-quote', [
            'data' => [
                'type' => 'quote-request-convert-to-quote',
                'attributes' => [],
            ],
        ]);
    }

    /**
     * @param string $quoteRequestUuid
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    protected function reviseAgentQuoteRequest(string $quoteRequestUuid, QuoteRequestAgentsApiTester $I): void
    {
        $I->sendPost('/agent-quote-requests/' . $quoteRequestUuid . '/agent-quote-request-revise', [
            'data' => [
                'type' => 'agent-quote-request-revise',
                'attributes' => [],
            ],
        ]);
    }

    /**
     * @param string $quoteRequestUuid
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    protected function getAgentQuoteRequest(string $quoteRequestUuid, QuoteRequestAgentsApiTester $I): void
    {
        $I->sendGet('/agent-quote-requests/' . $quoteRequestUuid);
    }

    /**
     * @param string $query
     * @param \PyzTest\Glue\QuoteRequestAgents\QuoteRequestAgentsApiTester $I
     *
     * @return void
     */
    protected function getAgentQuoteRequests(string $query, QuoteRequestAgentsApiTester $I): void
    {
        $I->sendGet('/agent-quote-requests?' . $query);
    }
}
