<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore\Writer;

use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore\CombinedProductAbstractStoreHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\ProductAbstractStoreBulkPdoMariaDbDataSetWriter;

class CombinedProductAbstractStoreBulkPdoMariaDbDataSetWriter extends ProductAbstractStoreBulkPdoMariaDbDataSetWriter
{
    public const COLUMN_ABSTRACT_SKU = CombinedProductAbstractStoreHydratorStep::COLUMN_ABSTRACT_SKU;

    public const COLUMN_STORE_NAME = CombinedProductAbstractStoreHydratorStep::COLUMN_STORE_NAME;
}
