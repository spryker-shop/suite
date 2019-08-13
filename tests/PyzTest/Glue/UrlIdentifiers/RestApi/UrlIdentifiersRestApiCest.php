<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\UrlIdentifiers\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\UrlIdentifiers\UrlIdentifiersRestApiTester;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group UrlIdentifiers
 * @group RestApi
 * @group UrlIdentifiersRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class UrlIdentifiersRestApiCest
{
    /**
     * @var \PyzTest\Glue\UrlIdentifiers\RestApi\UrlIdentifiersRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\UrlIdentifiers\UrlIdentifiersRestApiTester $I
     *
     * @return void
     */
    public function loadFixtures(UrlIdentifiersRestApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(UrlIdentifiersRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\UrlIdentifiers\UrlIdentifiersRestApiTester $I
     *
     * @return void
     */
    public function requestNonExistingUrl(UrlIdentifiersRestApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'url-identifiers?url={url}',
                [
                    'url' => 'none',
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\UrlIdentifiers\UrlIdentifiersRestApiTester $I
     *
     * @return void
     */
    public function requestUrlWithoutUrlParameter(UrlIdentifiersRestApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'url-identifiers'
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\UrlIdentifiers\UrlIdentifiersRestApiTester $I
     *
     * @return void
     */
    public function requestExistingProductAbstractUrl(UrlIdentifiersRestApiTester $I): void
    {
        $currentLocale = $I->getLocator()->locale()->client()->getCurrentLocale();
        $localizedUrl = '';
        foreach ($this->fixtures->getProductUrlTransfer()->getUrls() as $localizedUrlTransfer) {
            if ($localizedUrlTransfer->getLocale()->getLocaleName() === $currentLocale) {
                $localizedUrl = $localizedUrlTransfer->getUrl();
            }
        }

        //act
        $I->sendGET(
            $I->formatUrl(
                'url-identifiers?url={url}',
                [
                    'url' => $localizedUrl,
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource collection consists of 1 item of type url-identifiers')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfTypeWithSizeOf('url-identifiers', 1);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\UrlIdentifiers\UrlIdentifiersRestApiTester $I
     *
     * @return void
     */
    public function requestExistingCategoryUrl(UrlIdentifiersRestApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'url-identifiers?url={url}',
                [
                    'url' => $this->fixtures->getCategoryUrlTransfer()->getUrl(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource collection consists of 1 item of type url-identifiers')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfTypeWithSizeOf('url-identifiers', 1);
    }
}
