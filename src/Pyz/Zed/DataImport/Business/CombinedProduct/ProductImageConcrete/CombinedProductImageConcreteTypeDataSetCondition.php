<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductImageConcrete;

use Pyz\Zed\DataImport\Business\CombinedProduct\ProductImageAbstract\CombinedProductImageAbstractHydratorStep;
use Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CombinedProductImageConcreteTypeDataSetCondition implements DataSetConditionInterface
{
    /**
     * @var string
     */
    protected const ASSIGNABLE_PRODUCT_TYPE_CONCRETE = 'concrete';

    /**
     * @var string
     */
    protected const ASSIGNABLE_PRODUCT_TYPE_BOTH = 'both';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    public function hasData(DataSetInterface $dataSet): bool
    {
        if (
            in_array(
                $dataSet[CombinedProductImageAbstractHydratorStep::COLUMN_ASSIGNED_PRODUCT_TYPE],
                [static::ASSIGNABLE_PRODUCT_TYPE_CONCRETE, static::ASSIGNABLE_PRODUCT_TYPE_BOTH],
            )
        ) {
            return true;
        }

        return false;
    }
}
