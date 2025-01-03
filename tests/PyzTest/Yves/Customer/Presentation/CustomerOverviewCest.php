<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Yves\Customer\Presentation;

use PyzTest\Yves\Customer\CustomerPresentationTester;
use PyzTest\Yves\Customer\PageObject\CustomerAddressesPage;
use PyzTest\Yves\Customer\PageObject\CustomerNewsletterPage;
use PyzTest\Yves\Customer\PageObject\CustomerOrdersPage;
use PyzTest\Yves\Customer\PageObject\CustomerOverviewPage;
use PyzTest\Yves\Customer\PageObject\CustomerProfilePage;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Yves
 * @group Customer
 * @group Presentation
 * @group CustomerOverviewCest
 * Add your own group annotations below this line
 */
class CustomerOverviewCest
{
    /**
     * @param \PyzTest\Yves\Customer\CustomerPresentationTester $i
     *
     * @return void
     */
    public function _before(CustomerPresentationTester $i): void
    {
        $i->amYves();
    }

    /**
     * @skip This test was temporarily skipped due to flikerness. See {@link https://spryker.atlassian.net/browse/CC-25718} for details
     *
     * @param \PyzTest\Yves\Customer\CustomerPresentationTester $i
     *
     * @return void
     */
    public function testICanOpenOverviewPage(CustomerPresentationTester $i): void
    {
        $i->amLoggedInCustomer();
        $i->amOnPage(CustomerOverviewPage::URL);

        $i->see(CustomerOverviewPage::BOX_HEADLINE_ORDERS, 'h5');
        $i->see(CustomerOverviewPage::BOX_HEADLINE_PROFILE, 'h5');
        $i->see(CustomerOverviewPage::BOX_HEADLINE_NEWSLETTER, 'h5');
    }

    /**
     * @skip This test was temporarily skipped due to flikerness. See {@link https://spryker.atlassian.net/browse/CC-25718} for details
     *
     * @param \PyzTest\Yves\Customer\CustomerPresentationTester $i
     *
     * @return void
     */
    public function testCustomerWithoutAddressShouldSeeAddAddressInfoText(CustomerPresentationTester $i): void
    {
        $i->amLoggedInCustomer();
        $i->amOnPage(CustomerOverviewPage::URL);

        // $i->see(CustomerOverviewPage::INFO_TEXT_ADD_SHIPPING_ADDRESS);
        // $i->see(CustomerOverviewPage::INFO_TEXT_ADD_BILLING_ADDRESS);
    }

    /**
     * @skip This test was temporarily skipped due to flikerness. See {@link https://spryker.atlassian.net/browse/CC-25718} for details
     *
     * @param \PyzTest\Yves\Customer\CustomerPresentationTester $i
     *
     * @return void
     */
    public function testICanGoFromOverviewToProfilePage(CustomerPresentationTester $i): void
    {
        $i->amLoggedInCustomer();
        $i->amOnPage(CustomerOverviewPage::URL);
        $i->click(CustomerOverviewPage::LINK_TO_PROFILE_PAGE);
        $i->amOnPage(CustomerProfilePage::URL);
    }

    /**
     * @skip This test was temporarily skipped due to flikerness. See {@link https://spryker.atlassian.net/browse/CC-25718} for details
     *
     * @param \PyzTest\Yves\Customer\CustomerPresentationTester $i
     *
     * @return void
     */
    public function testICanGoFromOverviewToAddressesPage(CustomerPresentationTester $i): void
    {
        $i->amLoggedInCustomer();
        $i->amOnPage(CustomerOverviewPage::URL);
        $i->click(CustomerOverviewPage::LINK_TO_ADDRESSES_PAGE);
        $i->amOnPage(CustomerAddressesPage::URL);
    }

    /**
     * @skip This test was temporarily skipped due to flikerness. See {@link https://spryker.atlassian.net/browse/CC-25718} for details
     *
     * @param \PyzTest\Yves\Customer\CustomerPresentationTester $i
     *
     * @return void
     */
    public function testICanGoFromOverviewToOrdersPage(CustomerPresentationTester $i): void
    {
        $i->amLoggedInCustomer();
        $i->amOnPage(CustomerOverviewPage::URL);
        $i->click(CustomerOverviewPage::LINK_TO_ORDERS_PAGE);
        $i->amOnPage(CustomerOrdersPage::URL);
    }

    /**
     * @skip This test was temporarily skipped due to flikerness. See {@link https://spryker.atlassian.net/browse/CC-25718} for details
     *
     * @param \PyzTest\Yves\Customer\CustomerPresentationTester $i
     *
     * @return void
     */
    public function testICanGoFromOverviewToNewsletterPage(CustomerPresentationTester $i): void
    {
        $i->amLoggedInCustomer();
        $i->amOnPage(CustomerOverviewPage::URL);
        $i->click(CustomerOverviewPage::LINK_TO_NEWSLETTER_PAGE);
        $i->amOnPage(CustomerNewsletterPage::URL);
    }
}
