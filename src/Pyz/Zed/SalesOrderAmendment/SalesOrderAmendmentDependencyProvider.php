<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\SalesOrderAmendment;

use Spryker\Zed\SalesOrderAmendment\SalesOrderAmendmentDependencyProvider as SprykerSalesOrderAmendmentDependencyProvider;
use Spryker\Zed\SalesOrderAmendmentOms\Communication\Plugin\SalesOrderAmendment\OrderSalesOrderAmendmentValidatorRulePlugin;

class SalesOrderAmendmentDependencyProvider extends SprykerSalesOrderAmendmentDependencyProvider
{
    /**
     * @return list<\Spryker\Zed\SalesOrderAmendmentExtension\Dependency\Plugin\SalesOrderAmendmentValidatorRulePluginInterface>
     */
    protected function getSalesOrderAmendmentCreateValidationRulePlugins(): array
    {
        return [
            new OrderSalesOrderAmendmentValidatorRulePlugin(),
        ];
    }
}
