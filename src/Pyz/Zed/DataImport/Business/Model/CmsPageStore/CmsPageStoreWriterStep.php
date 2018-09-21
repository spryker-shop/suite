<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\CmsPageStore;

use Orm\Zed\Cms\Persistence\SpyCmsPageStoreQuery;
use Pyz\Zed\DataImport\Business\Model\CmsPageStore\DataSet\CmsPageStoreDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsPageStoreWriterStep implements DataImportStepInterface
{
    public const BULK_SIZE = 100;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        (new SpyCmsPageStoreQuery())
            ->filterByFkCmsPage($dataSet[CmsPageStoreDataSet::ID_CMS_PAGE])
            ->filterByFkStore($dataSet[CmsPageStoreDataSet::ID_STORE])
            ->findOneOrCreate()
            ->save();
    }
}
