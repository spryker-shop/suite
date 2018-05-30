<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer;

use Propel\Runtime\Propel;
use Pyz\Zed\DataImport\Business\Model\AbstractBulkPdoWriter\AbstractBulkPdoWriterTrait;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\ProductAbstractStoreHydratorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Business\Model\Writer\FlushInterface;
use Spryker\Zed\DataImport\Business\Model\Writer\WriterInterface;

class ProductAbstractStoreBulkPdoWriter extends DataImporterPublisher implements WriterInterface, FlushInterface
{
    use AbstractBulkPdoWriterTrait;

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
        $abstractSku = $this->formatPostgresArrayString(array_column(static::$productAbstractStoreCollection, ProductAbstractStoreHydratorStep::KEY_PRODUCT_ABSTRACT_SKU));
        $storeName = $this->formatPostgresArrayString(array_column(static::$productAbstractStoreCollection, ProductAbstractStoreHydratorStep::KEY_STORE_NAME));

        $sql = $this->createAbstractProductStoreSQL();

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $abstractSku,
            $storeName,
        ]);
    }

    /**
     * @return string
     */
    protected function createAbstractProductStoreSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.abstract_sku,
      input.store_name,
      id_product_abstract,
      id_store
    FROM (
           SELECT
             unnest(?::VARCHAR[]) AS abstract_sku,
             unnest(?::VARCHAR[]) AS store_name
         ) input
         INNER JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
         INNER JOIN spy_store ON spy_store.name = input.store_name
),
    inserted AS(
        INSERT INTO spy_product_abstract_store (
          id_product_abstract_store,
          fk_product_abstract,
          fk_store
        ) (
          SELECT
            nextval('id_product_abstract_store_pk_seq'),
            id_product_abstract,
            id_store
        FROM records
    ) ON CONFLICT DO NOTHING
)
SELECT 1;";

        return $sql;
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
