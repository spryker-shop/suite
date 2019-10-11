<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Codeception\Util\HttpCode;
use Pyz\Glue\ProductsRestApi\ProductsRestApiConfig;
use PyzTest\Glue\Carts\CartsApiTester;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;
use Spryker\Glue\UpSellingProductsRestApi\UpSellingProductsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group GuestCartsUpSellingProductsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class GuestCartsUpSellingProductsRestApiCest
{
    /**
     * @var \PyzTest\Glue\Carts\RestApi\CartsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CartsApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(CartsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestExistingGuestCartItemsWithProductLabelRelationship(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getGuestQuoteTransfer();
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransferWithLabel();
        $productLabelTransfer = $this->fixtures->getProductLabelTransfer();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getValueForGuestCustomerReference()
        );

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceGuestCarts}/{cartUuid}/{relationshipUpSellingProducts}?include={relationshipConcreteProducts},{relationshipProductLabels}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'relationshipUpSellingProducts' => UpSellingProductsRestApiConfig::RELATIONSHIP_NAME_UP_SELLING_PRODUCTS,
                    'relationshipConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'cartUuid' => $quoteTransfer->getUuid(),
                ]
            )
        );

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSure('Returned resource has include of type concrete-products')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteTransfer->getSku()
            );

        $I->amSure('Returned resource has include of type product-labels')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                $productLabelTransfer->getIdProductLabel()
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestExistingGuestCartItemsWithoutProductLabelRelationship(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getGuestQuoteTransferWithLabel();
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransfer();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getValueForGuestCustomerReferenceWithLabel()
        );

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceGuestCarts}/{cartUuid}/{relationshipUpSellingProducts}?include={relationshipConcreteProducts},{relationshipProductLabels}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'relationshipUpSellingProducts' => UpSellingProductsRestApiConfig::RELATIONSHIP_NAME_UP_SELLING_PRODUCTS,
                    'relationshipConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'cartUuid' => $quoteTransfer->getUuid(),
                ]
            )
        );

        // Assert
        $I->assertResponse(HttpCode::OK);

        $I->amSure('Returned resource has include of type concrete-products')
            ->whenI()
            ->seeIncludesContainsResourceByTypeAndId(
                ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                $productConcreteTransfer->getSku()
            );

        $I->dontSeeResponseContains('"' . ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS . '"');
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestNotExistingCartItemsWithProductLabelRelationship(CartsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getValueForGuestCustomerReferenceWithLabelWithEmptyCart()
        );

        // Act
        $I->sendGET(
            $I->formatUrl(
                '{resourceGuestCarts}/{cartUuid}/{relationshipUpSellingProducts}?include={relationshipConcreteProducts},{relationshipProductLabels}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'relationshipUpSellingProducts' => UpSellingProductsRestApiConfig::RELATIONSHIP_NAME_UP_SELLING_PRODUCTS,
                    'relationshipConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'cartUuid' => 'NotExistingUuid',
                ]
            )
        );

        // Assert
        $I->assertResponse(HttpCode::NOT_FOUND);

        $I->dontSeeResponseMatchesJsonPath('$.data[*]');
        $I->dontSeeResponseMatchesJsonPath('$.included[*]');
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestExistingCartItemsWithProductLabelRelationshipByPost(CartsApiTester $I): void
    {
        // Arrange
        $quoteTransfer = $this->fixtures->getGuestQuoteTransfer();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getValueForGuestCustomerReference()
        );

        // Act
        $I->sendPOST(
            $I->formatUrl(
                '{resourceGuestCarts}/{cartUuid}/{relationshipUpSellingProducts}?include={relationshipConcreteProducts},{relationshipProductLabels}',
                [
                    'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                    'relationshipUpSellingProducts' => UpSellingProductsRestApiConfig::RELATIONSHIP_NAME_UP_SELLING_PRODUCTS,
                    'relationshipConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'cartUuid' => $quoteTransfer->getUuid(),
                ]
            )
        );

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
