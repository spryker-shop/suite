<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\AppCatalogGui\Presentation;

use PyzTest\Zed\AppCatalogGui\AppCatalogGuiPresentationTester;
use PyzTest\Zed\AppCatalogGui\PageObject\AppCatalogGuiApiLoginPage;
use PyzTest\Zed\AppCatalogGui\PageObject\AppCatalogGuiIndexPage;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group AppCatalogGui
 * @group Presentation
 * @group AppCatalogGuiControllerCest
 * Add your own group annotations below this line
 */
class AppCatalogGuiControllerCest
{
    /**
     * @param \PyzTest\Zed\AppCatalogGui\AppCatalogGuiPresentationTester $I
     *
     * @return void
     */
    public function checkIfAppCatalogGuiReturn200AndValidUrl(AppCatalogGuiPresentationTester $I): void
    {
        if ($I->seeThatDynamicStoreEnabled()) {
            $I->markTestSkipped('Test is valid for Static Store mode only.');
        }

        // Arrange
        /** @var \Spryker\Zed\AppCatalogGui\AppCatalogGuiConfig $appCatalogGuiConfig */
        $appCatalogGuiConfig = $I->getModuleConfig();
        $storeTransfer = $I->getAllowedStore();
        $locale = array_search(
            $storeTransfer->getDefaultLocaleIsoCode(),
            $storeTransfer->getAvailableLocaleIsoCodes(),
            true,
        );
        $I->amZed();
        $I->amLoggedInUser();

        $I->setCookie('acceptedTerms', 'true');

        // Act
        $I->amOnPage(AppCatalogGuiIndexPage::APP_CATALOG_GUI_INDEX_PAGE_URL);

        // Assert
        $I->seeInSource(sprintf(
            AppCatalogGuiIndexPage::APP_CATALOG_SCRIPT,
            $appCatalogGuiConfig->getAppCatalogScriptUrl(),
            $storeTransfer->getStoreReference() ?: '',
            $locale,
        ));
    }

    /**
     * @param \PyzTest\Zed\AppCatalogGui\AppCatalogGuiPresentationTester $I
     *
     * @return void
     */
    public function checkIfAppCatalogGuiApiLoginReturn200AndValidToken(AppCatalogGuiPresentationTester $I): void
    {
        if ($I->seeThatDynamicStoreEnabled()) {
            $I->markTestSkipped('Test is valid for Static Store mode only.');
        }

        // Arrange
        $I->amZed();
        $I->amLoggedInUser();

        // Act
        $I->amOnPage(AppCatalogGuiApiLoginPage::APP_CATALOG_GUI_API_LOGIN_PAGE_URL);

        // Assert
        $I->seeInSource('"access_token":');
        $I->seeInSource('"expires_in":');
    }
}
