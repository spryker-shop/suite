<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer;

use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataFormatter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\ProductAbstractStoreHydratorStep;
use Pyz\Zed\DataImport\Business\Model\PropelExecutor;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Business\Model\Writer\FlushInterface;
use Spryker\Zed\DataImport\Business\Model\Writer\WriterInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;

class ProductAbstractStoreBulkPdoWriter extends DataImporterPublisher implements WriterInterface, FlushInterface
{
    use DataFormatter;

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\ProductAbstractStoreSql
     */
    protected $productAbstractStoreSql;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     * @param \Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\ProductAbstractStoreSql $productAbstractStoreSql
     */
    public function __construct(
        DataImportToEventFacadeInterface $eventFacade,
        ProductAbstractStoreSql $productAbstractStoreSql
    ) {
        parent::__construct($eventFacade);
        $this->productAbstractStoreSql = $productAbstractStoreSql;
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
            array_column(static::$productAbstractStoreCollection, ProductAbstractStoreHydratorStep::KEY_PRODUCT_ABSTRACT_SKU)
        );
        $storeName = $this->formatPostgresArrayString(
            array_column(static::$productAbstractStoreCollection, ProductAbstractStoreHydratorStep::KEY_STORE_NAME)
        );

        $sql = $this->productAbstractStoreSql->createAbstractProductStoreSQL();
        $parameters = [
            $abstractSku,
            $storeName,
        ];

        PropelExecutor::execute($sql, $parameters);
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
        $this->triggerEvents();
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
