<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductGroup;

use Pyz\Zed\DataImport\Business\Model\ProductGroup\ProductGroupWriter;

class CombinedProductGroupWriter extends ProductGroupWriter
{
    /**
     * @var string
     */
    public const COLUMN_ABSTRACT_SKU = 'abstract_sku';

    /**
     * @var string
     */
    public const COLUMN_PRODUCT_GROUP_KEY = 'product_group.group_key';

    /**
     * @var string
     */
    public const COLUMN_POSITION = 'product_group.position';
}
