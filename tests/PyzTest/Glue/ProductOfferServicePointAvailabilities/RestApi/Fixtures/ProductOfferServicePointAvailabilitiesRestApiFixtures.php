<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\ProductOfferServicePointAvailabilities\RestApi\Fixtures;

use ArrayObject;
use Generated\Shared\DataBuilder\ServicePointBuilder;
use Generated\Shared\Transfer\MerchantProfileTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\PriceProductOfferTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer;
use Generated\Shared\Transfer\ProductOfferServiceTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ServicePointTransfer;
use Generated\Shared\Transfer\ServiceTransfer;
use Generated\Shared\Transfer\ServiceTypeTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StockTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use PyzTest\Glue\ProductOfferServicePointAvailabilities\ProductOfferServicePointAvailabilitiesApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group ProductOfferServicePointAvailabilities
 * @group RestApi
 * @group ProductOfferServicePointAvailabilitiesRestApiFixtures
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ProductOfferServicePointAvailabilitiesRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var string
     */
    protected const STORE_NAME_DE = 'DE';

    /**
     * @uses \Spryker\Zed\Merchant\MerchantConfig::STATUS_APPROVED
     *
     * @var string
     */
    protected const MERCHANT_STATUS_APPROVED = 'approved';

    /**
     * @var array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>
     */
    protected array $availableProductOfferServicePointAvailabilityResponseItemTransfers = [];

    /**
     * @var array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>
     */
    protected array $notAvailableProductOfferServicePointAvailabilityResponseItemTransfers = [];

    /**
     * @var array<string, \Generated\Shared\Transfer\ServiceTransfer>
     */
    protected array $serviceTransfers = [];

    /**
     * @var \Generated\Shared\Transfer\ServiceTypeTransfer
     */
    protected ServiceTypeTransfer $serviceTypeTransfer;

    /**
     * @var \Generated\Shared\Transfer\MerchantTransfer
     */
    protected MerchantTransfer $merchantTransfer;

    /**
     * @var \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    protected ShipmentTypeTransfer $shipmentTypeTransfer;

    /**
     * @param \PyzTest\Glue\ProductOfferServicePointAvailabilities\ProductOfferServicePointAvailabilitiesApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(ProductOfferServicePointAvailabilitiesApiTester $I): FixturesContainerInterface
    {
        $storeTransfer = $I->haveStore([StoreTransfer::NAME => static::STORE_NAME_DE]);

        $serviceTypeTransfer = $I->haveServiceType();

        $this->serviceTransfers = [
            $this->createServiceTransferWithServicePoint($I, $storeTransfer, $serviceTypeTransfer),
            $this->createServiceTransferWithServicePoint($I, $storeTransfer, $serviceTypeTransfer),
            $this->createServiceTransferWithServicePoint($I, $storeTransfer, $serviceTypeTransfer),
            $this->createServiceTransferWithServicePoint($I, $storeTransfer),
        ];

        $this->shipmentTypeTransfer = $I->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($storeTransfer),
        ]);

        $this->merchantTransfer = $I->haveMerchant([
            MerchantTransfer::IS_ACTIVE => true,
            MerchantTransfer::STATUS => static::MERCHANT_STATUS_APPROVED,
            MerchantTransfer::MERCHANT_PROFILE => new MerchantProfileTransfer(),
        ]);

        $this->availableProductOfferServicePointAvailabilityResponseItemTransfers = [
            $this->serviceTransfers[0]->getServicePointOrFail()->getUuidOrFail() => [
                $this->createProductOfferServicePointAvailabilityResponseItem($I, $storeTransfer, $this->serviceTransfers[0], $this->shipmentTypeTransfer, 10),
                $this->createProductOfferServicePointAvailabilityResponseItem($I, $storeTransfer, $this->serviceTransfers[0], null, 1),
            ],
            $this->serviceTransfers[1]->getServicePointOrFail()->getUuidOrFail() => [
                $this->createProductOfferServicePointAvailabilityResponseItem($I, $storeTransfer, $this->serviceTransfers[1], $this->shipmentTypeTransfer, 2),
            ],
        ];

        $this->notAvailableProductOfferServicePointAvailabilityResponseItemTransfers = [
            $this->serviceTransfers[0]->getServicePointOrFail()->getUuidOrFail() => [
                $this->createProductOfferServicePointAvailabilityResponseItem($I, $storeTransfer, $this->serviceTransfers[0], $this->shipmentTypeTransfer),
            ],
            $this->serviceTransfers[2]->getServicePointOrFail()->getUuidOrFail() => [
                $this->createProductOfferServicePointAvailabilityResponseItem($I, $storeTransfer, $this->serviceTransfers[0], $this->shipmentTypeTransfer),
            ],
        ];

        return $this;
    }

    /**
     * @return list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>
     */
    public function getAvailableProductOfferServicePointAvailabilityResponseItemTransfers(): array
    {
        return $this->availableProductOfferServicePointAvailabilityResponseItemTransfers;
    }

    /**
     * @return list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>
     */
    public function getNotAvailableProductOfferServicePointAvailabilityResponseItemTransfers(): array
    {
        return $this->notAvailableProductOfferServicePointAvailabilityResponseItemTransfers;
    }

    /**
     * @return list<\Generated\Shared\Transfer\ServiceTransfer>
     */
    public function getServiceTransfers(): array
    {
        return $this->serviceTransfers;
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantTransfer
     */
    public function getMerchantTransfer(): MerchantTransfer
    {
        return $this->merchantTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer
     */
    public function getShipmentTypeTransfer(): ShipmentTypeTransfer
    {
        return $this->shipmentTypeTransfer;
    }

    /**
     * @param \PyzTest\Glue\ProductOfferServicePointAvailabilities\ProductOfferServicePointAvailabilitiesApiTester $I
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ServiceTransfer $serviceTransfer
     * @param \Generated\Shared\Transfer\ShipmentTypeTransfer|null $shipmentTypeTransfer
     * @param int $availableQuantity
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer
     */
    protected function createProductOfferServicePointAvailabilityResponseItem(
        ProductOfferServicePointAvailabilitiesApiTester $I,
        StoreTransfer $storeTransfer,
        ServiceTransfer $serviceTransfer,
        ?ShipmentTypeTransfer $shipmentTypeTransfer = null,
        int $availableQuantity = 0,
    ): ProductOfferServicePointAvailabilityResponseItemTransfer {
        $productTransfer = $I->haveProduct([ProductConcreteTransfer::IS_ACTIVE => true]);

        $productOfferTransfer = $I->haveProductOffer([
            ProductOfferTransfer::CONCRETE_SKU => $productTransfer->getSkuOrFail(),
            ProductOfferTransfer::ID_PRODUCT_CONCRETE => $productTransfer->getIdProductConcreteOrFail(),
            ProductOfferTransfer::STORES => new ArrayObject([$storeTransfer]),
            ProductOfferTransfer::MERCHANT_REFERENCE => $this->merchantTransfer->getMerchantReferenceOrFail(),
        ]);

        $I->haveProductOfferService([
            ProductOfferServiceTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferServiceTransfer::ID_SERVICE => $serviceTransfer->getIdServiceOrFail(),
        ]);

        if ($shipmentTypeTransfer) {
            $I->haveProductOfferShipmentType($productOfferTransfer, $shipmentTypeTransfer);
        }

        $productOfferStockTransfer = $I->haveProductOfferStock([
            ProductOfferStockTransfer::ID_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            ProductOfferStockTransfer::QUANTITY => $availableQuantity,
            ProductOfferStockTransfer::STOCK => [
                StockTransfer::STORE_RELATION => [
                    StoreRelationTransfer::ID_STORES => [$storeTransfer->getIdStore()],
                ],
            ],
        ]);

        $I->updateStock($productOfferStockTransfer->getStock()->setIsActive(true));

        $I->havePriceProductOffer([
            PriceProductOfferTransfer::FK_PRODUCT_OFFER => $productOfferTransfer->getIdProductOfferOrFail(),
            PriceProductTransfer::SKU_PRODUCT => $productTransfer->getSkuOrFail(),
            PriceProductTransfer::SKU_PRODUCT_ABSTRACT => $productTransfer->getAbstractSkuOrFail(),
        ]);

        return (new ProductOfferServicePointAvailabilityResponseItemTransfer())
            ->setProductOfferReference($productOfferTransfer->getProductOfferReferenceOrFail())
            ->setProductConcreteSku($productTransfer->getSkuOrFail())
            ->setServicePointUuid($serviceTransfer->getServicePointOrFail()->getUuidOrFail())
            ->setAvailableQuantity($availableQuantity);
    }

    /**
     * @param \PyzTest\Glue\ProductOfferServicePointAvailabilities\ProductOfferServicePointAvailabilitiesApiTester $I
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param \Generated\Shared\Transfer\ServiceTypeTransfer|null $serviceTypeTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTransfer
     */
    protected function createServiceTransferWithServicePoint(
        ProductOfferServicePointAvailabilitiesApiTester $I,
        StoreTransfer $storeTransfer,
        ?ServiceTypeTransfer $serviceTypeTransfer = null,
    ): ServiceTransfer {
        $servicePointTransfer = (new ServicePointBuilder([ServicePointTransfer::IS_ACTIVE => true]))
            ->withStoreRelation([StoreRelationTransfer::STORES => [$storeTransfer->toArray()]])
            ->build();

        $servicePointTransfer = $I->haveServicePoint($servicePointTransfer->toArray());

        $serviceData = [
            ServiceTransfer::SERVICE_POINT => $servicePointTransfer->toArray(),
            ServiceTransfer::IS_ACTIVE => true,
        ];

        if ($serviceTypeTransfer) {
            $serviceData[ServiceTransfer::SERVICE_TYPE] = $serviceTypeTransfer->toArray();
        }

        return $I->haveService($serviceData);
    }
}
