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
        $i->wantTo('Add a product to my cart');
        $i->amOnPage('/en/canon-powershot-n-35');
        $i->click('Add to Cart');

        $i->wantTo('Start the checkout process');
        $i->amOnPage('/en/cart');
        $i->canSeeCurrentUrlMatches('#/en/cart#');
        $i->click('Checkout');

        $i->wantTo('Fill out guest checkout form');
        $i->amOnPage('/en/checkout/customer');
        $i->canSeeCurrentUrlMatches('#/en/checkout#');
        $i->click('#guest');
        $i->fillField('#guestForm_customer_first_name', 'Harry');
        $i->fillField('#guestForm_customer_last_name', 'Klein');
        $i->fillField('#guestForm_customer_email', 'harry.klein@spryker.com');
        $i->checkOption('#guestForm_customer_accept_terms');
        $i->click('Submit');

        $i->wantTo('Fill out the address form');
        $i->amOnPage('/en/checkout/address');
        $i->canSeeCurrentUrlMatches('#/en/checkout/address#');
        $i->fillField('#addressesForm_shippingAddress_first_name', 'Harry');
        $i->fillField('#addressesForm_shippingAddress_last_name', 'Klein');
        $i->fillField('#addressesForm_shippingAddress_address1', 'Julie-Wolfthorn-Str');
        $i->fillField('#addressesForm_shippingAddress_address2', '1');
        $i->fillField('#addressesForm_shippingAddress_zip_code', '10235');
        $i->fillField('#addressesForm_shippingAddress_city', 'Berlin');
        $i->checkOption('#addressesForm_billingSameAsShipping');
        $i->click('Next');

        $i->wantTo('Select a shipment');
        $i->amOnPage('/en/checkout/shipment');
        $i->canSeeCurrentUrlMatches('#/en/checkout/shipment#');
        // Standard: €4.90
        $i->selectOption('#shipmentCollectionForm_shipmentGroups_0_shipment_shipmentSelection_4', ['value' => 4]);
        $i->click('Next');

        $i->wantTo('Select a payment');
        $i->amOnPage('/en/checkout/payment');
        $i->canSeeCurrentUrlMatches('#/en/checkout/payment#');
        $i->selectOption('#paymentForm_paymentSelection_0', ['value' => 'dummyPaymentInvoice']);
        $i->fillField('#paymentForm_dummyPaymentInvoice_date_of_birth', '01.01.2000');
        $i->click('Go to Summary');

        $i->wantTo('Finalize checkout');
        $i->amOnPage('/en/checkout/summary');
        $i->canSeeCurrentUrlMatches('#/en/checkout/summary#');

        $token = $i->grabValueFrom('#summaryForm__token');

        $i->submitForm(['name' => 'summaryForm'], [
            'summaryForm[acceptTermsAndConditions]' => 1,
            'summaryForm[_token]' => $token,
        ]);

        $i->amOnPage('/en/checkout/place-order');
        $i->amOnPage('/en/checkout/summary');
        $i->amOnPage('/en/checkout/success');
        $i->see('Your order has been placed successfully!');
    }
}
