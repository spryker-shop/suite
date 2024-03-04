<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\ProductImageConcrete;

use Pyz\Zed\DataImport\Business\CombinedProduct\ProductImage\CombinedProductImageHydratorStep;

class CombinedProductImageConcreteHydratorStep extends CombinedProductImageHydratorStep
{
    /**
     * @var string
     */
    protected const ASSIGNABLE_PRODUCT_TYPE_BOTH = 'both';

    /**
     * @var array<string>
     */
    protected const ASSIGNABLE_PRODUCT_TYPES = [
        self::ASSIGNABLE_PRODUCT_TYPE_CONCRETE,
        self::ASSIGNABLE_PRODUCT_TYPE_BOTH,
    ];
}
