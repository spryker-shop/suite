<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\CmsPageStore\Step;

use Orm\Zed\Cms\Persistence\Map\SpyCmsPageTableMap;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Pyz\Zed\DataImport\Business\Model\CmsPageStore\DataSet\CmsPageStoreDataSet;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsPageKeyToIdCmsPageStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idCmsPageCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $cmsPageKey = $dataSet[CmsPageStoreDataSet::KEY_PAGE_NAME];
        if (!isset($this->idCmsPageCache[$cmsPageKey])) {
            $cmsPageQuery = new SpyCmsPageQuery();
            $idCmsPage = $cmsPageQuery
                ->select(SpyCmsPageTableMap::COL_ID_CMS_PAGE)
                ->findOneByPageKey($cmsPageKey);

            if (!$idCmsPage) {
                throw new EntityNotFoundException(sprintf('Could not find cms page by page key "%s"', $cmsPageKey));
            }

            $this->idCmsPageCache[$cmsPageKey] = $idCmsPage;
        }

        $dataSet[CmsPageStoreDataSet::ID_CMS_PAGE] = $this->idCmsPageCache[$cmsPageKey];
    }
}
