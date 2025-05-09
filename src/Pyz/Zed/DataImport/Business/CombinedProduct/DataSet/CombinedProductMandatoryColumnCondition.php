<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\DataImport\Business\CombinedProduct\DataSet;

use Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

abstract class CombinedProductMandatoryColumnCondition implements DataSetConditionInterface
{
    /**
     * Specification:
     * - Returns a list of columns.
     *
     * @return array<string>
     */
    abstract protected function getMandatoryColumns(): array;

    /**
     * Specification:
     * - Returns true if at least 1 mandatory column contains data.
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    final public function hasData(DataSetInterface $dataSet): bool
    {
        foreach ($this->getMandatoryColumns() as $mandatoryColumn) {
            if (!empty($dataSet[$mandatoryColumn])) {
                return true;
            }
        }

        return false;
    }
}
