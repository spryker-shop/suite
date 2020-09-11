<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductConfigurationWidget\Plugin\ProductConfigurationWidget;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\ProductConfigurationTemplateTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ProductConfigurationWidgetExtension\Dependency\Plugin\ProductConfigurationRenderStrategyPluginInterface;

/**
 * @TODO this class would be fully reworked in 2nd phase. Will keep it for now as demo version
 */
class ProductConfigurationPlainRenderStrategyPlugin extends AbstractPlugin implements ProductConfigurationRenderStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstance
     *
     * @return bool
     */
    public function isApplicable(ProductConfigurationInstanceTransfer $productConfigurationInstance): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstance
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationTemplateTransfer
     */
    public function getTemplate(ProductConfigurationInstanceTransfer $productConfigurationInstance): ProductConfigurationTemplateTransfer
    {
        return (new ProductConfigurationTemplateTransfer())
            ->setData(json_decode($productConfigurationInstance->getDisplayData(), true) ?? [])
            ->setModuleName('ProductConfigurationWidget')
            ->setTemplateType('view')
            ->setTemplateName('options-list');
    }
}
