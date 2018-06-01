<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Communication\Plugin;

use Generated\Shared\Transfer\DemoDataGeneratorTransfer;
use Pyz\Zed\DemoDataGenerator\Dependency\Plugin\DemoDataGeneratorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\DemoDataGenerator\Business\DemoDataGeneratorFacadeInterface getFacade()
 * @method \Pyz\Zed\DemoDataGenerator\Communication\DemoDataGeneratorCommunicationFactory getFactory()
 */
class PriceProductCsvGeneratorPlugin extends AbstractPlugin implements DemoDataGeneratorPluginInterface
{
    protected const PLUGIN_TYPE = 'price_product';

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::PLUGIN_TYPE;
    }

    /**
     * @param \Generated\Shared\Transfer\DemoDataGeneratorTransfer $demoDataGeneratorTransfer
     *
     * @return void
     */
    public function generateDemoData(DemoDataGeneratorTransfer $demoDataGeneratorTransfer): void
    {
        $this->getFacade()->createPriceProductCsvDemoData();
    }
}
