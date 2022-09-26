<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductConcrete\Writer;

use Pyz\Zed\DataImport\Business\CombinedProduct\ProductConcrete\CombinedProductConcreteHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\ProductConcreteBulkPdoMariaDbDataSetWriter;

class CombinedProductConcreteBulkPdoMariaDbDataSetWriter extends ProductConcreteBulkPdoMariaDbDataSetWriter
{
    public const BULK_SIZE = CombinedProductConcreteHydratorStep::BULK_SIZE;

    protected const COLUMN_NAME = CombinedProductConcreteHydratorStep::COLUMN_NAME;

    protected const COLUMN_DESCRIPTION = CombinedProductConcreteHydratorStep::COLUMN_DESCRIPTION;

    protected const COLUMN_IS_SEARCHABLE = CombinedProductConcreteHydratorStep::COLUMN_IS_SEARCHABLE;

    protected const COLUMN_ABSTRACT_SKU = CombinedProductConcreteHydratorStep::COLUMN_ABSTRACT_SKU;
}
