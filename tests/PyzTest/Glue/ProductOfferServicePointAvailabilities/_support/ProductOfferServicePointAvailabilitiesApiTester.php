<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\ProductOfferServicePointAvailabilities;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer;
use Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer;
use Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer;
use Generated\Shared\Transfer\RestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer;
use Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\ProductOfferServicePointAvailabilitiesRestApiConfig;
use SprykerTest\Glue\Testify\Tester\ApiEndToEndTester;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(\PyzTest\Glue\ProductOfferServicePointAvailabilities\PHPMD)
 */
class ProductOfferServicePointAvailabilitiesApiTester extends ApiEndToEndTester
{
    use _generated\ProductOfferServicePointAvailabilitiesApiTesterActions;

    /**
     * @return void
     */
    public function assertProductOfferServicePointAvailabilitiesResourceHasCorrectData(): void
    {
        $this->seeResponseDataContainsSingleResourceOfType(ProductOfferServicePointAvailabilitiesRestApiConfig::RESOURCE_PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES);

        $this->amSure('The returned resource id should be null')
            ->whenI()
            ->seeSingleResourceIdEqualTo('');

        $attributes = $this->getDataFromResponseByJsonPath('$.data.attributes');
        $this->assertNotEmpty($attributes[RestProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES]);
    }

    /**
     * @param array<string, mixed> $productOfferServicePointAvailabilityResponseItemData
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer $expectedProductOfferServicePointAvailabilityResponseItemTransfer
     * @param int $expectedAvailableQuantity
     * @param string $expectedIdentifier
     * @param bool $expectedIsAvailable
     *
     * @return void
     */
    public function assertProductOfferServicePointAvailabilityResponseItemHasCorrectData(
        array $productOfferServicePointAvailabilityResponseItemData,
        ProductOfferServicePointAvailabilityResponseItemTransfer $expectedProductOfferServicePointAvailabilityResponseItemTransfer,
        int $expectedAvailableQuantity,
        string $expectedIdentifier,
        bool $expectedIsAvailable,
    ): void {
        $this->assertSame(
            $expectedProductOfferServicePointAvailabilityResponseItemTransfer->getProductOfferReference(),
            $productOfferServicePointAvailabilityResponseItemData[RestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer::PRODUCT_OFFER_REFERENCE],
        );
        $this->assertSame(
            $expectedProductOfferServicePointAvailabilityResponseItemTransfer->getProductConcreteSku(),
            $productOfferServicePointAvailabilityResponseItemData[RestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer::PRODUCT_CONCRETE_SKU],
        );
        $this->assertSame(
            $expectedAvailableQuantity,
            $productOfferServicePointAvailabilityResponseItemData[RestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer::AVAILABLE_QUANTITY],
        );
        $this->assertSame(
            $expectedIdentifier,
            $productOfferServicePointAvailabilityResponseItemData[RestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer::IDENTIFIER],
        );
        $this->assertSame(
            $expectedIsAvailable,
            $productOfferServicePointAvailabilityResponseItemData[RestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer::IS_AVAILABLE],
        );
    }

    /**
     * @param string $servicePointUuid
     *
     * @return array<string, mixed>
     */
    public function getProductOfferServicePointAvailabilityByServicePointUuid(string $servicePointUuid): array
    {
        $productOfferServicePointAvailabilitiesData = $this->getDataFromResponseByJsonPath(sprintf(
            '$.data.attributes.%s[?(@.%s == "%s")]',
            RestProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITIES,
            RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::SERVICE_POINT_UUID,
            $servicePointUuid,
        ))[0] ?? [];

        return $this->sortProductOfferServicePointAvailabilitiesByIdentifier($productOfferServicePointAvailabilitiesData);
    }

    /**
     * @param array<string, mixed> $productOfferServicePointAvailabilitiesData
     *
     * @return array<string, mixed>
     */
    protected function sortProductOfferServicePointAvailabilitiesByIdentifier(array $productOfferServicePointAvailabilitiesData): array
    {
        usort($productOfferServicePointAvailabilitiesData[RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer::PRODUCT_OFFER_SERVICE_POINT_AVAILABILITY_RESPONSE_ITEMS], function ($availabilityResponse1, $availabilityResponse2) {
            return $availabilityResponse1[RestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer::IDENTIFIER] <=> $availabilityResponse2[RestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer::IDENTIFIER];
        });

        return $productOfferServicePointAvailabilitiesData;
    }
}
