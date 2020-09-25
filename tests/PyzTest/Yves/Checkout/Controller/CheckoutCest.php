<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\Checkout\Controller;

use PyzTest\Yves\Checkout\CheckoutControllerTester;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Yves
 * @group Checkout
 * @group Controller
 * @group CheckoutCest
 * Add your own group annotations below this line
 */
class CheckoutCest
{
    /**
     * @param \PyzTest\Yves\Checkout\CheckoutControllerTester $i
     *
     * @return void
     */
    public function checkoutWithGuest(CheckoutControllerTester $i): void
    {
        $i->setCookie('XDEBUG_SESSION', 'XDEBUG_ECLIPSE');
        $i->amOnPage('/en/asus-transformer-book-t200ta-139');
        $i->click('Add to Cart');
        $i->canSeeCurrentUrlMatches('#/en/cart#');

        // Open page to continue
        $i->amOnPage('/en/cart');
        $i->click('Checkout');
        // This page makes a redirect to /en/checkout/customer when customer is not logged in
        $i->canSeeCurrentUrlMatches('#/en/checkout#');

        // Open page to continue
        $i->amOnPage('/en/checkout/customer');
        $i->click('#guest');
        $i->fillField('#guestForm_customer_first_name', 'Harry');
        $i->fillField('#guestForm_customer_last_name', 'Klein');
        $i->fillField('#guestForm_customer_email', 'harry.klein@spryker.com');
        $i->click('#registerForm_customer_accept_terms');
        $i->click('Submit');
    }
}
