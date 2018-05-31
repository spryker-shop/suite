<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock;

use Generated\Shared\Transfer\SpyStockEntityTransfer;
use Generated\Shared\Transfer\SpyStockProductEntityTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ProductStockHydratorStep implements DataImportStepInterface
{
    const KEY_NAME = 'name';
    const KEY_CONCRETE_SKU = 'concrete_sku';
    const KEY_QUANTITY = 'quantity';
    const KEY_IS_NEVER_OUT_OF_STOCK = 'is_never_out_of_stock';
    const KEY_IS_BUNDLE = 'is_bundle';
    const KEY_FK_PRODUCT = 'fk_product';
    const KEY_FK_STOCK = 'fk_stock';
    const STOCK_ENTITY_TRANSFER = 'STOCK_ENTITY_TRANSFER';
    const STOCK_PRODUCT_ENTITY_TRANSFER = 'STOCK_PRODUCT_ENTITY_TRANSFER';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->importStock($dataSet);
        $this->importStockProduct($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importStock(DataSetInterface $dataSet): void
    {
        $stockEntityTransfer = new SpyStockEntityTransfer();
        $stockEntityTransfer->setName($dataSet[static::KEY_NAME]);

        $dataSet[static::STOCK_ENTITY_TRANSFER] = $stockEntityTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importStockProduct(DataSetInterface $dataSet): void
    {
        $stockProductEntityTransfer = new SpyStockProductEntityTransfer();
        $stockProductEntityTransfer
            ->setQuantity($dataSet[static::KEY_QUANTITY])
            ->setIsNeverOutOfStock($dataSet[static::KEY_IS_NEVER_OUT_OF_STOCK]);

        $dataSet[static::STOCK_PRODUCT_ENTITY_TRANSFER] = $stockProductEntityTransfer;
    }
}
