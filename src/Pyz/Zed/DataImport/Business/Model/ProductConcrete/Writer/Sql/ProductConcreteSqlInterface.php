<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql;

interface ProductConcreteSqlInterface
{
    /**
     * @return string
     */
    public function createConcreteProductSQL(): string;

    /**
     * @return string
     */
    public function createConcreteProductLocalizedAttributesSQL(): string;

    /**
     * @return string
     */
    public function createConcreteProductSearchSQL(): string;

    /**
     * @return string
     */
    public function createConcreteProductBundleSQL(): string;
}
