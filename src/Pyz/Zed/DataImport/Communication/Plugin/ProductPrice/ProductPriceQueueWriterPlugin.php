<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Communication\Plugin\ProductPrice;

use Spryker\Zed\DataImport\Communication\Plugin\AbstractQueueWriterPlugin;

/**
 * @method \Pyz\Zed\DataImport\Business\DataImportFacadeInterface getFacade()
 * @method \Pyz\Zed\DataImport\DataImportConfig getConfig()
 */
class ProductPriceQueueWriterPlugin extends AbstractQueueWriterPlugin
{
    /**
     * @return string
     */
    protected function getQueueName(): string
    {
        return $this->getConfig()->getProductPriceQueueWriterConfiguration()->getQueueName();
    }

    /**
     * @return int
     */
    protected function getChunkSize(): int
    {
        return $this->getConfig()->getProductPriceQueueWriterConfiguration()->getChunkSize();
    }
}
