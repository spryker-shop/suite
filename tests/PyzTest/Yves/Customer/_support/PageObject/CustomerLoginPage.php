<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Yves\Customer\PageObject;

class CustomerLoginPage extends Customer
{
    const URL = '/register';

    const FORGOT_PASSWORD_LINK = '[data-qa="customer-forgot-password-link"]';

    const TITLE_LOGIN = 'Access your account';
    const TITLE_FORGOT_PASSWORD = 'Recover my password';

    const FORM_FIELD_SELECTOR_EMAIL = 'loginForm[email]';
    const FORM_FIELD_SELECTOR_PASSWORD = 'loginForm[password]';

    const FORM_NAME_LOGIN_FORM = 'loginForm';

    const LOGOUT_LINK = ['id' => 'logout-link'];
}
