<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\ProductOfferServicePointAvailabilities\RestApi;

use Codeception\Example;
use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer;
use Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer;
use Generated\Shared\Transfer\RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer;
use PyzTest\Glue\ProductOfferServicePointAvailabilities\ProductOfferServicePointAvailabilitiesApiTester;
use PyzTest\Glue\ProductOfferServicePointAvailabilities\RestApi\Fixtures\ProductOfferServicePointAvailabilitiesRestApiFixtures;
use Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\ProductOfferServicePointAvailabilitiesRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group ProductOfferServicePointAvailabilities
 * @group RestApi
 * @group ProductOfferServicePointAvailabilitiesRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ProductOfferServicePointAvailabilitiesRestApiCest
{
    /**
     * @var string
     */
    protected const TEST_PRODUCT_OFFER_REFERENCE = 'TEST_PRODUCT_OFFER_REFERENCE';

    /**
     * @var string
     */
    protected const TEST_PRODUCT_CONCRETE_SKU = 'TEST_PRODUCT_CONCRETE_SKU';

    /**
     * @var \PyzTest\Glue\ProductOfferServicePointAvailabilities\RestApi\Fixtures\ProductOfferServicePointAvailabilitiesRestApiFixtures
     */
    protected ProductOfferServicePointAvailabilitiesRestApiFixtures $fixtures;

    /**
     * @param \PyzTest\Glue\ProductOfferServicePointAvailabilities\ProductOfferServicePointAvailabilitiesApiTester $I
     *
     * @return void
     */
    public function loadFixtures(ProductOfferServicePointAvailabilitiesApiTester $I): void
    {
        /** @var \PyzTest\Glue\ProductOfferServicePointAvailabilities\RestApi\Fixtures\ProductOfferServicePointAvailabilitiesRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(ProductOfferServicePointAvailabilitiesRestApiFixtures::class);
        $this->fixtures = $fixtures;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\ProductOfferServicePointAvailabilities\ProductOfferServicePointAvailabilitiesApiTester $I
     *
     * @return void
     */
    public function requestGetProductOfferServicePointAvailabilitiesWithPartiallyAvailableItems(ProductOfferServicePointAvailabilitiesApiTester $I): void
    {
        // Arrange
        $serviceTransfer = $this->fixtures->getServiceTransfers()[0];
        $servicePointUuid = $serviceTransfer->getServicePointOrFail()->getUuidOrFail();

        [$productOfferAvailabilityResponseItemTransfer, $secondProductOfferAvailabilityResponseItemTransfer]
            = $this->fixtures->getAvailableProductOfferServicePointAvailabilityResponseItemTransfers()[$servicePointUuid];
        $notAvailableProductOfferAvailabilityResponseItemTransfer = $this->fixtures->getNotAvailableProductOfferServicePointAvailabilityResponseItemTransfers()[$servicePointUuid][0];

        $merchantTransfer = $this->fixtures->getMerchantTransfer();

        $requestData = $this->getRequestData([$servicePointUuid], $serviceTransfer->getServiceTypeOrFail()->getUuidOrFail());
        $requestData['data'][GlueRequestTransfer::ATTRIBUTES][RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_REQUEST_ITEMS] = [
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => $productOfferAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $productOfferAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => $productOfferAvailabilityResponseItemTransfer->getAvailableQuantity(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
            ],
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => $secondProductOfferAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $secondProductOfferAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => $secondProductOfferAvailabilityResponseItemTransfer->getAvailableQuantity() + 1,
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
            ],
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => $notAvailableProductOfferAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $notAvailableProductOfferAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => 1,
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
            ],
        ];

        // Act
        $I->sendPost(
            $I->formatUrl(ProductOfferServicePointAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES),
            $requestData,
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->assertProductOfferServicePointAvailabilitiesResourceHasCorrectData();

        $productOfferServicePointAvailability = $I->getProductOfferServicePointAvailabilityByServicePointUuid($servicePointUuid);
        $I->assertNotEmpty($productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS]);

        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][0],
            $productOfferAvailabilityResponseItemTransfer,
            $productOfferAvailabilityResponseItemTransfer->getAvailableQuantity(),
            '0',
            true,
        );
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][1],
            $secondProductOfferAvailabilityResponseItemTransfer,
            $secondProductOfferAvailabilityResponseItemTransfer->getAvailableQuantity(),
            '1',
            false,
        );
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][2],
            $notAvailableProductOfferAvailabilityResponseItemTransfer,
            0,
            '2',
            false,
        );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\ProductOfferServicePointAvailabilities\ProductOfferServicePointAvailabilitiesApiTester $I
     *
     * @return void
     */
    public function requestGetProductOfferServicePointAvailabilitiesWithoutMerchantReference(
        ProductOfferServicePointAvailabilitiesApiTester $I,
    ): void {
        // Arrange
        $serviceTransfer = $this->fixtures->getServiceTransfers()[0];
        $servicePointUuid = $serviceTransfer->getServicePointOrFail()->getUuidOrFail();

        [$productOfferAvailabilityResponseItemTransfer, $secondProductOfferAvailabilityResponseItemTransfer]
            = $this->fixtures->getAvailableProductOfferServicePointAvailabilityResponseItemTransfers()[$servicePointUuid];
        $notAvailableProductOfferAvailabilityResponseItemTransfer = $this->fixtures->getNotAvailableProductOfferServicePointAvailabilityResponseItemTransfers()[$servicePointUuid][0];

        $requestData = $this->getRequestData([$servicePointUuid], $serviceTransfer->getServiceTypeOrFail()->getUuidOrFail());
        $requestData['data'][GlueRequestTransfer::ATTRIBUTES][RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_REQUEST_ITEMS] = [
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => $productOfferAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $productOfferAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => $productOfferAvailabilityResponseItemTransfer->getAvailableQuantity(),
            ],
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => $secondProductOfferAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $secondProductOfferAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => $secondProductOfferAvailabilityResponseItemTransfer->getAvailableQuantity() + 1,
            ],
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => $notAvailableProductOfferAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $notAvailableProductOfferAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => 1,
            ],
        ];

        // Act
        $I->sendPost(
            $I->formatUrl(ProductOfferServicePointAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES),
            $requestData,
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->assertProductOfferServicePointAvailabilitiesResourceHasCorrectData();

        $productOfferServicePointAvailability = $I->getProductOfferServicePointAvailabilityByServicePointUuid($servicePointUuid);
        $I->assertNotEmpty($productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS]);

        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][0],
            $productOfferAvailabilityResponseItemTransfer,
            0,
            '0',
            false,
        );
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][1],
            $secondProductOfferAvailabilityResponseItemTransfer,
            0,
            '1',
            false,
        );
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][2],
            $notAvailableProductOfferAvailabilityResponseItemTransfer,
            0,
            '2',
            false,
        );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\ProductOfferServicePointAvailabilities\ProductOfferServicePointAvailabilitiesApiTester $I
     *
     * @return void
     */
    public function requestGetProductOfferServicePointAvailabilitiesWithShipmentType(
        ProductOfferServicePointAvailabilitiesApiTester $I,
    ): void {
        // Arrange
        $serviceTransfer = $this->fixtures->getServiceTransfers()[0];
        $servicePointUuid = $serviceTransfer->getServicePointOrFail()->getUuidOrFail();

        [$productOfferAvailabilityResponseItemTransfer, $productOfferAvailabilityResponseItemTransferWithoutShipmentType]
            = $this->fixtures->getAvailableProductOfferServicePointAvailabilityResponseItemTransfers()[$servicePointUuid];

        $merchantTransfer = $this->fixtures->getMerchantTransfer();

        $requestData = $this->getRequestData([$servicePointUuid], $serviceTransfer->getServiceTypeOrFail()->getUuidOrFail());
        $requestData['data'][GlueRequestTransfer::ATTRIBUTES][RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_REQUEST_ITEMS] = [
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => $productOfferAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $productOfferAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => $productOfferAvailabilityResponseItemTransfer->getAvailableQuantity(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
            ],
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => $productOfferAvailabilityResponseItemTransferWithoutShipmentType->getProductOfferReferenceOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $productOfferAvailabilityResponseItemTransferWithoutShipmentType->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => $productOfferAvailabilityResponseItemTransferWithoutShipmentType->getAvailableQuantity(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
            ],
        ];
        $requestData['data'][GlueRequestTransfer::ATTRIBUTES][RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::SHIPMENT_TYPE_UUID] = $this->fixtures->getShipmentTypeTransfer()->getUuidOrFail();

        // Act
        $I->sendPost(
            $I->formatUrl(ProductOfferServicePointAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES),
            $requestData,
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->assertProductOfferServicePointAvailabilitiesResourceHasCorrectData();

        $productOfferServicePointAvailability = $I->getProductOfferServicePointAvailabilityByServicePointUuid($servicePointUuid);
        $I->assertNotEmpty($productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS]);

        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][0],
            $productOfferAvailabilityResponseItemTransfer,
            $productOfferAvailabilityResponseItemTransfer->getAvailableQuantity(),
            '0',
            true,
        );
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][1],
            $productOfferAvailabilityResponseItemTransferWithoutShipmentType,
            0,
            '1',
            false,
        );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\ProductOfferServicePointAvailabilities\ProductOfferServicePointAvailabilitiesApiTester $I
     *
     * @return void
     */
    public function requestGetProductOfferServicePointAvailabilitiesWithIncorrectServiceType(
        ProductOfferServicePointAvailabilitiesApiTester $I,
    ): void {
        // Arrange
        $servicePointUuid = $this->fixtures->getServiceTransfers()[0]->getServicePointOrFail()->getUuidOrFail();

        [$productOfferAvailabilityResponseItemTransfer, $secondProductOfferAvailabilityResponseItemTransfer]
            = $this->fixtures->getAvailableProductOfferServicePointAvailabilityResponseItemTransfers()[$servicePointUuid];

        $merchantTransfer = $this->fixtures->getMerchantTransfer();

        $requestData = $this->getRequestData([$servicePointUuid], $this->fixtures->getServiceTransfers()[3]->getServiceTypeOrFail()->getUuidOrFail());

        $requestData['data'][GlueRequestTransfer::ATTRIBUTES][RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_REQUEST_ITEMS] = [
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => $productOfferAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $productOfferAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => $productOfferAvailabilityResponseItemTransfer->getAvailableQuantity(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
            ],
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => $secondProductOfferAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $secondProductOfferAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => $secondProductOfferAvailabilityResponseItemTransfer->getAvailableQuantity(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
            ],
        ];

        // Act
        $I->sendPost(
            $I->formatUrl(ProductOfferServicePointAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES),
            $requestData,
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->assertProductOfferServicePointAvailabilitiesResourceHasCorrectData();

        $productOfferServicePointAvailability = $I->getProductOfferServicePointAvailabilityByServicePointUuid($servicePointUuid);
        $I->assertNotEmpty($productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS]);

        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][0],
            $productOfferAvailabilityResponseItemTransfer,
            0,
            '0',
            false,
        );
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][1],
            $secondProductOfferAvailabilityResponseItemTransfer,
            0,
            '1',
            false,
        );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\ProductOfferServicePointAvailabilities\ProductOfferServicePointAvailabilitiesApiTester $I
     *
     * @return void
     */
    public function requestGetProductOfferServicePointAvailabilitiesWithSeveralServicePointsShouldReturnItemsWithAvailabilityForEachServicePoint(
        ProductOfferServicePointAvailabilitiesApiTester $I,
    ): void {
        // Arrange
        $servicePointUuids = [
            $this->fixtures->getServiceTransfers()[0]->getServicePointOrFail()->getUuidOrFail(),
            $this->fixtures->getServiceTransfers()[1]->getServicePointOrFail()->getUuidOrFail(),
            $this->fixtures->getServiceTransfers()[2]->getServicePointOrFail()->getUuidOrFail(),
        ];

        $productOfferAvailabilityResponseItemTransfer = $this->fixtures->getAvailableProductOfferServicePointAvailabilityResponseItemTransfers()[$servicePointUuids[0]][0];
        $secondProductOfferAvailabilityResponseItemTransfer = $this->fixtures->getAvailableProductOfferServicePointAvailabilityResponseItemTransfers()[$servicePointUuids[1]][0];
        $thirdProductOfferAvailabilityResponseItemTransfer = $this->fixtures->getNotAvailableProductOfferServicePointAvailabilityResponseItemTransfers()[$servicePointUuids[2]][0];

        $merchantTransfer = $this->fixtures->getMerchantTransfer();

        $requestData = $this->getRequestData($servicePointUuids, $this->fixtures->getServiceTransfers()[0]->getServiceTypeOrFail()->getUuidOrFail());
        $requestData['data'][GlueRequestTransfer::ATTRIBUTES][RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_REQUEST_ITEMS] = [
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => $productOfferAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $productOfferAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => $productOfferAvailabilityResponseItemTransfer->getAvailableQuantity(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
            ],
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => $secondProductOfferAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $secondProductOfferAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => $secondProductOfferAvailabilityResponseItemTransfer->getAvailableQuantity(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
            ],
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => $thirdProductOfferAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $thirdProductOfferAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => 1,
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
            ],
        ];

        // Act
        $I->sendPost(
            $I->formatUrl(ProductOfferServicePointAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES),
            $requestData,
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->assertProductOfferServicePointAvailabilitiesResourceHasCorrectData();

        $productOfferServicePointAvailability = $I->getProductOfferServicePointAvailabilityByServicePointUuid($servicePointUuids[0]);

        $I->assertCount(3, $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS]);
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][0],
            $productOfferAvailabilityResponseItemTransfer,
            $productOfferAvailabilityResponseItemTransfer->getAvailableQuantity(),
            '0',
            true,
        );
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][1],
            $secondProductOfferAvailabilityResponseItemTransfer,
            0,
            '1',
            false,
        );
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][2],
            $thirdProductOfferAvailabilityResponseItemTransfer,
            0,
            '2',
            false,
        );

        $secondProductOfferServicePointAvailability = $I->getProductOfferServicePointAvailabilityByServicePointUuid($servicePointUuids[1]);
        $I->assertCount(3, $secondProductOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS]);
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $secondProductOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][0],
            $productOfferAvailabilityResponseItemTransfer,
            0,
            '0',
            false,
        );
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $secondProductOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][1],
            $secondProductOfferAvailabilityResponseItemTransfer,
            $secondProductOfferAvailabilityResponseItemTransfer->getAvailableQuantity(),
            '1',
            true,
        );
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $secondProductOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][2],
            $thirdProductOfferAvailabilityResponseItemTransfer,
            0,
            '2',
            false,
        );

        $thirdProductOfferServicePointAvailability = $I->getProductOfferServicePointAvailabilityByServicePointUuid($servicePointUuids[2]);
        $I->assertCount(3, $secondProductOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS]);
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $thirdProductOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][0],
            $productOfferAvailabilityResponseItemTransfer,
            0,
            '0',
            false,
        );
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $thirdProductOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][1],
            $secondProductOfferAvailabilityResponseItemTransfer,
            0,
            '1',
            false,
        );
        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $thirdProductOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][2],
            $thirdProductOfferAvailabilityResponseItemTransfer,
            0,
            '2',
            false,
        );
    }

    /**
     * @depends loadFixtures
     *
     * @dataProvider getValidationErrorsDataProvider
     *
     * @param \PyzTest\Glue\ProductOfferServicePointAvailabilities\ProductOfferServicePointAvailabilitiesApiTester $I
     * @param \Codeception\Example $example
     *
     * @return void
     */
    public function requestGetProductOfferServicePointAvailabilitiesWithInvalidRequestData(
        ProductOfferServicePointAvailabilitiesApiTester $I,
        Example $example,
    ): void {
        // Arrange
        $serviceTransfer = $this->fixtures->getServiceTransfers()[0];
        $servicePointUuid = $serviceTransfer->getServicePointOrFail()->getUuidOrFail();
        $productOfferServicePointAvailabilityResponseItemTransfer = $this->fixtures->getAvailableProductOfferServicePointAvailabilityResponseItemTransfers()[$servicePointUuid][0];

        $requestData = $this->getRequestData([$servicePointUuid], $serviceTransfer->getServiceTypeOrFail()->getUuidOrFail());
        $requestData['data'][GlueRequestTransfer::ATTRIBUTES][RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_REQUEST_ITEMS] = [
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => $productOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReferenceOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $productOfferServicePointAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => $productOfferServicePointAvailabilityResponseItemTransfer->getAvailableQuantity(),
            ],
        ];
        $requestData['data'][GlueRequestTransfer::ATTRIBUTES] = array_merge($requestData['data'][GlueRequestTransfer::ATTRIBUTES], $example[GlueRequestTransfer::ATTRIBUTES]);

        // Act
        $I->sendPost(
            $I->formatUrl(ProductOfferServicePointAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES),
            $requestData,
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\ProductOfferServicePointAvailabilities\ProductOfferServicePointAvailabilitiesApiTester $I
     *
     * @return void
     */
    public function requestGetProductOfferServicePointAvailabilitiesWithoutProductOfferReference(ProductOfferServicePointAvailabilitiesApiTester $I): void
    {
        // Arrange
        $serviceTransfer = $this->fixtures->getServiceTransfers()[0];
        $servicePointUuid = $serviceTransfer->getServicePointOrFail()->getUuidOrFail();

        $productOfferAvailabilityResponseItemTransfer = $this->fixtures->getAvailableProductOfferServicePointAvailabilityResponseItemTransfers()[$servicePointUuid][0];
        $merchantTransfer = $this->fixtures->getMerchantTransfer();

        $requestData = $this->getRequestData([$servicePointUuid], $serviceTransfer->getServiceTypeOrFail()->getUuidOrFail());
        $requestData['data'][GlueRequestTransfer::ATTRIBUTES][RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_REQUEST_ITEMS] = [
            [
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => null,
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => $productOfferAvailabilityResponseItemTransfer->getProductConcreteSkuOrFail(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => $productOfferAvailabilityResponseItemTransfer->getAvailableQuantity(),
                RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
            ],
        ];

        // Act
        $I->sendPost(
            $I->formatUrl(ProductOfferServicePointAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES),
            $requestData,
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->assertProductOfferServicePointAvailabilitiesResourceHasCorrectData();

        $productOfferServicePointAvailability = $I->getProductOfferServicePointAvailabilityByServicePointUuid($servicePointUuid);
        $I->assertNotEmpty($productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS]);

        $I->assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
            $productOfferServicePointAvailability[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS][0],
            $productOfferAvailabilityResponseItemTransfer,
            $productOfferAvailabilityResponseItemTransfer->getAvailableQuantity(),
            '0',
            true,
        );
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function getValidationErrorsDataProvider(): array
    {
        return [
            'Should return error when service point uuids are not provided' => [
                GlueRequestTransfer::ATTRIBUTES => [RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::SERVICE_POINT_UUIDS => []],
            ],
            'Should return error when service type uuid is not provided' => [
                GlueRequestTransfer::ATTRIBUTES => [RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::SERVICE_TYPE_UUID => null],
            ],
            'Should return error when product concrete sku is not provided' => [
                GlueRequestTransfer::ATTRIBUTES => [
            RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_REQUEST_ITEMS => [
                    RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => static::TEST_PRODUCT_OFFER_REFERENCE,
                    RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => 1,
                ]],
            ],
            'Should return error when quantity is not provided' => [
                GlueRequestTransfer::ATTRIBUTES => [
            RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_REQUEST_ITEMS => [
                    RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => static::TEST_PRODUCT_OFFER_REFERENCE,
                    RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => static::TEST_PRODUCT_CONCRETE_SKU,
                ]],
            ],
            'Should return error when quantity is less then 0' => [
                GlueRequestTransfer::ATTRIBUTES => [
            RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_REQUEST_ITEMS => [
                    RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE => static::TEST_PRODUCT_OFFER_REFERENCE,
                    RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::PRODUCT_CONCRETE_SKU => static::TEST_PRODUCT_CONCRETE_SKU,
                    RestProductOfferServicePointAvailabilityRequestItemsAttributesTransfer::QUANTITY => -1,
                ]],
            ],
            'Should return error when empty shipment type is provided' => [
                GlueRequestTransfer::ATTRIBUTES => [RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::SHIPMENT_TYPE_UUID => null],
            ],
        ];
    }

    /**
     * @param list<string> $servicePointUuids
     * @param string $serviceTypeUuid
     *
     * @return array<string, mixed>
     */
    protected function getRequestData(array $servicePointUuids, string $serviceTypeUuid): array
    {
        return [
            'data' => [
                'type' => ProductOfferServicePointAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES,
                GlueRequestTransfer::ATTRIBUTES => [
                    RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::SERVICE_POINT_UUIDS => $servicePointUuids,
                    RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::SERVICE_TYPE_UUID => $serviceTypeUuid,
                    RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_REQUEST_ITEMS => [],
                ],
            ],
        ];
    }
}
