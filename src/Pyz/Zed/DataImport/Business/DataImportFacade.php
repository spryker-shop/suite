<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportFacade as SprykerDataImportFacade;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

/**
 * @method \Pyz\Zed\DataImport\Business\DataImportBusinessFactory getFactory()
 */
class DataImportFacade extends SprykerDataImportFacade implements DataImportFacadeInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductAbstractDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductAbstractPropelWriter()->write($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductAbstractPdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductAbstractBulkPdoWriter()->write($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductStockDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductStockPropelWriter()->write($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductStockPdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductStockBulkPdoWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushProductAbstractDataImporter(): void
    {
        $this->getFactory()->createProductAbstractPropelWriter()->flush();
    }

    /**
     * @return void
     */
    public function flushProductAbstractPdoDataImporter(): void
    {
        $this->getFactory()->createProductAbstractBulkPdoWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductImageDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductImagePropelWriter()->write($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductImagePdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductImageBulkPdoWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushProductImageDataImporter(): void
    {
        $this->getFactory()->createProductImagePropelWriter()->flush();
    }

    /**
     * @return void
     */
    public function flushProductImagePdoDataImporter(): void
    {
        $this->getFactory()->createProductImageBulkPdoWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductAbstractStoreDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductAbstractStorePropelWriter()->write($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductAbstractStorePdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductAbstractStoreBulkPdoWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushProductAbstractStoreDataImporter(): void
    {
        $this->getFactory()->createProductAbstractStorePropelWriter()->flush();
    }

    /**
     * @return void
     */
    public function flushProductAbstractStorePdoDataImporter(): void
    {
        $this->getFactory()->createProductAbstractStoreBulkPdoWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductPriceDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductPricePropelWriter()->write($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductPricePdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductPriceBulkPdoWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushProductPriceDataImporter(): void
    {
        $this->getFactory()->createProductPricePropelWriter()->flush();
    }

    /**
     * @return void
     */
    public function flushProductPricePdoDataImporter(): void
    {
        $this->getFactory()->createProductPriceBulkPdoWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductConcreteDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductConcretePropelWriter()->write($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductConcretePdoDataSet(DataSetInterface $dataSet): void
    {
        $this->getFactory()->createProductConcreteBulkPdoWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushProductConcreteDataImporter(): void
    {
        $this->getFactory()->createProductConcretePropelWriter()->flush();
    }

    /**
     * @return void
     */
    public function flushProductConcretePdoDataImporter(): void
    {
        $this->getFactory()->createProductConcreteBulkPdoWriter()->flush();
    }

    /**
     * @return void
     */
    public function flushProductStockDataImporter(): void
    {
        $this->getFactory()->createProductStockPropelWriter()->flush();
    }

    /**
     * @return void
     */
    public function flushProductStockPdoDataImporter(): void
    {
        $this->getFactory()->createProductStockBulkPdoWriter()->flush();
    }
}
