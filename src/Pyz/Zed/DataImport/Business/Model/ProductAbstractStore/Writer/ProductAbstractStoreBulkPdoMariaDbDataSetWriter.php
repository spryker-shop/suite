<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer;

use Pyz\Zed\DataImport\Business\Model\PropelMariaDbVersionConstraintTrait;
use Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface;
use Spryker\Zed\Propel\PropelConfig;

class ProductAbstractStoreBulkPdoMariaDbDataSetWriter extends AbstractProductAbstractStoreBulkDataSetWriter implements ApplicableDatabaseEngineAwareInterface
{
    use PropelMariaDbVersionConstraintTrait;

    /**
     * @return bool
     */
    public function isApplicable(): bool
    {
        return $this->dataImportConfig->getCurrentDatabaseEngine() === PropelConfig::DB_ENGINE_MYSQL
            && $this->checkIsMariaDBSupportsBulkImport($this->propelExecutor);
    }

    /**
     * @return void
     */
    protected function persistAbstractProductStoreEntities(): void
    {
        $rawAbstractSku = $this->dataFormatter->getCollectionDataByKey(static::$productAbstractStoreCollection, static::COLUMN_ABSTRACT_SKU);
        $abstractSku = $this->dataFormatter->formatStringList($rawAbstractSku);
        $rowsCount = count($rawAbstractSku);

        $storeName = $this->dataFormatter->formatStringList(
            $this->dataFormatter->getCollectionDataByKey(static::$productAbstractStoreCollection, static::COLUMN_STORE_NAME),
            $rowsCount,
        );

        $sql = $this->productAbstractStoreSql->createAbstractProductStoreSQL();
        $parameters = [
            $rowsCount,
            $abstractSku,
            $storeName,
        ];

        $this->propelExecutor->execute($sql, $parameters, false);
    }
}
