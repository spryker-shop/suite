<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
     * @param DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductAbstractDataSet(DataSetInterface $dataSet)
    {
        $this->getFactory()->createProductAbstractPropelWriter()->write($dataSet);
    }

    /**
     * @param DataSetInterface $dataSet
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
    public function writeProductConcreteDataSet(DataSetInterface $dataSet)
    {
        $this->getFactory()->createProductConcretePropelWriter()->write($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductConcretePdoDataSet(DataSetInterface $dataSet)
    {
        $this->getFactory()->createProductConcreteBulkPdoWriter()->write($dataSet);
    }

    /**
     * @return void
     */
    public function flushProductConcreteDataImporter()
    {
        $this->getFactory()->createProductConcretePropelWriter()->flush();
    }

    /**
     * @return void
     */
    public function flushProductConcretePdoDataImporter()
    {
        $this->getFactory()->createProductConcreteBulkPdoWriter()->flush();
    }
}
