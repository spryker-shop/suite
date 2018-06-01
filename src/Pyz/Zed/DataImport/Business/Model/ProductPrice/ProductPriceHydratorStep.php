<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductPrice;

use Generated\Shared\Transfer\SpyCurrencyEntityTransfer;
use Generated\Shared\Transfer\SpyPriceProductEntityTransfer;
use Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer;
use Generated\Shared\Transfer\SpyPriceTypeEntityTransfer;
use Generated\Shared\Transfer\SpyProductAbstractEntityTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyStoreEntityTransfer;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ProductPriceHydratorStep implements DataImportStepInterface
{
    public const BULK_SIZE = 5000;
    public const KEY_ABSTRACT_SKU = 'abstract_sku';
    public const KEY_CONCRETE_SKU = 'concrete_sku';
    public const KEY_ID_PRODUCT_ABSTRACT = 'id_product_abstract';
    public const KEY_ID_PRODUCT = 'id_product';
    public const KEY_ID_PRICE_PRODUCT = 'id_price_product';
    public const KEY_STORE = 'store';
    public const KEY_CURRENCY = 'currency';
    public const KEY_PRICE_TYPE = 'price_type';
    public const KEY_PRICE_TYPE_NAME = 'name';
    public const KEY_PRICE_MODE_CONFIGURATION = 'price_mode_configuration';
    public const KEY_DEFAULT_PRICE_MODE_CONFIGURATION = 2;
    public const KEY_PRICE_NET = 'value_net';
    public const KEY_PRICE_GROSS = 'value_gross';
    public const KEY_PRICE_GROSS_DB = 'gross_price';
    public const KEY_PRICE_NET_DB = 'net_price';
    public const KEY_CURRENCY_NAME = 'name';
    public const KEY_STORE_NAME = 'name';
    public const KEY_SPY_PRODUCT_ABSTRACT = 'spy_product_abstract';
    public const KEY_SPY_PRODUCT = 'spy_product';
    public const KEY_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';
    public const KEY_FK_PRODUCT = 'fk_product';
    public const KEY_PRICE_PRODUCT_STORES = 'spy_price_product_stores';
    public const KEY_PRODUCT = 'product';
    public const KEY_SKU = 'sku';
    public const PRICE_TYPE_TRANSFER = 'PRICE_TYPE_TRANSFER';
    public const PRICE_PRODUCT_TRANSFER = 'PRICE_PRODUCT_TRANSFER';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->importPriceType($dataSet);
        $this->importProductPrice($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    protected function importProductPrice(DataSetInterface $dataSet): void
    {
        $productPriceTransfer = new SpyPriceProductEntityTransfer();

        if (empty($dataSet[static::KEY_ABSTRACT_SKU]) && empty($dataSet[static::KEY_CONCRETE_SKU])) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                'One of "%s" or "%s" must be in the data set. Given: "%s"',
                $dataSet[static::KEY_ABSTRACT_SKU],
                $dataSet[static::KEY_ABSTRACT_SKU],
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }

        if (!empty($dataSet[static::KEY_ABSTRACT_SKU])) {
            $productPriceTransfer->setSpyProductAbstract($this->importProductAbstract($dataSet));
        } else {
            $productPriceTransfer->setProduct($this->importProductConcrete($dataSet));
        }

        $productPriceTransfer
            ->setPriceType($this->importPriceType($dataSet))
            ->addSpyPriceProductStores($this->importPriceProductStore($dataSet));

        $dataSet[static::PRICE_PRODUCT_TRANSFER] = $productPriceTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet $dataSet
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductStoreEntityTransfer
     */
    protected function importPriceProductStore(DataSetInterface $dataSet): SpyPriceProductStoreEntityTransfer
    {
        $currencyEntityTransfer = new SpyCurrencyEntityTransfer();
        $currencyEntityTransfer->setName($dataSet[static::KEY_CURRENCY]);

        $storeEntityTransfer = new SpyStoreEntityTransfer();
        $storeEntityTransfer->setName($dataSet[static::KEY_STORE]);

        $priceProductStoreEntityTransfer = new SpyPriceProductStoreEntityTransfer();
        $priceProductStoreEntityTransfer
            ->setCurrency($currencyEntityTransfer)
            ->setStore($storeEntityTransfer)
            ->setNetPrice($dataSet[static::KEY_PRICE_NET])
            ->setGrossPrice($dataSet[static::KEY_PRICE_GROSS]);

        return $priceProductStoreEntityTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\SpyPriceTypeEntityTransfer
     */
    protected function importPriceType(DataSetInterface $dataSet): SpyPriceTypeEntityTransfer
    {
        $priceTypeTransfer = new SpyPriceTypeEntityTransfer();
        $priceTypeTransfer
            ->setName($dataSet[static::KEY_PRICE_TYPE])
            ->setPriceModeConfiguration(static::KEY_DEFAULT_PRICE_MODE_CONFIGURATION);

        $dataSet[static::PRICE_TYPE_TRANSFER] = $priceTypeTransfer;

        return $priceTypeTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\SpyProductAbstractEntityTransfer
     */
    protected function importProductAbstract(DataSetInterface $dataSet): SpyProductAbstractEntityTransfer
    {
        $productAbstractTransfer = new SpyProductAbstractEntityTransfer();
        $productAbstractTransfer->setSku($dataSet[static::KEY_ABSTRACT_SKU]);

        return $productAbstractTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Generated\Shared\Transfer\SpyProductEntityTransfer
     */
    protected function importProductConcrete(DataSetInterface $dataSet): SpyProductEntityTransfer
    {
        $productConcreteTransfer = new SpyProductEntityTransfer();
        $productConcreteTransfer->setSku($dataSet[static::KEY_CONCRETE_SKU]);

        return $productConcreteTransfer;
    }
}
