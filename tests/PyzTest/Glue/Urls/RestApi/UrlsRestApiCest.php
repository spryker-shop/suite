<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Urls\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\Urls\UrlsRestApiTester;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group Urls
 * @group RestApi
 * @group UrlsRestApiCest
 * Add your own group annotations below this line
 * @group EndToEnd
 */
class UrlsRestApiCest
{
    /**
     * @var \PyzTest\Glue\Urls\RestApi\UrlsRestApiFixtures
     */
    protected $fixtures;

    /**
     * @param \PyzTest\Glue\Urls\UrlsRestApiTester $I
     *
     * @return void
     */
    public function loadFixtures(UrlsRestApiTester $I): void
    {
        $this->fixtures = $I->loadFixtures(UrlsRestApiFixtures::class);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Urls\UrlsRestApiTester $I
     *
     * @return void
     */
    public function requestNonExistingUrl(UrlsRestApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'urls?url={url}',
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
     * @param \PyzTest\Glue\UrlIdentifiers\UrlsRestApiTester $I
     *
     * @return void
     */
    public function requestUrlWithoutUrlParameter(UrlsRestApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'urls'
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Urls\UrlsRestApiTester $I
     *
     * @return void
     */
    public function requestExistingProductAbstractUrl(UrlsRestApiTester $I): void
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
                'urls?url={url}',
                [
                    'url' => $localizedUrl,
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource collection consists of 1 item of type urls')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfTypeWithSizeOf('urls', 1);
    }

    /**
     * @depends loadFixtures
     *
     * @param \PyzTest\Glue\Urls\UrlsRestApiTester $I
     *
     * @return void
     */
    public function requestExistingCategoryUrl(UrlsRestApiTester $I): void
    {
        //act
        $I->sendGET(
            $I->formatUrl(
                'urls?url={url}',
                [
                    'url' => $this->fixtures->getCategoryUrlTransfer()->getUrl(),
                ]
            )
        );

        //assert
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('Returned resource collection consists of 1 item of type urls')
            ->whenI()
            ->seeResponseDataContainsResourceCollectionOfTypeWithSizeOf('urls', 1);
    }
}
