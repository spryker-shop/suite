<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\Cart\Controller;

use PyzTest\Yves\Checkout\CheckoutControllerTester;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Yves
 * @group Cart
 * @group Controller
 * @group CartCest
 * Add your own group annotations below this line
 */
class CartCest
{
    /**
     * @param \PyzTest\Yves\Checkout\CheckoutControllerTester $i
     *
     * @return void
     */
    public function quickAddToCart(CheckoutControllerTester $i): void
    {
        $i->amOnPage('/en/cart');
        $i->fillField(['name' => 'product_quick_add_form[sku]'], 147);
        $i->fillField(['name' => 'product_quick_add_form[quantity]'], 1);
        $i->click('Add to Cart');

        $i->seeResponseCodeIs(200);
    }
}
