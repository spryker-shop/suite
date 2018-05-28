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
    public function writeProductAbstractDataSet(DataSetInterface $dataSet)
    {
        $this->getFactory()->createProductAbstractPropelWriter()->write($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductAbstractPdoDataSet(DataSetInterface $dataSet)
    {
        $this->getFactory()->createProductAbstractBulkPdoWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushProductAbstractDataImporter()
    {
        $this->getFactory()->createProductAbstractPropelWriter()->flush();
    }

    /**
     * @return void
     */
    public function flushProductAbstractPdoDataImporter()
    {
        $this->getFactory()->createProductAbstractBulkPdoWriter()->flush();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductImageDataSet(DataSetInterface $dataSet)
    {
        $this->getFactory()->createProductImagePropelWriter()->write($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductImagePdoDataSet(DataSetInterface $dataSet)
    {
        $this->getFactory()->createProductImageBulkPdoWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushProductImageDataImporter()
    {
        $this->getFactory()->createProductImagePropelWriter()->flush();
    }

    /**
     * @return void
     */
    public function flushProductImagePdoDataImporter()
    {
        $this->getFactory()->createProductImageBulkPdoWriter()->flush();
    }
}
