<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProductImporter\ProductImage\Writer;

use Pyz\Zed\DataImport\Business\Model\ProductImage\Repository\ProductImageRepositoryInterface;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\ProductImagePropelDataSetWriter as WriterProductImagePropelDataSetWriter;

class ProductImagePropelDataSetWriter extends WriterProductImagePropelDataSetWriter
{
    /**
     * @param \Pyz\Zed\DataImport\Business\Model\ProductImage\Repository\ProductImageRepositoryInterface $productImageRepository
     */
    public function __construct(ProductImageRepositoryInterface $productImageRepository)
    {
        parent::__construct($productImageRepository);
    }
}
