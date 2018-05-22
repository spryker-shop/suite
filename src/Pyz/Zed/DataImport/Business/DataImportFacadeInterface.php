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
     * @param DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductAbstractDataSet(DataSetInterface $dataSet);

    /**
     * @param DataSetInterface $dataSet
     *
     * @return void
     */
    public function writeProductAbstractPdoDataSet(DataSetInterface $dataSet);

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
}
