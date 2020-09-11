<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\SalesProductConfigurationWidget\Plugin\SalesProductConfigurationWidget;

use Generated\Shared\Transfer\ProductConfigurationTemplateTransfer;
use Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\SalesProductConfigurationWidgetExtension\Dependency\Plugin\SalesProductConfigurationRenderStrategyPluginInterface;

/**
 * @TODO this class would be fully reworked in 2nd phase. Will keep it for now as demo version
 */
class SalesProductConfigurationPlainRenderStrategyPlugin extends AbstractPlugin implements SalesProductConfigurationRenderStrategyPluginInterface
{
    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     *
     * @return bool
     */
    public function isApplicable(SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer): bool
    {
        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTemplateTransfer
     */
    public function getTemplate(SalesOrderItemConfigurationTransfer $salesOrderItemConfigurationTransfer): ProductConfigurationTemplateTransfer
    {
        return (new ProductConfigurationTemplateTransfer())
            ->setData(json_decode($salesOrderItemConfigurationTransfer->getDisplayData(), true) ?? [])
            ->setModuleName('SalesProductConfigurationWidget')
            ->setTemplateType('view')
            ->setTemplateName('options-list');
    }
}
