<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer;

use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataFormatter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\ProductAbstractStoreHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;

class ProductAbstractStoreBulkPdoDataSetWriter extends DataImporterPublisher implements DataSetWriterInterface
{
    use DataFormatter;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSql
     */
    protected $productAbstractStoreSql;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    protected $propelExecutor;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     * @param \Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSqlInterface $productAbstractStoreSql
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     */
    public function __construct(
        DataImportToEventFacadeInterface $eventFacade,
        ProductAbstractStoreSqlInterface $productAbstractStoreSql,
        PropelExecutorInterface $propelExecutor
    ) {
        parent::__construct($eventFacade);
        $this->productAbstractStoreSql = $productAbstractStoreSql;
        $this->propelExecutor = $propelExecutor;
    }

    /**
     * @var array
     */
    protected static $productAbstractStoreCollection = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $this->collectProductAbstractStoreCollection($dataSet);

        if (count(static::$productAbstractStoreCollection) >= ProductAbstractStoreHydratorStep::BULK_SIZE) {
            $this->writeEntities();
        }
    }

    /**
     * @return void
     */
    protected function persistAbstractProductStoreEntities(): void
    {
        $abstractSku = $this->formatPostgresArrayString(
            $this->getCollectionDataByKey(static::$productAbstractStoreCollection, ProductAbstractStoreHydratorStep::KEY_PRODUCT_ABSTRACT_SKU)
        );
        $storeName = $this->formatPostgresArrayString(
            $this->getCollectionDataByKey(static::$productAbstractStoreCollection, ProductAbstractStoreHydratorStep::KEY_STORE_NAME)
        );

        $sql = $this->productAbstractStoreSql->createAbstractProductStoreSQL();
        $parameters = [
            $abstractSku,
            $storeName,
        ];

        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @return void
     */
    public function flush(): void
    {
        $this->writeEntities();
    }

    /**
     * @return void
     */
    protected function writeEntities(): void
    {
        $this->persistAbstractProductStoreEntities();
        $this->flushMemory();
    }

    /**
     * @return void
     */
    protected function flushMemory(): void
    {
        static::$productAbstractStoreCollection = [];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectProductAbstractStoreCollection(DataSetInterface $dataSet): void
    {
        static::$productAbstractStoreCollection[] = $dataSet[ProductAbstractStoreHydratorStep::PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFER]->modifiedToArray();
    }
}
