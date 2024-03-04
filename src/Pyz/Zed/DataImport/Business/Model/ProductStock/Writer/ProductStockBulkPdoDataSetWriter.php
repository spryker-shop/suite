<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock\Writer;

use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\DataImport\Business\Model\ApplicableDatabaseEngineAwareInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\Propel\PropelConfig;

class ProductStockBulkPdoDataSetWriter extends AbstractProductStockBulkDataSetWriter implements DataSetWriterInterface, ApplicableDatabaseEngineAwareInterface
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
    protected function persistStockEntities(): void
    {
        $names = $this->dataFormatter->getCollectionDataByKey(static::$stockCollection, static::COLUMN_NAME);
        $uniqueNames = array_unique($names);
        $name = $this->dataFormatter->formatPostgresArrayString($uniqueNames);

        $sql = $this->productStockSql->createStockSQL();
        $parameters = [
            $name,
        ];
        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @return void
     */
    protected function persistStockProductEntities(): void
    {
        $sku = $this->dataFormatter->formatPostgresArrayString(
            $this->dataFormatter->getCollectionDataByKey(static::$stockProductCollection, static::COLUMN_CONCRETE_SKU),
        );
        $stockName = $this->dataFormatter->formatPostgresArray(
            $this->dataFormatter->getCollectionDataByKey(static::$stockCollection, static::COLUMN_NAME),
        );
        $quantity = $this->dataFormatter->formatPostgresArray(
            $this->dataFormatter->getCollectionDataByKey(static::$stockProductCollection, static::COLUMN_QUANTITY),
        );
        $isNeverOutOfStock = $this->dataFormatter->formatPostgresArray(
            $this->dataFormatter->getCollectionDataByKey(static::$stockProductCollection, static::COLUMN_IS_NEVER_OUT_OF_STOCK),
        );

        $sql = $this->productStockSql->createStockProductSQL();
        $parameters = [
            $sku,
            $stockName,
            $quantity,
            $isNeverOutOfStock,
        ];
        $this->propelExecutor->execute($sql, $parameters);
    }

    /**
     * @return void
     */
    protected function persistAvailability(): void
    {
        $skus = $this->dataFormatter->getCollectionDataByKey(static::$stockProductCollection, static::COLUMN_CONCRETE_SKU);

        $concreteSkusToAbstractMap = $this->mapConcreteSkuToAbstractSku($skus);
        $reservations = $this->getReservationsBySkus($skus);

        foreach ($this->storeFacade->getAllStores() as $storeTransfer) {
            $this->updateAvailability($skus, $storeTransfer, $concreteSkusToAbstractMap, $reservations);
        }

        $this->updateBundleAvailability();
    }

    /**
     * @param array<string> $skus
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param array<string, string> $concreteSkusToAbstractMap
     * @param array<\Spryker\DecimalObject\Decimal> $reservations
     *
     * @return void
     */
    protected function updateAvailability(
        array $skus,
        StoreTransfer $storeTransfer,
        array $concreteSkusToAbstractMap,
        array $reservations,
    ): void {
        $stockProductsForStore = $this->getStockProductBySkusAndStore($skus, $storeTransfer);

        $concreteAvailabilityData = $this->prepareConcreteAvailabilityData($stockProductsForStore, $reservations);

        $abstractAvailabilityData = $this->prepareAbstractAvailabilityData($concreteAvailabilityData, $concreteSkusToAbstractMap);

        $abstractAvailabilityQueryParams = [
            $this->dataFormatter->formatPostgresArrayString(array_column($abstractAvailabilityData, static::KEY_SKU)),
            $this->dataFormatter->formatPostgresArray(array_column($abstractAvailabilityData, static::KEY_QUANTITY)),
            $this->dataFormatter->formatPostgresArray(array_fill(0, count($abstractAvailabilityData), $storeTransfer->getIdStore())),
        ];

        $availabilityAbstractIds = $this->propelExecutor->execute($this->productStockSql->createAbstractAvailabilitySQL(), $abstractAvailabilityQueryParams);
        $this->collectAvailabilityAbstractIds($availabilityAbstractIds);

        $availabilityQueryParams = [
            $this->dataFormatter->formatPostgresArrayString(array_column($concreteAvailabilityData, static::KEY_SKU)),
            $this->dataFormatter->formatPostgresArray(array_column($concreteAvailabilityData, static::KEY_QUANTITY)),
            $this->dataFormatter->formatPostgresArrayBoolean(array_column($concreteAvailabilityData, static::KEY_IS_NEVER_OUT_OF_STOCK)),
            $this->dataFormatter->formatPostgresArray(array_fill(0, count($concreteAvailabilityData), $storeTransfer->getIdStore())),
        ];

        $this->propelExecutor->execute($this->productStockSql->createAvailabilitySQL(), $availabilityQueryParams);
    }
}
