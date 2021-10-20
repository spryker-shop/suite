<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\UpSellingProducts\RestApi;

use Codeception\Util\HttpCode;
use Pyz\Glue\ProductsRestApi\ProductsRestApiConfig;
use PyzTest\Glue\UpSellingProducts\UpSellingProductsApiTester;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group UpSellingProducts
 * @group RestApi
 * @group GuestCartUpSellingProductsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class GuestCartUpSellingProductsRestApiCest
{
    /**
     * @var \PyzTest\Glue\UpSellingProducts\RestApi\GuestCartUpSellingProductsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\UpSellingProducts\UpSellingProductsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(UpSellingProductsApiTester $I): void
    {
        /** @var \PyzTest\Glue\UpSellingProducts\RestApi\GuestCartUpSellingProductsRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(GuestCartUpSellingProductsRestApiFixtures::class);

        $this->fixtures = $fixtures;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\UpSellingProducts\UpSellingProductsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartUpSellingProducts(UpSellingProductsApiTester $I): void
    {
        // Arrange
        $productAbstractSku = $this->fixtures->getProductConcreteTransfer()->getAbstractSku();
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            $this->fixtures->getGuestCustomerReference(),
        );
        $url = $I->buildGuestCartUpSellingProductsUrl($this->fixtures->getGuestQuoteTransfer()->getUuid());

        // Act
        $I->sendGET($url);

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
                $I->buildProductAbstractUrl($this->fixtures->getUpSellingProductConcreteTransfer()->getAbstractSku()),
            );
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\UpSellingProducts\UpSellingProductsApiTester $I
     *
     * @return void
     */
    public function requestGuestCartUpSellingProductsByNotExistingCartUuid(UpSellingProductsApiTester $I): void
    {
        // Arrange
        $I->haveHttpHeader(
            CartsRestApiConfig::HEADER_ANONYMOUS_CUSTOMER_UNIQUE_ID,
            'NotExistingReference',
        );

        // Act
        $I->sendGET($I->buildGuestCartUpSellingProductsUrl('NotExistingUuid'));

        // Assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }
}
