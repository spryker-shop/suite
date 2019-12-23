<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout;

use Orm\Zed\Payment\Persistence\Base\SpyPaymentMethod;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use SprykerTest\Glue\Testify\Tester\ApiEndToEndTester;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class CheckoutRestApiTester extends ApiEndToEndTester
{
    use _generated\CheckoutRestApiTesterActions;

    /**
     * @param string $paymentMethodKey
     *
     * @return \Orm\Zed\Payment\Persistence\Base\SpyPaymentMethod
     */
    public function grabPaymentMethod(string $paymentMethodKey): SpyPaymentMethod
    {
        $paymentMethod = SpyPaymentMethodQuery::create()
            ->filterByPaymentMethodKey($paymentMethodKey)
            ->findOne();

        if ($paymentMethod) {
            return $paymentMethod;
        }

        $this->fail(sprintf('Payment method %s not found', $paymentMethodKey));
    }
}
