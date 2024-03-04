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
     * @skip Issue with session need to be fixed.
     *
     * @param \PyzTest\Yves\Checkout\CheckoutControllerTester $i
     *
     * @return void
     */
    public function quickAddToCart(CheckoutControllerTester $i): void
    {
        $i->amOnPage('/en/cart');
        $i->fillField(['name' => 'sku'], '147_30046188');
        $i->fillField(['name' => 'quantity'], 1);
        $i->click('Add to Cart');
        $i->canSeeResponseCodeIsRedirection();

        $i->amOnPage('/en/cart/quick-add/147_30046188?quantity=1');
        $i->canSeeResponseCodeIsRedirection();
    }
}
