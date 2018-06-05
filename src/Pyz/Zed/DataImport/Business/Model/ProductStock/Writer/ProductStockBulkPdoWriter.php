<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductStock\Writer;

use Propel\Runtime\Propel;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataFormatter;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository;
use Pyz\Zed\DataImport\Business\Model\ProductStock\ProductStockHydratorStep;
use Spryker\Zed\Availability\Business\AvailabilityFacadeInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Business\Model\Writer\FlushInterface;
use Spryker\Zed\DataImport\Business\Model\Writer\WriterInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface;

class ProductStockBulkPdoWriter extends DataImporterPublisher implements WriterInterface, FlushInterface
{
    use DataFormatter;

    const BULK_SIZE = 100;

    protected static $stockCollection = [];

    protected static $stockProductCollection = [];

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository
     */
    protected $productRepository;

    /**
     * @var \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @var \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface
     */
    protected $productBundleFacade;

    /**
     * ProductStockBulkPdoWriter constructor.
     *
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface $eventFacade
     * @param \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface $availabilityFacade
     * @param \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface $productBundleFacade
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository $productRepository
     */
    public function __construct(
        DataImportToEventFacadeInterface $eventFacade,
        AvailabilityFacadeInterface $availabilityFacade,
        ProductBundleFacadeInterface $productBundleFacade,
        ProductRepository $productRepository
    ) {
        parent::__construct($eventFacade);
        $this->availabilityFacade = $availabilityFacade;
        $this->productBundleFacade = $productBundleFacade;
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet): void
    {
        $this->collectStock($dataSet);
        $this->collectStockProduct($dataSet);

        if (count(static::$stockProductCollection) >= static::BULK_SIZE) {
            $this->writeEntities();
            $this->availabilityFacade->updateAvailability($dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU]);

            if ($dataSet[ProductStockHydratorStep::KEY_IS_BUNDLE]) {
                $this->productBundleFacade->updateBundleAvailability($dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU]);
                $this->productBundleFacade->updateAffectedBundlesAvailability($dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU]);
            }
        }
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
        $this->persistStockEntities();
        $this->persistStockProductEntities();
        $this->triggerEvents();
        $this->flushMemory();
    }

    /**
     * @return void
     */
    protected function flushMemory(): void
    {
        static::$stockCollection = [];
        static::$stockProductCollection = [];
    }

    /**
     * @return void
     */
    protected function persistStockEntities(): void
    {
        $name = $this->formatPostgresArrayString(
            array_unique(
                array_column(static::$stockCollection, ProductStockHydratorStep::KEY_NAME)
            )
        );

        $sql = $this->createStockSQL();
        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $name,
        ]);
    }

    /**
     * @return void
     */
    protected function persistStockProductEntities(): void
    {
        $fkProduct = $this->formatPostgresArray(
            array_column(static::$stockProductCollection, ProductStockHydratorStep::KEY_FK_PRODUCT)
        );
        $stockName = $this->formatPostgresArray(
            array_column(static::$stockCollection, ProductStockHydratorStep::KEY_NAME)
        );
        $quantity = $this->formatPostgresArray(
            array_column(static::$stockProductCollection, ProductStockHydratorStep::KEY_QUANTITY)
        );
        $isNeverOutOfStock = $this->formatPostgresArray(
            array_column(static::$stockProductCollection, ProductStockHydratorStep::KEY_IS_NEVER_OUT_OF_STOCK)
        );

        $sql = $this->createStockProductSQL();
        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $fkProduct,
            $stockName,
            $quantity,
            $isNeverOutOfStock,
        ]);
    }

    /**
     * @return string
     */
    protected function createStockSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.name as inputName,
      id_stock as idStock,
      spy_stock.name as spyStockName
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS name
         ) input
    LEFT JOIN spy_stock ON spy_stock.name = input.name
),
    updated AS (
    UPDATE spy_stock
    SET
      name = records.inputName
    FROM records
    WHERE idStock is not null and spyStockName is null
    RETURNING idStock
  ),
    inserted AS(
    INSERT INTO spy_stock (
      id_stock,
      name
    ) (
      SELECT
        nextval('spy_stock_pk_seq'),
        inputName
      FROM records
      WHERE idStock is null AND inputName <> ''
    ) RETURNING id_stock
  )
SELECT updated.idStock FROM updated UNION ALL SELECT inserted.id_stock FROM inserted;";

        return $sql;
    }

    /**
     * @return string
     */
    protected function createStockProductSQL(): string
    {
        $sql = "WITH records AS (
    SELECT
      input.fk_product,
      input.stockName,
      input.quantity,
      input.is_never_out_of_stock,
      id_stock_product as idStockProduct,
      id_stock as fkStock
    FROM (
           SELECT
             unnest(? :: INTEGER []) AS fk_product,
             unnest(? :: VARCHAR []) AS stockName,
             unnest(? :: INTEGER []) AS quantity,
             unnest(? :: BOOLEAN []) AS is_never_out_of_stock
         ) input
      INNER JOIN spy_stock on spy_stock.name = stockName
      LEFT JOIN spy_stock_product ON spy_stock_product.fk_product = input.fk_product
                                     AND spy_stock_product.fk_stock = spy_stock.id_stock
),
    updated AS (
    UPDATE spy_stock_product
    SET
      fk_product = records.fk_product,
      fk_stock = records.fkStock,
      quantity = records.quantity,
      is_never_out_of_stock = records.is_never_out_of_stock
    FROM records
    WHERE spy_stock_product.fk_product = records.fk_product AND fk_stock = records.fkStock
    RETURNING idStockProduct
  ),
    inserted AS(
    INSERT INTO spy_stock_product (
      id_stock_product,
      fk_product,
      fk_stock,
      quantity,
      is_never_out_of_stock
    ) (
      SELECT
        nextval('spy_stock_product_pk_seq'),
        fk_product,
        fkStock,
        quantity,
        is_never_out_of_stock
      FROM records
      WHERE idStockProduct is null
    ) RETURNING id_stock_product
  )
SELECT updated.idStockProduct FROM updated UNION ALL SELECT inserted.id_stock_product FROM inserted;";

        return $sql;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectStock(DataSetInterface $dataSet): void
    {
        static::$stockCollection[] = $dataSet[ProductStockHydratorStep::STOCK_ENTITY_TRANSFER]->modifiedToArray();
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function collectStockProduct(DataSetInterface $dataSet): void
    {
        $sku = $dataSet[ProductStockHydratorStep::KEY_CONCRETE_SKU];
        $idProduct = $this->productRepository->getIdProductByConcreteSku($sku);
        $dataSet[ProductStockHydratorStep::STOCK_PRODUCT_ENTITY_TRANSFER]->setFkProduct($idProduct);
        static::$stockProductCollection[] = $dataSet[ProductStockHydratorStep::STOCK_PRODUCT_ENTITY_TRANSFER]->modifiedToArray();
    }
}
