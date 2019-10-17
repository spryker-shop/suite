<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use Pyz\Glue\ProductsRestApi\ProductsRestApiConfig;
use PyzTest\Glue\Carts\CartsApiTester;
use PyzTest\Glue\Carts\RestApi\Fixtures\GuestCartsRestApiFixtures;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group GuestCartsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class GuestCartsRestApiCest
{
    protected const INCLUDE_RESOURCES = [
        CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
        ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
        ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
    ];

    /**
     * @var \PyzTest\Glue\Carts\RestApi\Fixtures\GuestCartsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CartsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(GuestCartsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartByUuidWithItemsWithProductLabelRelationship(CartsApiTester $I): void
    {
        // Arrange
        $quoteUuid = $this->fixtures->getGuestQuoteTransferWithLabel()->getUuid();
        $productConcreteSku = $this->fixtures->getProductConcreteTransferWithLabel()->getSku();
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getValueForGuestCustomerReferenceWithLabel()
        );
        $url = $I->buildGuestCartUrl($quoteUuid, static::INCLUDE_RESOURCES);

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_GUEST_CARTS)
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CartsRestApiConfig::RESOURCE_GUEST_CARTS);

        $I->amSureSingleResourceIdEqualTo()
            ->whenI()
            ->seeSingleResourceIdEqualTo($quoteUuid);

        $I->amSureSingleResourceHasSelfLink()
            ->whenI()
            ->seeSingleResourceHasSelfLink($url);

        $I->amSureSingleResourceHasRelationshipByTypeAndId(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            $productConcreteSku
        )
            ->whenI()
            ->seeSingleResourceHasRelationshipByTypeAndId(
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                $productConcreteSku
            );

        $I->amSureIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            $productConcreteSku,
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                $productConcreteSku,
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku
            );

        $I->amSureIncludesContainsResourceByTypeAndId(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku
            );

        $I->amSureIncludedResourceByTypeAndIdHasSelfLink(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasSelfLink(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku,
                $I->buildProductConcreteUrl($productConcreteSku)
            );

        $I->amSureIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku,
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $idProductLabel
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel
            );

        $I->amSureIncludesContainsResourceByTypeAndId(
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $idProductLabel
        )
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel
            );

        $I->amSureIncludedResourceByTypeAndIdHasSelfLink(
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $idProductLabel
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasSelfLink(
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel,
                $I->buildProductLabelUrl($idProductLabel)
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartWithItemsWithProductLabelRelationship(CartsApiTester $I): void
    {
        // Arrange
        $quoteUuid = $this->fixtures->getGuestQuoteTransferWithLabel()->getUuid();
        $productConcreteSku = $this->fixtures->getProductConcreteTransferWithLabel()->getSku();
        $idProductLabel = $this->fixtures->getProductLabelTransfer()->getIdProductLabel();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getValueForGuestCustomerReferenceWithLabel()
        );
        $url = $I->buildGuestCartsUrl(static::INCLUDE_RESOURCES);

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSureResponseDataContainsResourceCollectionOfType(CartsRestApiConfig::RESOURCE_GUEST_CARTS)
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType(CartsRestApiConfig::RESOURCE_GUEST_CARTS);

        $I->amSureResourceCollectionHasResourceWithId($quoteUuid)
            ->whenI()
            ->seeResourceCollectionHasResourceWithId($quoteUuid);

        $I->amSureResourceByIdHasSelfLink($quoteUuid)
            ->whenI()
            ->seeResourceByIdHasSelfLink($quoteUuid, $I->buildGuestCartUrl($quoteUuid, static::INCLUDE_RESOURCES));

        $I->amSureResourceByIdHasRelationshipByTypeAndId(
            $quoteUuid,
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            $productConcreteSku
        )
            ->whenI()
            ->seeResourceByIdHasRelationshipByTypeAndId(
                $quoteUuid,
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                $productConcreteSku
            );

        $I->amSureIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            $productConcreteSku,
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
                CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
                $productConcreteSku,
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku
            );

        $I->amSureIncludesContainsResourceByTypeAndId(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku
            );

        $I->amSureIncludedResourceByTypeAndIdHasSelfLink(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasSelfLink(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku,
                $I->buildProductConcreteUrl($productConcreteSku)
            );

        $I->amSureIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            $productConcreteSku,
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $idProductLabel
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasRelationshipByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteSku,
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel
            );

        $I->amSureIncludesContainsResourceByTypeAndId(
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $idProductLabel
        )
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel
            );

        $I->amSureIncludedResourceByTypeAndIdHasSelfLink(
            ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
            $idProductLabel
        )
            ->whenI()
            ->seeIncludedResourceByTypeAndIdHasSelfLink(
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $idProductLabel,
                $I->buildProductLabelUrl($idProductLabel)
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartByUuidWithItemsWithoutProductLabelRelationship(CartsApiTester $I): void
    {
        // Arrange
        $quoteUuid = $this->fixtures->getGuestQuoteTransfer()->getUuid();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getValueForGuestCustomerReference()
        );
        $url = $I->buildGuestCartUrl($quoteUuid, static::INCLUDE_RESOURCES);

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::OK);
        $I->dontSeeResponseMatchesJsonPath(
            sprintf('$.included[?(@.type == %s$s)]', ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS)
        );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartByNotExistingGuestCartUuid(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getValueForGuestCustomerReferenceWithLabelWithEmptyCart()
        );
        $url = $I->buildGuestCartUrl('NotExistingUuid');

        // Act
        $I->sendGET($url);

        // Assert
        $I->assertResponse(HttpCode::NOT_FOUND);
        $I->seeResponseDataContainsEmptyCollection();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartByUuidWithItemsWithProductLabelRelationshipByPost(CartsApiTester $I): void
    {
        // Arrange
        $quoteUuid = $this->fixtures->getGuestQuoteTransfer()->getUuid();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getValueForGuestCustomerReferenceWithLabel()
        );
        $url = $I->buildGuestCartUrl($quoteUuid, static::INCLUDE_RESOURCES);

        // Act
        $I->sendPOST($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartByUuidWithItemsWithProductLabelRelationshipByPatch(CartsApiTester $I): void
    {
        // Arrange
        $quoteUuid = $this->fixtures->getGuestQuoteTransfer()->getUuid();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getValueForGuestCustomerReferenceWithLabel()
        );
        $url = $I->buildGuestCartUrl($quoteUuid, static::INCLUDE_RESOURCES);

        // Act
        $I->sendPATCH($url);

        // Assert
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseIsJson();
    }
}
