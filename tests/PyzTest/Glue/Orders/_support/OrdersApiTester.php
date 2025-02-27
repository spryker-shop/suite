<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Glue\Orders;

use Orm\Zed\SalesOrderAmendment\Persistence\SpySalesOrderAmendmentQuery;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\OrdersRestApi\OrdersRestApiConfig;
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
 * @SuppressWarnings(\PyzTest\Glue\Orders\PHPMD)
 */
class OrdersApiTester extends ApiEndToEndTester
{
    use _generated\OrdersApiTesterActions;

    /**
     * @param string $orderUuid
     * @param array<string> $includes
     *
     * @return string
     */
    public function buildOrdersUrl(string $orderUuid, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceOrders}/{orderUuid}' . $this->formatQueryInclude($includes),
            [
                'resourceOrders' => OrdersRestApiConfig::RESOURCE_ORDERS,
                'orderUuid' => $orderUuid,
            ],
        );
    }

    /**
     * @return void
     */
    public function ensureSalesOrderAmendmentTableIsEmpty(): void
    {
        $this->ensureDatabaseTableIsEmpty(SpySalesOrderAmendmentQuery::create());
    }

    /**
     * @param array<string> $includes
     *
     * @return string
     */
    protected function formatQueryInclude(array $includes = []): string
    {
        if (!$includes) {
            return '';
        }

        return sprintf('?%s=%s', RequestConstantsInterface::QUERY_INCLUDE, implode(',', $includes));
    }
}
