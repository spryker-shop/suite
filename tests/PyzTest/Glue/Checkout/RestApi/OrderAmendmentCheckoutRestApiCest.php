<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Glue\Checkout\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use PyzTest\Glue\Checkout\RestApi\Fixtures\OrderAmendmentCheckoutRestApiFixtures;
use Spryker\Glue\CartReorderRestApi\CartReorderRestApiConfig;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Checkout
 * @group RestApi
 * @group OrderAmendmentCheckoutRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 * @group CheckoutMinimumWorkflow
 */
class OrderAmendmentCheckoutRestApiCest
{
    /**
     * @var \PyzTest\Glue\Checkout\RestApi\Fixtures\OrderAmendmentCheckoutRestApiFixtures
     */
    protected OrderAmendmentCheckoutRestApiFixtures $fixtures;

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CheckoutApiTester $I): void
    {
        /** @var \PyzTest\Glue\Checkout\RestApi\Fixtures\OrderAmendmentCheckoutRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(OrderAmendmentCheckoutRestApiFixtures::class);
        $this->fixtures = $fixtures;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    public function requestCheckoutAmendedOrder(CheckoutApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $I->sendPOST($I->buildCheckoutUrl(), $this->buildPayloadForCheckout($I));
        $existingOrderReference = $I->getDataFromResponseByJsonPath('$.data.attributes.orderReference');
        $I->checkCondition();
        $I->checkTimeout();

        $I->sendPOST($I->buildCartReorderUrl(), [
            'data' => [
                'type' => CartReorderRestApiConfig::RESOURCE_CART_REORDER,
                'attributes' => [
                    'orderReference' => $existingOrderReference,
                    'isAmendment' => true,
                ],
            ],
        ]);

        $idCart = $I->getDataFromResponseByJsonPath('$.data.id');
        $I->sendPOST($I->buildAddCartItemUrl($idCart), [
            'data' => [
                'type' => CartsRestApiConfig::RESOURCE_CART_ITEMS,
                'attributes' => [
                    'sku' => $this->fixtures->getProductConcreteTransfers()[1]->getSku(),
                    'quantity' => 1,
                ],
            ],
        ]);

        // Act
        $I->sendPOST($I->buildCheckoutUrl(), $this->buildPayloadForCheckout($I, $idCart));

        // Assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->assertCheckoutResponseResourceHasCorrectData();

        $orderReference = $I->getDataFromResponseByJsonPath('$.data.attributes.orderReference');
        $salesOrderAmendmentEntity = $I->findSalesOrderAmendmentEntity($existingOrderReference);

        $I->assertSame($existingOrderReference, $orderReference);
        $I->assertSame($existingOrderReference, $salesOrderAmendmentEntity->getOriginalOrderReference());
        $I->assertSame($existingOrderReference, $salesOrderAmendmentEntity->getAmendedOrderReference());
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     * @param string|null $idCart
     *
     * @return array<string, array<mixed>>
     */
    protected function buildPayloadForCheckout(CheckoutApiTester $I, ?string $idCart = null): array
    {
        $customerTransfer = $this->fixtures->getCustomerTransfer();
        $shipmentMethodTransfer = $this->fixtures->getShipmentMethodTransfer();
        $quoteTransfer = $I->havePersistentQuoteWithItemsAndItemLevelShipment(
            $customerTransfer,
            [$I->getQuoteItemOverrideData($this->fixtures->getProductConcreteTransfers()[0], $shipmentMethodTransfer, 1)],
        );
        $shippingAddressTransfer = $quoteTransfer->getItems()[0]->getShipment()->getShippingAddress();

        return [
            'data' => [
                'type' => CheckoutRestApiConfig::RESOURCE_CHECKOUT,
                'attributes' => [
                    'idCart' => $idCart ?? $quoteTransfer->getUuid(),
                    'billingAddress' => $I->getAddressRequestPayload($quoteTransfer->getBillingAddress()),
                    'shippingAddress' => $I->getAddressRequestPayload($shippingAddressTransfer),
                    'customer' => $I->getCustomerRequestPayload($customerTransfer),
                    'payments' => $I->getPaymentRequestPayload(CheckoutApiTester::REQUEST_PARAM_PAYMENT_METHOD_NAME_CREDIT_CARD),
                    'shipment' => $I->getShipmentRequestPayload($shipmentMethodTransfer->getIdShipmentMethod()),
                ],
            ],
        ];
    }
}
