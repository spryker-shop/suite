<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\Availability;

use Codeception\Actor;
use Codeception\Scenario;
use PyzTest\Yves\Customer\PageObject\CustomerLoginPage;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class AvailabilityPresentationTester extends Actor
{
    use _generated\AvailabilityPresentationTesterActions;

    const FUJITSU_PRODUCT_PAGE = '/en/fujitsu-esprimo-e420-118';
    const FUJITSU2_PRODUCT_PAGE = '/en/fujitsu-esprimo-e920-119';

    const CART_PRE_CHECK_AVAILABILITY_ERROR_MESSAGE = 'Item 119_29804808 only has availability of 10.';

    /**
     * @param \Codeception\Scenario $scenario
     */
    public function __construct(Scenario $scenario)
    {
        parent::__construct($scenario);

        $this->amYves();
//        $customerTransfer = $this->haveRegisteredCustomer();
//        $this->submitForm(['name' => 'loginForm'], [
//            CustomerLoginPage::FORM_FIELD_SELECTOR_EMAIL => $customerTransfer->getEmail(),
//            CustomerLoginPage::FORM_FIELD_SELECTOR_PASSWORD => $customerTransfer->getPassword(),
//        ]);

        $i = $this->getWebDriver();
        $customerTransfer = $this->haveRegisteredCustomer();;

        if ($i->loadSessionSnapshot($customerTransfer->getEmail())) {
            return;
        }

        $i = $this->getWebDriver();
        $i->amOnPage(CustomerLoginPage::URL);
        $i->waitForElement("form[name='loginForm']");
        $i->submitForm(['name' => 'loginForm'], [
            CustomerLoginPage::FORM_FIELD_SELECTOR_EMAIL => $customerTransfer->getEmail(),
            CustomerLoginPage::FORM_FIELD_SELECTOR_PASSWORD => $customerTransfer->getPassword(),
        ]);

        $i->waitForElement(CustomerLoginPage::CLASS_HOMEPAGE_SEARCH_FIELD);

        $i->saveSessionSnapshot($customerTransfer->getEmail());
    }

    /**
     * @return void
     */
    public function processCheckout()
    {
        $this->processAllCheckoutSteps();
    }
}
