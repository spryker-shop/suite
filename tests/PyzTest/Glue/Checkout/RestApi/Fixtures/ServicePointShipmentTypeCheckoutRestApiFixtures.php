<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi\Fixtures;

use ArrayObject;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
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
     * @var \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    protected ShipmentTypeTransfer $pickableShipmentTypeTransfer;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected StoreTransfer $storeTransfer;

    /**
     * @var list<\Generated\Shared\Transfer\ProductOfferTransfer>
     */
    protected array $productOfferTransfers;

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
     * @return list<\Generated\Shared\Transfer\ProductOfferTransfer>
     */
    public function getProductOfferTransfers(): array
    {
        return $this->productOfferTransfers;
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
        $this->createPickableShipmentType($I);
        $this->createShipmentMethods($I);
        $this->createServicePoint($I);
        $this->createProductOfferTransfers($I);

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
    protected function createPickableShipmentType(CheckoutApiTester $I): void
    {
        $this->pickableShipmentTypeTransfer = $I->havePickableShipmentType($this->storeTransfer);
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return void
     */
    protected function createShipmentMethods(CheckoutApiTester $I): void
    {
        $this->pickableShipmentMethodTransfer = $I->haveShipmentMethod(
            [ShipmentMethodTransfer::IS_ACTIVE => true],
            [],
            ShipmentMethodDataHelper::DEFAULT_PRICE_LIST,
            [$this->storeTransfer->getIdStoreOrFail()],
        );
        $I->haveShipmentMethodShipmentTypeRelation(
            $this->pickableShipmentMethodTransfer->getIdShipmentMethod(),
            $this->pickableShipmentTypeTransfer->getIdShipmentType(),
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
    protected function createProductOfferTransfers(CheckoutApiTester $I): void
    {
        $serviceTransfer = $I->havePickableService($this->pickableShipmentTypeTransfer, [
            ServiceTransfer::SERVICE_POINT => $this->servicePointTransfer->toArray(),
            ServiceTransfer::IS_ACTIVE => true,
        ]);
        $stockTransfer = $I->haveStock([
            StockTransfer::STORE_RELATION => (new StoreRelationTransfer())->addIdStores($this->storeTransfer->getIdStoreOrFail()),
        ]);
        $productConcreteTransfer1 = $I->haveProductWithStock();
        $productConcreteTransfer2 = $I->haveProductWithStock();

        $this->productOfferTransfers[] = $I->haveProductOfferWithShipmentTypeAndServiceRelations(
            $productConcreteTransfer1,
            $serviceTransfer,
            $this->pickableShipmentTypeTransfer,
            [
                ProductOfferTransfer::STORES => new ArrayObject([$this->storeTransfer]),
                ProductOfferStockTransfer::STOCK => $stockTransfer->toArray(),
            ],
        );
        $this->productOfferTransfers[] = $I->haveProductOfferWithShipmentTypeAndServiceRelations(
            $productConcreteTransfer2,
            $serviceTransfer,
            $this->pickableShipmentTypeTransfer,
            [
                ProductOfferTransfer::STORES => new ArrayObject([$this->storeTransfer]),
                ProductOfferStockTransfer::STOCK => $stockTransfer->toArray(),
            ],
        );
        $I->haveProductOfferWithStock(
            $productConcreteTransfer2,
            [
                ProductOfferTransfer::STORES => new ArrayObject([$this->storeTransfer]),
                ProductOfferStockTransfer::STOCK => $stockTransfer->toArray(),
            ],
        );
    }
}
