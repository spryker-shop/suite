<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi\Fixtures;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use SprykerTest\Shared\Shipment\Helper\ShipmentMethodDataHelper;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Checkout
 * @group RestApi
 * @group GuestCheckoutDataShipmentRelationshipsFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class GuestCheckoutDataShipmentRelationshipsFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var string
     */
    protected const ANONYMOUS_PREFIX = 'anonymous:';

    /**
     * @var string
     */
    protected string $guestCustomerReference;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected CustomerTransfer $guestCustomerTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected QuoteTransfer $guestQuoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected ShipmentMethodTransfer $shipmentMethodTransfer;

    /**
     * @var \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    protected ShipmentTypeTransfer $shipmentTypeTransfer;

    /**
     * @return string
     */
    public function getGuestCustomerReference(): string
    {
        return $this->guestCustomerReference;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getGuestCustomerTransfer(): CustomerTransfer
    {
        return $this->guestCustomerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getGuestQuoteTransfer(): QuoteTransfer
    {
        return $this->guestQuoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getShipmentMethodTransfer(): ShipmentMethodTransfer
    {
        return $this->shipmentMethodTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function getShipmentTypeTransfer(): ShipmentTypeTransfer
    {
        return $this->shipmentTypeTransfer;
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CheckoutApiTester $I): FixturesContainerInterface
    {
        $I->truncateSalesOrderThresholds();

        $this->guestCustomerReference = $I->createGuestCustomerReference();
        $this->guestCustomerTransfer = $I->createCustomerTransfer([
            CustomerTransfer::CUSTOMER_REFERENCE => static::ANONYMOUS_PREFIX . $this->guestCustomerReference,
        ]);

        $this->shipmentMethodTransfer = $I->haveShipmentMethod(
            [
                ShipmentMethodTransfer::CARRIER_NAME => 'Guest Spryker Dummy Shipment with Shipment Type',
                ShipmentMethodTransfer::NAME => 'Guest Standard with Shipment Type',
            ],
            [],
            ShipmentMethodDataHelper::DEFAULT_PRICE_LIST,
            [
                $I->getStoreFacade()->getCurrentStore()->getIdStore(),
            ],
        );

        $this->shipmentTypeTransfer = $I->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())
                ->addStores($I->haveStore([StoreTransfer::NAME => 'DE'])),
        ]);
        $I->haveShipmentMethodShipmentTypeRelation(
            $this->shipmentMethodTransfer->getIdShipmentMethodOrFail(),
            $this->shipmentTypeTransfer->getIdShipmentTypeOrFail(),
        );

        $this->guestQuoteTransfer = $I->havePersistentQuoteWithItemsAndItemLevelShipment(
            $this->guestCustomerTransfer,
            [$I->getQuoteItemOverrideData($I->haveProductWithStock(), $this->shipmentMethodTransfer, 10)],
        );

        return $this;
    }
}
