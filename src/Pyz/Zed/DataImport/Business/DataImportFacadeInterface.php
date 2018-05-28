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
     * @return void
     */
    public function flushProductAbstractPdoDataImporter();

    /**
     * @return void
     */
    public function flushProductAbstractDataImporter();

    /**
     * @return void
     */
    public function flushProductImageDataImporter();

    /**
     * @return void
     */
    public function flushProductImagePdoDataImporter();
}
