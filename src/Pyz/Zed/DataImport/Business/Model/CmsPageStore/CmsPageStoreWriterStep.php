<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\CmsPageStore;

use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\Cms\Persistence\SpyCmsPageStoreQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsPageStoreWriterStep implements DataImportStepInterface
{
    public const BULK_SIZE = 100;
    protected const KEY_PAGE_NAME = 'page_key';
    protected const KEY_STORE_NAME = 'store_name';

    /**
     * @var int[] Keys are CMS Page keys, values are CMS Page IDs.
     */
    protected static $idCmsPageBuffer = [];

    /**
     * @var int[] Keys are store names, values are store ids.
     */
    protected static $idStoreBuffer = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        (new SpyCmsPageStoreQuery())
            ->filterByFkCmsPage($this->getIdCmsPageByPageKey($dataSet[static::KEY_PAGE_NAME]))
            ->filterByFkStore($this->getIdStoreByName($dataSet[static::KEY_STORE_NAME]))
            ->findOneOrCreate()
            ->save();
    }

    /**
     * @param string $cmsPageKey
     *
     * @return int
     */
    protected function getIdCmsPageByPageKey(string $cmsPageKey): int
    {
        if (!isset(static::$idCmsPageBuffer[$cmsPageKey])) {
            static::$idCmsPageBuffer[$cmsPageKey] =
                SpyCmsPageQuery::create()->findOneByPageKey($cmsPageKey)->getIdCmsPage();
        }
        return static::$idCmsPageBuffer[$cmsPageKey];
    }

    /**
     * @param string $storeName
     *
     * @return int
     */
    protected function getIdStoreByName(string $storeName): int
    {
        if (!isset(static::$idStoreBuffer[$storeName])) {
            static::$idStoreBuffer[$storeName] =
                SpyStoreQuery::create()->findOneByName($storeName)->getIdStore();
        }
        return static::$idStoreBuffer[$storeName];
    }
}
