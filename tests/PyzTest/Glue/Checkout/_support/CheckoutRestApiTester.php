<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Checkout;

use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType;
use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodTypeQuery;
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
     * @param string $providerName
     * @param string $methodName
     *
     * @return \Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType
     */
    public function grabPaymentMethod(string $providerName, string $methodName): SpySalesPaymentMethodType
    {
        $paymentMethod = SpySalesPaymentMethodTypeQuery::create()
            ->filterByPaymentProvider($providerName)
            ->filterByPaymentMethod($methodName)
            ->findOne();

        if ($paymentMethod) {
            return $paymentMethod;
        }

        $this->fail(sprintf('Payment method %s:%s not found', $providerName, $methodName));
    }
}
