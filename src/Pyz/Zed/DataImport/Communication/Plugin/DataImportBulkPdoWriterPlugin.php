<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Communication\Plugin;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportFlushPluginInterface;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportWriterPluginInterface;

class DataImportBulkPdoWriterPlugin implements DataImportWriterPluginInterface, DataImportFlushPluginInterface
{
    const BULK_SIZE = 100;

    /**
     * @var array
     */
    protected static $buffer = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet)
    {
        static::$buffer[] = $dataSet;

        if (count(static::$buffer) >= static::BULK_SIZE) {
            echo 'Write All dataSets in bulk operation: ' . count(static::$buffer) . PHP_EOL;
            static::$buffer = [];
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        echo 'Flush the rest dataSets in bulk operation: ' . count(static::$buffer) . PHP_EOL;
    }
}
