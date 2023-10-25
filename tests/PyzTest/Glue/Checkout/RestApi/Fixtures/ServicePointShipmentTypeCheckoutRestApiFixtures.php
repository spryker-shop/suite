<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi\Fixtures;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use SprykerTest\Shared\Shipment\Helper\ShipmentMethodDataHelper;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class ServicePointShipmentTypeCheckoutRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var string
     */
    protected const TEST_USERNAME = 'ServicePointShipmentTypeCheckoutRestApiFixtures';

    /**
     * @var string
     */
    protected const TEST_PASSWORD = 'change123';

    /**
     * @var string
     */
    protected const TEST_STORE_NAME = 'DE';

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected CustomerTransfer $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected ShipmentMethodTransfer $pickableShipmentMethodTransfer;

    /**
     * @var \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected ShipmentMethodTransfer $regularShipmentMethodTransfer;

    /**
     * @var \Generated\Shared\Transfer\ServicePointTransfer
     */
    protected ServicePointTransfer $servicePointTransfer;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected StoreTransfer $storeTransfer;

    /**
     * @var list<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected array $productConcreteTransfers;

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getPickableShipmentMethodTransfer(): ShipmentMethodTransfer
    {
        return $this->pickableShipmentMethodTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    public function getRegularShipmentMethodTransfer(): ShipmentMethodTransfer
    {
        return $this->regularShipmentMethodTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function getServicePointTransfer(): ServicePointTransfer
    {
        return $this->servicePointTransfer;
    }

    /**
     * @return list<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfers(): array
    {
        return $this->productConcreteTransfers;
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CheckoutApiTester $I): FixturesContainerInterface
    {
        $I->truncateSalesOrderThresholds();

        $this->createStore($I);
        $this->createCustomer($I);
        $this->createShipmentMethods($I);
        $this->createServicePoint($I);
        $this->createProductConcreteTransfers($I);

        return $this;
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    protected function createStore(CheckoutApiTester $I): void
    {
        $this->storeTransfer = $I->haveStore([StoreTransfer::NAME => static::TEST_STORE_NAME]);
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    protected function createCustomer(CheckoutApiTester $I): void
    {
        $customerTransfer = $I->haveCustomer([
            CustomerTransfer::USERNAME => static::TEST_USERNAME,
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);

        $this->customerTransfer = $I->confirmCustomer($customerTransfer);
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    protected function createShipmentMethods(CheckoutApiTester $I): void
    {
        $shipmentTypeTransfer = $I->havePickableShipmentType($this->storeTransfer);
        $this->pickableShipmentMethodTransfer = $I->haveShipmentMethod(
            [ShipmentMethodTransfer::IS_ACTIVE => true],
            [],
            ShipmentMethodDataHelper::DEFAULT_PRICE_LIST,
            [$this->storeTransfer->getIdStoreOrFail()],
        );
        $I->haveShipmentMethodShipmentTypeRelation(
            $this->pickableShipmentMethodTransfer->getIdShipmentMethod(),
            $shipmentTypeTransfer->getIdShipmentType(),
        );

        $this->regularShipmentMethodTransfer = $I->haveShipmentMethod(
            [ShipmentMethodTransfer::IS_ACTIVE => true],
            [],
            ShipmentMethodDataHelper::DEFAULT_PRICE_LIST,
            [$this->storeTransfer->getIdStoreOrFail()],
        );
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    protected function createServicePoint(CheckoutApiTester $I): void
    {
        $this->servicePointTransfer = $I->haveServicePointWithAddress($this->storeTransfer);
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    protected function createProductConcreteTransfers(CheckoutApiTester $I): void
    {
        $this->productConcreteTransfers = [
            $I->haveProductWithStock(),
            $I->haveProductWithStock(),
        ];
    }
}
