<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Communication\Plugin\ProductImage;

use Spryker\Zed\DataImport\Communication\Plugin\AbstractQueueWriterPlugin;

/**
 * @method \Pyz\Zed\DataImport\Business\DataImportFacadeInterface getFacade()
 * @method \Pyz\Zed\DataImport\DataImportConfig getConfig()
 */
class ProductImageQueueWriterPlugin extends AbstractQueueWriterPlugin
{
    /**
     * @return string
     */
    protected function getQueueName(): string
    {
        return $this->getConfig()->getProductImageQueueWriterConfiguration()->getQueueName();
    }

    /**
     * @return int
     */
    protected function getChunkSize(): int
    {
        return $this->getConfig()->getProductImageQueueWriterConfiguration()->getChunkSize();
    }
}
