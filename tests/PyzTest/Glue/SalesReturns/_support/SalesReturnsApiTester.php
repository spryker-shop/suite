<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\SalesReturns;

use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;
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
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(PHPMD)
 */
class SalesReturnsApiTester extends ApiEndToEndTester
{
    use _generated\SalesReturnsApiTesterActions;


    /**
     * @param string[] $includes
     *
     * @return string
     */
    public function formatQueryInclude(array $includes = []): string
    {
        if (!$includes) {
            return '';
        }

        return sprintf('?%s=%s', RequestConstantsInterface::QUERY_INCLUDE, implode(',', $includes));
    }

    /**
     * @param string[] $includes
     *
     * @return string
     */
    public function buildGuestReturnReasonsUrl(array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceReturnReasons}' . $this->formatQueryInclude($includes),
            [
                'resourceReturnReasons' => SalesReturnsRestApiConfig::RESOURCE_RETURN_REASONS,
            ]
        );
    }
}
