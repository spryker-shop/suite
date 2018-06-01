<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

/**
 * @method \Pyz\Zed\DataImport\Business\DataImportBusinessFactory getFactory()
 */
interface DataImportFacadeInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductAbstractDataSet(DataSetInterface $dataSet);

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductAbstractPdoDataSet(DataSetInterface $dataSet);

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductImageDataSet(DataSetInterface $dataSet);

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductImagePdoDataSet(DataSetInterface $dataSet);

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductStockDataSet(DataSetInterface $dataSet);

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductStockPdoDataSet(DataSetInterface $dataSet);

    /**
     * @return void
     */
    public function flushProductAbstractPdoDataImporter();

    /**
     * @return void
     */
    public function flushProductAbstractDataImporter();

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductConcreteDataSet(DataSetInterface $dataSet);

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductConcretePdoDataSet(DataSetInterface $dataSet);

    /**
     * @return void
     */
    public function flushProductConcretePdoDataImporter();

    /**
     * @return void
     */
    public function flushProductConcreteDataImporter();

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductAbstractStoreDataSet(DataSetInterface $dataSet): void;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductAbstractStorePdoDataSet(DataSetInterface $dataSet): void;

    /**
     * @return void
     */
    public function flushProductAbstractStoreDataImporter(): void;

    /**
     * @return void
     */
    public function flushProductAbstractStorePdoDataImporter(): void;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductPriceDataSet(DataSetInterface $dataSet): void;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductPricePdoDataSet(DataSetInterface $dataSet): void;

    /**
     * @return void
     */
    public function flushProductPriceDataImporter(): void;

    /**
     * @return void
     */
    public function flushProductPricePdoDataImporter(): void;

    /**
     * @return void
     */
    public function flushProductImageDataImporter();

    /**
     * @return void
     */
    public function flushProductImagePdoDataImporter();

    /**
     * @return void
     */
    public function flushProductStockDataImporter();

    /**
     * @return void
     */
    public function flushProductStockPdoDataImporter();
}
