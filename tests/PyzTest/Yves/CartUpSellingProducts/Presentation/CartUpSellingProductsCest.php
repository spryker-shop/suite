<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\CartUpSellingProducts\Presentation;

use PyzTest\Yves\Cart\PageObject\CartListPage;
use PyzTest\Yves\CartUpSellingProducts\CartUpSellingProductsPresentationTester;
use PyzTest\Yves\CartUpSellingProducts\PageObject\CartUpSellingPage;
use PyzTest\Yves\Product\PageObject\ProductDetailPage;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Yves
 * @group CartUpSellingProducts
 * @group Presentation
 * @group CartUpSellingProductsCest
 * Add your own group annotations below this line
 */
class CartUpSellingProductsCest
{
    /**
     * @param \PyzTest\Yves\CartUpSellingProducts\CartUpSellingProductsPresentationTester $i
     *
     * @return void
     */
    public function testAddToCartItemAndCheckUpsellingItemsExist(CartUpSellingProductsPresentationTester $i): void
    {
        $i->wantTo('Open product page, and add item to cart and check up-selling widget');
        $i->amOnPage(CartUpSellingPage::PRODUCT_PAGE);
        $i->waitForElement(ProductDetailPage::ADD_TO_CART_XPATH);
        $i->waitForElementVisible(ProductDetailPage::ADD_TO_CART_XPATH, 60);

        $i->click(ProductDetailPage::ADD_TO_CART_XPATH);
        $i->seeInTitle(CartListPage::CART_HEADER);

        $i->waitForElementVisible(CartUpSellingPage::SIMPLE_CAROUSEL_CONTAINER, 60);
    }
}
