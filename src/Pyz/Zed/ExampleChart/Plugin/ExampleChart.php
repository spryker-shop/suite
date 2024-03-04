<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ExampleChart\Plugin;

use Generated\Shared\Transfer\ChartDataTraceTransfer;
use Generated\Shared\Transfer\ChartDataTransfer;
use Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface;

class ExampleChart implements ChartPluginInterface
{
    /**
     * @return string
     */
    public function getName(): string
    {
        return 'testChart';
    }

    /**
     * @param string|null $dataIdentifier
     *
     * @return \Generated\Shared\Transfer\ChartDataTransfer
     */
    public function getChartData($dataIdentifier = null): ChartDataTransfer
    {
        $data = new ChartDataTransfer();
        $data->setTitle('test');
        $data->setKey('test');
        $data->addTrace($this->getTrace());

        return $data;
    }

    /**
     * @return \Generated\Shared\Transfer\ChartDataTraceTransfer
     */
    protected function getTrace(): ChartDataTraceTransfer
    {
        $trace = new ChartDataTraceTransfer();
        $trace->setValues([11, 23, 31]);
        $trace->setLabels(['one', 'two', 'three']);
        $trace->setType('pie');

        return $trace;
    }
}
