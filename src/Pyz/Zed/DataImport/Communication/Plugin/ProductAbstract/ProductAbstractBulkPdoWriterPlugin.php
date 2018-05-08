<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Communication\Plugin\ProductAbstract;

use Propel\Runtime\Propel;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportFlushPluginInterface;
use Spryker\Zed\DataImport\Dependency\Plugin\DataImportWriterPluginInterface;

class ProductAbstractBulkPdoWriterPlugin implements DataImportWriterPluginInterface, DataImportFlushPluginInterface
{
    const BULK_SIZE = 100;

    /**
     * @var array
     */
    protected static $productAbstractCollection = [];

    /**
     * @var array
     */
    protected static $productAbstractLocalizedAttributes = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet)
    {
        static::$productAbstractCollection[] = $dataSet[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_TRANSFER]->modifiedToArray();

        if (count(static::$productAbstractCollection) >= static::BULK_SIZE) {
            $this->persistAbstractProductEntities();
            echo 'Write All dataSets in bulk operation: ' . count(static::$productAbstractCollection) . PHP_EOL;
            static::$productAbstractCollection = [];
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        echo 'Flush the rest dataSets in bulk operation: ' . count(static::$productAbstractCollection) . PHP_EOL;
    }

    /**
     * @return void
     */
    protected function persistAbstractProductEntities()
    {
        $abstractSkus = $this->formatPostgresArray(array_column(static::$productAbstractCollection, 'sku'));
        $attributes = $this->formatPostgresArrayFromJson(array_column(static::$productAbstractCollection, 'attributes'));
        $fkTaxSets = $this->formatPostgresArray(array_column(static::$productAbstractCollection, 'fk_tax_set'));
        $colorCode = $this->formatPostgresArray(array_column(static::$productAbstractCollection, 'color_code'));
        $newFrom = $this->formatPostgresArray(array_column(static::$productAbstractCollection, 'new_from'));
        $newTo = $this->formatPostgresArray(array_column(static::$productAbstractCollection, 'new_to'));

        $sql = $this->createAbstractProductSQL();

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $abstractSkus,
            $attributes,
            $fkTaxSets,
            $colorCode,
            $newFrom,
            $newTo
        ]);

        $result = $stmt->fetchAll();
//        foreach ($result as $columns) {
//            $this->addPublishEvents(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $columns['id_product_abstract']);
//        }
    }

    /**
     * @return string
     */
    protected function createAbstractProductSQL()
    {
        $sql = "WITH records AS (
    SELECT
      input.abstract_sku,
      input.attributes,
      input.fkTaxSet,
      input.colorCode,
      input.newFrom,
      input.newTo,
      id_product_abstract as idProductAbstract
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS abstract_sku,
             json_array_elements(?) AS attributes,
             unnest(?::INTEGER[]) AS fkTaxSet,
             unnest(?::VARCHAR[]) AS colorCode,
             unnest(?::TIMESTAMP[]) AS newFrom,
             unnest(?::TIMESTAMP[]) AS newTo
         ) input    
      LEFT JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
),
    updated AS (
    UPDATE spy_product_abstract
    SET
      sku = records.abstract_sku,
      attributes = records.attributes,
      updated_at = now(),
      fk_tax_set = records.fkTaxSet,
      color_code = records.colorCode,
      new_from = records.newFrom,
      new_to = records.newTo
    FROM records
    WHERE records.abstract_sku = spy_product_abstract.sku
    RETURNING id_product_abstract,sku
  ),
    inserted AS(
    INSERT INTO spy_product_abstract (
      id_product_abstract,
      sku,
      attributes,
      fk_tax_set,
      color_code,
      new_from,
      new_to
    ) (
      SELECT
        nextval('spy_product_abstract_pk_seq'),
        abstract_sku,
        attributes,
        fkTaxSet,
        colorCode,
        newFrom,
        newTo
    FROM records
    WHERE idProductAbstract is null
  ) RETURNING id_product_abstract,sku
)
SELECT updated.id_product_abstract,sku FROM updated UNION ALL SELECT inserted.id_product_abstract,sku FROM inserted;";

        return $sql;
    }

    /**
     * @param array $values
     *
     * @return string
     */
    protected function formatPostgresArray(array $values)
    {
        return sprintf(
            '{%s}',
            join(',', $values)
        );
    }

    /**
     * @param array $values
     *
     * @return string
     */
    protected function formatPostgresArrayString(array $values)
    {
        return sprintf(
            '{"%s"}',
            join(',', $values)
        );
    }

    /**
     * @param array $values
     *
     * @return string
     */
    protected function formatPostgresArrayFromJson(array $values)
    {
        $values = array_map(function ($element) {
            return json_encode($element);
        }, $values);

        return sprintf(
            '[%s]',
            join(',', $values)
        );
    }
}
