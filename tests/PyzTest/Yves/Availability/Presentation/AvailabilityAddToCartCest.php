<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Yves\Availability\Presentation;

use PyzTest\Yves\Availability\AvailabilityPresentationTester;
use PyzTest\Yves\Cart\PageObject\CartListPage;
use PyzTest\Yves\Product\PageObject\ProductDetailPage;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Yves
 * @group Availability
 * @group Presentation
 * @group AvailabilityAddToCartCest
 * Add your own group annotations below this line
 */
class AvailabilityAddToCartCest
{
    /**
     * @param \PyzTest\Yves\Availability\AvailabilityPresentationTester $i
     *
     * @return void
     */
    public function _before(AvailabilityPresentationTester $i): void
    {
        $i->amYves();
    }

    /**
     * @param \PyzTest\Yves\Availability\AvailabilityPresentationTester $i
     *
     * @return void
     */
    public function testAddToCartWhenBiggerQuantityIsUsed(AvailabilityPresentationTester $i): void
    {
        if ($i->isCartItemsViaAjaxLoadEnabled()) {
            $i->markTestSkipped('Skip test in case if cart items via ajax load enabled');
        }

        $i->wantTo('Open product page, and add item to cart with larger quantity than available');
        $i->expectTo('Display error message');

        $i->amLoggedInCustomer();
        $i->amOnPage(AvailabilityPresentationTester::FUJITSU2_PRODUCT_PAGE);
        $i->click(ProductDetailPage::ADD_TO_CART_XPATH);

        $i->seeInTitle(CartListPage::CART_HEADER);
        $i->waitForElement(CartListPage::FIRST_CART_ITEM_QUANTITY_INPUT_XPATH);
        $i->fillField(CartListPage::FIRST_CART_ITEM_QUANTITY_INPUT_XPATH, 50);
        $i->click(CartListPage::FIRST_CART_ITEM_CHANGE_QUANTITY_BUTTON_XPATH);

        $i->seeInSource(AvailabilityPresentationTester::CART_PRE_CHECK_AVAILABILITY_ERROR_MESSAGE);
    }
}
