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
}
