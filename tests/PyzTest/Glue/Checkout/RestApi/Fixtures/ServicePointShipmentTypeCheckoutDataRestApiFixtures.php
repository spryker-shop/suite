<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout\RestApi\Fixtures;

use ArrayObject;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ServicePointAddressTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use SprykerTest\Shared\Shipment\Helper\ShipmentMethodDataHelper;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class ServicePointShipmentTypeCheckoutDataRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var string
     */
    protected const TEST_USERNAME = 'CheckoutDataRestApiFixtures';

    /**
     * @var string
     */
    protected const TEST_PASSWORD = 'change123';

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected CustomerTransfer $customerTransfer;

    /**
     * @var \Generated\Shared\Transfer\QuoteTransfer
     */
    protected QuoteTransfer $quoteTransfer;

    /**
     * @var \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected ShipmentMethodTransfer $pickableShipmentMethodTransfer;

    /**
     * @var \Generated\Shared\Transfer\ShipmentMethodTransfer
     */
    protected ShipmentMethodTransfer $nonPickableShipmentMethodTransfer;

    /**
     * @var \Generated\Shared\Transfer\ServicePointTransfer
     */
    protected ServicePointTransfer $servicePointWithAddress;

    /**
     * @var \Generated\Shared\Transfer\ServicePointTransfer
     */
    protected ServicePointTransfer $servicePoint;

    /**
     * @var \Generated\Shared\Transfer\ServicePointTransfer
     */
    protected ServicePointTransfer $servicePointWithoutAddress;

    /**
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function getQuoteTransfer(): QuoteTransfer
    {
        return $this->quoteTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function getServicePointWithoutAddress(): ServicePointTransfer
    {
        return $this->servicePointWithoutAddress;
    }

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
    public function getNonPickableShipmentMethodTransfer(): ShipmentMethodTransfer
    {
        return $this->nonPickableShipmentMethodTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function getServicePoint(): ServicePointTransfer
    {
        return $this->servicePoint;
    }

    /**
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function getServicePointWithAddress(): ServicePointTransfer
    {
        return $this->servicePointWithAddress;
    }

    /**
     * @param \PyzTest\Glue\Checkout\CheckoutApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CheckoutApiTester $I): FixturesContainerInterface
    {
        $I->truncateSalesOrderThresholds();

        $customerTransfer = $I->haveCustomer([
            CustomerTransfer::USERNAME => static::TEST_USERNAME,
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);

        $productConcreteTransfer1 = $I->haveProductWithStock();
        $productConcreteTransfer2 = $I->haveProductWithStock();

        $storeTransfer = $I->getStoreFacade()->getCurrentStore();
        $this->pickableShipmentMethodTransfer = $I->haveShipmentMethod(
            [
                ShipmentMethodTransfer::CARRIER_NAME => 'Spryker Dummy Shipment with pickable Shipment Type',
                ShipmentMethodTransfer::NAME => 'Standard with pickable Shipment Type',
            ],
            [],
            ShipmentMethodDataHelper::DEFAULT_PRICE_LIST,
            [$storeTransfer->getIdStoreOrFail()],
        );
        $pickableShipmentTypeTransfer = $I->havePickableShipmentType($storeTransfer);
        $I->addShipmentTypeToShipmentMethod($this->pickableShipmentMethodTransfer, $pickableShipmentTypeTransfer);

        $this->nonPickableShipmentMethodTransfer = $I->haveShipmentMethod(
            [
                ShipmentMethodTransfer::CARRIER_NAME => 'Spryker Dummy Shipment',
                ShipmentMethodTransfer::NAME => 'Standard',
            ],
            [],
            ShipmentMethodDataHelper::DEFAULT_PRICE_LIST,
            [$storeTransfer->getIdStoreOrFail()],
        );

        $this->servicePointWithoutAddress = $I->haveServicePointWithoutAddress($storeTransfer);

        $this->servicePoint = $I->haveServicePointWithAddress($storeTransfer);
        $serviceTransfer = $I->havePickableService($pickableShipmentTypeTransfer, [
            ServiceTransfer::SERVICE_POINT => $this->servicePoint->toArray(),
            ServiceTransfer::IS_ACTIVE => true,
        ]);

        $merchantTransfer = $I->haveMerchantWithStoreRelation($storeTransfer);
        $stockTransfer = $I->haveStock([
            StockTransfer::IS_ACTIVE => true,
            StockTransfer::STORE_RELATION => (new StoreRelationTransfer())->addIdStores($storeTransfer->getIdStoreOrFail()),
        ]);

        $productOfferTransfer1 = $I->haveProductOfferWithShipmentTypeAndServiceRelations(
            $productConcreteTransfer1,
            $serviceTransfer,
            $pickableShipmentTypeTransfer,
            [
                ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
                ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
                ProductOfferStockTransfer::STOCK => $stockTransfer->toArray(),
            ],
        );
        $productOfferTransfer2 = $I->haveProductOfferWithShipmentTypeAndServiceRelations(
            $productConcreteTransfer2,
            $serviceTransfer,
            $pickableShipmentTypeTransfer,
            [
                ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
                ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
                ProductOfferStockTransfer::STOCK => $stockTransfer->toArray(),
            ],
        );

        $countryTransfer = $I->haveCountry([
            CountryTransfer::ISO2_CODE => 'DE',
        ]);
        $this->servicePointWithAddress = $I->haveServicePoint(
            [
                ServicePointTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
            ],
        );
        $servicePointAddressTransfer = $I->haveServicePointAddress(
            [
                ServicePointAddressTransfer::SERVICE_POINT => $this->servicePointWithAddress->toArray(),
                ServicePointAddressTransfer::COUNTRY => $countryTransfer->toArray(),
            ],
        );
        $this->servicePointWithAddress->setAddress($servicePointAddressTransfer);

        $this->customerTransfer = $I->confirmCustomer($customerTransfer);
        $this->quoteTransfer = $I->havePersistentQuoteWithProductOfferItems(
            $this->customerTransfer,
            [$productOfferTransfer1, $productOfferTransfer2],
        );

        return $this;
    }
}
