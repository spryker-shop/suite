<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer;

use Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface;
use Spryker\Zed\Propel\PropelConfig;

class ProductAbstractStoreBulkPdoDataSetWriter extends AbstractProductAbstractStoreBulkDataSetWriter implements ApplicableDatabaseEngineAwareInterface
{
    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return $this->dataImportConfig->getCurrentDatabaseEngine() === PropelConfig::DB_ENGINE_PGSQL;
    }

    /**
     * @return void
     */
    protected function persistAbstractProductStoreEntities(): void
    {
        $abstractSku = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$productAbstractStoreCollection, static::COLUMN_ABSTRACT_SKU)
        );
        $storeName = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$productAbstractStoreCollection, static::COLUMN_STORE_NAME)
        );

        $sql = $this->productAbstractStoreSql->createAbstractProductStoreSQL();
        $parameters = [
            $abstractSku,
            $storeName,
        ];

        $this->propelExecutor->execute($sql, $parameters);
    }
}
