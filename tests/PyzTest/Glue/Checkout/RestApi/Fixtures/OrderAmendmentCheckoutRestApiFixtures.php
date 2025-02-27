<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Glue\Checkout\RestApi\Fixtures;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use SprykerTest\Shared\Shipment\Helper\ShipmentMethodDataHelper;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class OrderAmendmentCheckoutRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var string
     */
    protected const TEST_USERNAME = 'OrderAmendmentCheckoutRestApiFixtures';

    /**
     * @var string
     */
    protected const TEST_PASSWORD = 'change123';

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected CustomerTransfer $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\AddressTransfer
     */
    protected AddressTransfer $customerAddress;

    /**
     * @var list<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected array $productConcreteTransfers;

    /**
     * @var \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected ShipmentMethodTransfer $shipmentMethodTransfer;

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function getCustomerAddress(): AddressTransfer
    {
        return $this->customerAddress;
    }

    /**
     * @return list<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfers(): array
    {
        return $this->productConcreteTransfers;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getShipmentMethodTransfer(): ShipmentMethodTransfer
    {
        return $this->shipmentMethodTransfer;
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CheckoutApiTester $I): FixturesContainerInterface
    {
        $I->truncateSalesOrderThresholds();
        $I->ensureSalesOrderAmendmentTableIsEmpty();

        $customerTransfer = $I->haveCustomer([
            CustomerTransfer::USERNAME => static::TEST_USERNAME,
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);
        $this->customerTransfer = $I->confirmCustomer($customerTransfer);

        $this->productConcreteTransfers = [
            $I->haveProductWithStock(),
            $I->haveProductWithStock(),
        ];

        $shipmentTypeTransfer = $I->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($I->getStoreFacade()->getCurrentStore()),
        ]);
        $this->shipmentMethodTransfer = $I->haveShipmentMethod(
            [
                ShipmentMethodTransfer::CARRIER_NAME => 'Spryker Dummy Shipment',
                ShipmentMethodTransfer::NAME => 'Standard',
            ],
            [],
            ShipmentMethodDataHelper::DEFAULT_PRICE_LIST,
            [
                $I->getStoreFacade()->getCurrentStore()->getIdStore(),
            ],
        );
        $I->addShipmentTypeToShipmentMethod($this->shipmentMethodTransfer, $shipmentTypeTransfer);

        $this->customerAddress = $I->haveCustomerAddress([
            AddressTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
            AddressTransfer::FK_COUNTRY => $I->haveCountry()->getIdCountry(),
        ]);

        return $this;
    }
}
