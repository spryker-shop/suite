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
 * @group CartsUpSellingProductsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CartsUpSellingProductsRestApiCest
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
    protected function requestCustomerLogin(CartsApiTester $I): void
    {
        $token = $I
            ->haveAuth($this->fixtures->getCustomerTransfer())
            ->getAccessToken();
        $I->amBearerAuthenticated($token);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    public function requestExistingUpSellingProductsWithProductLabelRelationship(CartsApiTester $I): void
    {
        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransferWithLabel();
        $productLabelTransfer = $this->fixtures->getProductLabelTransfer();

        //Act
        $this->requestCustomerLogin($I);
        $I->sendGET(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{relationshipUpSellingProducts}?include={relationshipConcreteProducts},{relationshipProductLabels}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'relationshipUpSellingProducts' => UpSellingProductsRestApiConfig::RELATIONSHIP_NAME_UP_SELLING_PRODUCTS,
                    'relationshipConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'cartUuid' => $quoteTransfer->getUuid(),
                ]
            )
        );

        //Assert
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
    public function requestExistingCartItemsWithoutProductLabelRelationship(CartsApiTester $I): void
    {
        $quoteTransfer = $this->fixtures->getQuoteTransferWithLabel();
        $productConcreteTransfer = $this->fixtures->getProductConcreteTransfer();

        //Act
        $this->requestCustomerLogin($I);
        $I->sendGET(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{relationshipUpSellingProducts}?include={relationshipConcreteProducts},{relationshipProductLabels}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'relationshipUpSellingProducts' => UpSellingProductsRestApiConfig::RELATIONSHIP_NAME_UP_SELLING_PRODUCTS,
                    'relationshipConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'cartUuid' => $quoteTransfer->getUuid(),
                ]
            )
        );

        //Assert
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
        //Act
        $this->requestCustomerLogin($I);
        $I->sendGET(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{relationshipUpSellingProducts}?include={relationshipConcreteProducts},{relationshipProductLabels}',
                [
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'relationshipUpSellingProducts' => UpSellingProductsRestApiConfig::RELATIONSHIP_NAME_UP_SELLING_PRODUCTS,
                    'relationshipConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                    'cartUuid' => 'NotExistingUuid',
                ]
            )
        );

        //Assert
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
        $quoteTransfer = $this->fixtures->getQuoteTransferWithLabel();

        //Act
        $this->requestCustomerLogin($I);
        $I->sendPOST(
            $I->formatUrl(
                '{resourceCarts}/{cartUuid}/{relationshipUpSellingProducts}?include={relationshipConcreteProducts},{relationshipProductLabels}',
                [
                    'cartUuid' => $quoteTransfer->getUuid(),
                    'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                    'relationshipUpSellingProducts' => UpSellingProductsRestApiConfig::RELATIONSHIP_NAME_UP_SELLING_PRODUCTS,
                    'relationshipConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                    'relationshipProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                ]
            )
        );

        //Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
