<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\UpSellingProducts\RestApi;

use Codeception\Util\HttpCode;
use Pyz\Glue\ProductsRestApi\ProductsRestApiConfig;
use PyzTest\Glue\UpSellingProducts\UpSellingProductsApiTester;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group UpSellingProducts
 * @group RestApi
 * @group CartUpSellingProductsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class CartUpSellingProductsRestApiCest
{
    /**
     * @var \PyzTest\Glue\UpSellingProducts\RestApi\CartUpSellingProductsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\UpSellingProducts\UpSellingProductsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(UpSellingProductsApiTester $I): void
    {
        return;
        /** @var \PyzTest\Glue\UpSellingProducts\RestApi\CartUpSellingProductsRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(CartUpSellingProductsRestApiFixtures::class);

        $this->fixtures = $fixtures;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\UpSellingProducts\UpSellingProductsApiTester $I
     *
     * @return void
     */
    public function requestCartUpSellingProducts(UpSellingProductsApiTester $I): void
    {
        /**
        // Arrange
        $quoteTransfer = $this->fixtures->getQuoteTransfer();
        $productAbstractSku = $this->fixtures->getProductConcreteTransfer()->getAbstractSku();

        $oauthResponseTransfer = $I->haveAuthorizationToGlue($quoteTransfer->getCustomer());
        $I->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());

        // Act
        $I->sendGET($I->buildCartUpSellingProductsUrl($quoteTransfer->getUuid()));

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Response data contains resource collection')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType(ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS);

        $I->amSure('Resource collection has resource')
            ->whenI()
            ->seeResourceCollectionHasResourceWithId($this->fixtures->getUpSellingProductConcreteTransfer()->getAbstractSku());

        $I->amSure('Resource has correct self-link')
            ->whenI()
            ->seeResourceByIdHasSelfLink(
                $this->fixtures->getUpSellingProductConcreteTransfer()->getAbstractSku(),
                $I->buildProductAbstractUrl($this->fixtures->getUpSellingProductConcreteTransfer()->getAbstractSku())
            );
         */
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\UpSellingProducts\UpSellingProductsApiTester $I
     *
     * @return void
     */
    public function requestCartUpSellingProductsByNotExistingCartUuid(UpSellingProductsApiTester $I): void
    {
        /**
        // Arrange
        $oauthResponseTransfer = $I->haveAuthorizationToGlue($this->fixtures->getQuoteTransfer()->getCustomer());
        $I->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());

        // Act
        $I->sendGET($I->buildCartUpSellingProductsUrl('NotExistingUuid'));

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
        */
    }
}
