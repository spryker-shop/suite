<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\DataImport\Business\Model\ProductGroup;

use Orm\Zed\ProductGroup\Persistence\SpyProductAbstractGroupQuery;
use Orm\Zed\ProductGroup\Persistence\SpyProductGroupQuery;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductGroup\Dependency\ProductGroupEvents;

class ProductGroupWriter extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @var int
     */
    public const BULK_SIZE = 100;

    /**
     * @var string
     */
    public const COLUMN_ABSTRACT_SKU = 'abstract_sku';

    /**
     * @var string
     */
    public const COLUMN_PRODUCT_GROUP_KEY = 'group_key';

    /**
     * @var string
     */
    public const COLUMN_POSITION = 'position';

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository
     */
    protected $productRepository;

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productGroupEntity = SpyProductGroupQuery::create()
            ->filterByProductGroupKey($dataSet[static::COLUMN_PRODUCT_GROUP_KEY])
            ->findOneOrCreate();

        $productGroupEntity->save();

        $idProductAbstract = $this->productRepository->getIdProductAbstractByAbstractSku($dataSet[static::COLUMN_ABSTRACT_SKU]);

        $productAbstractGroup = SpyProductAbstractGroupQuery::create()
            ->filterByFkProductAbstract($idProductAbstract)
            ->filterByFkProductGroup($productGroupEntity->getIdProductGroup())
            ->findOneOrCreate();

        $productAbstractGroup
            ->setPosition($dataSet[static::COLUMN_POSITION])
            ->save();

        $this->addPublishEvents(ProductGroupEvents::PRODUCT_GROUP_PUBLISH, $productAbstractGroup->getFkProductAbstract());
    }
}
