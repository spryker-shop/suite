<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Communication\Plugin\ProductConcrete;

use Spryker\Zed\DataImport\Communication\Plugin\AbstractQueueWriterPlugin;

/**
 * @method \Pyz\Zed\DataImport\Business\DataImportFacadeInterface getFacade()
 * @method \Pyz\Zed\DataImport\DataImportConfig getConfig()
 */
class ProductConcreteQueueWriterPlugin extends AbstractQueueWriterPlugin
{
    /**
     * @return string
     */
    protected function getQueueName(): string
    {
        return $this->getConfig()->getProductConcreteQueueWriterConfiguration()->getQueueName();
    }

    /**
     * @return int
     */
    protected function getChunkSize(): int
    {
        return $this->getConfig()->getProductConcreteQueueWriterConfiguration()->getChunkSize();
    }
}
