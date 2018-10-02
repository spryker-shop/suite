<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstractStore;

use Generated\Shared\Transfer\ProductAbstractStoreTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ProductAbstractStoreHydratorStep implements DataImportStepInterface
{
    public const BULK_SIZE = 1000;
    public const KEY_PRODUCT_ABSTRACT_SKU = 'product_abstract_sku';
    public const KEY_STORE_NAME = 'store_name';
    public const DATA_PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFER = 'DATA_PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFER';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->importProductAbstractStore($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importProductAbstractStore(DataSetInterface $dataSet): void
    {
        $productAbstractStoreTransfer = new ProductAbstractStoreTransfer();
        $productAbstractStoreTransfer
            ->setStoreName($dataSet[static::KEY_STORE_NAME])
            ->setProductAbstractSku($dataSet[static::KEY_PRODUCT_ABSTRACT_SKU]);

        $dataSet[static::DATA_PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFER] = $productAbstractStoreTransfer;
    }
}
