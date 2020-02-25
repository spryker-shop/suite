<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\SalesReturns\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\SalesReturns\RestApi\Fixtures\ReturnReasonsRestApiFixtures;
use PyzTest\Glue\SalesReturns\SalesReturnsApiTester;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group SalesReturns
 * @group RestApi
 * @group ReturnReasonsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class ReturnReasonsRestApiCest
{
    /**
     * @var \PyzTest\Glue\SalesReturns\RestApi\Fixtures\ReturnReasonsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\SalesReturns\SalesReturnsApiTester $I
     *
     * @return void
     */
    public function loadFixtures(SalesReturnsApiTester $I): void
    {
        /** @var \PyzTest\Glue\SalesReturns\RestApi\Fixtures\ReturnReasonsRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(ReturnReasonsRestApiFixtures::class);

        $this->fixtures = $fixtures;
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\SalesReturns\SalesReturnsApiTester $I
     *
     * @return void
     */
    public function requestReturnReasons(SalesReturnsApiTester $I): void
    {
        // Arrange

        // Act
        $I->sendGET($I->buildGuestReturnReasonsUrl());

        // Assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Response data contains resource collection')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfType(SalesReturnsRestApiConfig::RESOURCE_RETURN_REASONS);

        $I->amSure('Resource collection has resource')
            ->whenI()
            ->seeResourceCollectionHasResourceWithId(null);

        $I->amSure('Resource has correct self-link')
            ->whenI()
            ->seeResourceByIdHasSelfLink(null, $I->buildGuestReturnReasonsUrl());
    }
}
