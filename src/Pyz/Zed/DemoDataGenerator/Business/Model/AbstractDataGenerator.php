<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model;

use Generated\Shared\DataBuilder\LocaleBuilder;
use Generated\Shared\DataBuilder\ProductAbstractBuilder;
use Generated\Shared\DataBuilder\ProductConcreteBuilder;
use Generated\Shared\DataBuilder\ProductImageBuilder;
use Generated\Shared\DataBuilder\ProductImageSetBuilder;

abstract class AbstractDataGenerator
{
    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateProductConcrete()
    {
        return (new ProductConcreteBuilder())->withLocalizedAttributes()->build();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateProductAbstract()
    {
        return (new ProductAbstractBuilder())->build();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductImageTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateProductImage()
    {
        return (new ProductImageBuilder())->build();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductImageTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateProductImageSet()
    {
        return (new ProductImageSetBuilder())->build();
    }

    /**
     * @return \Generated\Shared\Transfer\ProductImageTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    protected function generateLocale()
    {
        return (new LocaleBuilder())->build();
    }
}
