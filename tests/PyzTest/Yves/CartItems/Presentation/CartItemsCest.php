<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\CartItems\Presentation;

use PyzTest\Yves\Cart\PageObject\CartListPage;
use PyzTest\Yves\CartItems\CartItemsPresentationTester;
use PyzTest\Yves\CartItems\PageObject\CartItemsPage;
use PyzTest\Yves\Product\PageObject\ProductDetailPage;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Yves
 * @group CartItems
 * @group Presentation
 * @group CartItemsCest
 * Add your own group annotations below this line
 */
class CartItemsCest
{
    /**
     * @param \PyzTest\Yves\CartItems\CartItemsPresentationTester $i
     *
     * @return void
     */
    public function testAddToCartAndCheckExistItem(CartItemsPresentationTester $i): void
    {
        $i->wantTo('Open product page, and add some item to cart. Check if the cart items block shows on page');
        $i->amOnPage(CartItemsPage::PRODUCT_PAGE);

        $i->waitForElement(ProductDetailPage::ADD_TO_CART_XPATH);
        $i->waitForElementVisible(ProductDetailPage::ADD_TO_CART_XPATH, 60);
        $i->click(ProductDetailPage::ADD_TO_CART_XPATH);

        $i->seeInTitle(CartListPage::CART_HEADER);
        $i->waitForElementVisible(CartListPage::FIRST_CART_ITEM_SKU, 60);

        $sku = $i->grabTextFrom(CartListPage::FIRST_CART_ITEM_SKU);
        $i->assertEquals($sku, sprintf('SKU %s', CartItemsPage::PRODUCT_SKU));
    }
}
