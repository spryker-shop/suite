<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer;

use Propel\Runtime\Propel;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Business\Model\Publisher\DataImporterPublisher;
use Spryker\Zed\DataImport\Business\Model\Writer\FlushInterface;
use Spryker\Zed\DataImport\Business\Model\Writer\WriterInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;

class ProductAbstractBulkPdoWriter extends DataImporterPublisher implements WriterInterface, FlushInterface
{
    const BULK_SIZE = 100;

    /**
     * @var array
     */
    protected static $productAbstractCollection = [];

    /**
     * @var array
     */
    protected static $productAbstractLocalizedAttributesCollection = [];

    /**
     * //TODO refactor this
     *
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function write(DataSetInterface $dataSet)
    {
        static::$productAbstractCollection[] = $dataSet[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_TRANSFER]->modifiedToArray();
        foreach ($dataSet[ProductAbstractHydratorStep::PRODUCT_ABSTRACT_LOCALIZED_TRANSFER] as $productAbstractLocalizedTransfer) {
            $localizedAttributeArray = $productAbstractLocalizedTransfer['localizedAttributeTransfer']->modifiedToArray();
            $localizedAttributeArray['abstract_sku'] = $productAbstractLocalizedTransfer['abstract_sku'];
            $localizedAttributeArray['meta_description'] = str_replace('"','', $localizedAttributeArray['meta_description']);;
            $localizedAttributeArray['description'] = str_replace('"','', $localizedAttributeArray['description']);
            static::$productAbstractLocalizedAttributesCollection[] = $localizedAttributeArray;
        }

        if (count(static::$productAbstractCollection) >= static::BULK_SIZE) {
            $this->writeEntities();
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->writeEntities();
    }

    /**
     * @return void
     */
    protected function writeEntities()
    {
        $this->persistAbstractProductEntities();
        $this->persistAbstractProductLocalizedAttributesEntities();
        $this->triggerEvents();
        $this->flushMemory();
    }

    /**
     * @return void
     */
    protected function persistAbstractProductEntities()
    {
        //todo refactor this
        $abstractSkus = $this->formatPostgresArrayString(array_column(static::$productAbstractCollection, 'sku'));
        $attributes = $this->formatPostgresArrayFromJson(array_column(static::$productAbstractCollection, 'attributes'));
        $fkTaxSets = $this->formatPostgresArray(array_column(static::$productAbstractCollection, 'fk_tax_set'));
        $colorCode = $this->formatPostgresArrayString(array_column(static::$productAbstractCollection, 'color_code'));
        $newFrom = $this->formatPostgresArrayString(array_column(static::$productAbstractCollection, 'new_from'));
        $newTo = $this->formatPostgresArrayString(array_column(static::$productAbstractCollection, 'new_to'));

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
        foreach ($result as $columns) {
            $this->addEvent(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $columns['id_product_abstract']);
        }
    }

    /**
     * @return void
     */
    protected function persistAbstractProductLocalizedAttributesEntities()
    {
        $abstractSkus = $this->formatPostgresArrayString(array_column(static::$productAbstractLocalizedAttributesCollection, 'abstract_sku'));
        $idLocale = $this->formatPostgresArray(array_column(static::$productAbstractLocalizedAttributesCollection, 'fk_locale'));
        $name = $this->formatPostgresArrayString(array_column(static::$productAbstractLocalizedAttributesCollection, 'name'));
        $description = $this->formatPostgresArrayString(array_column(static::$productAbstractLocalizedAttributesCollection, 'description'));
        $metaTitle = $this->formatPostgresArrayString(array_column(static::$productAbstractLocalizedAttributesCollection, 'meta_title'));
        $metaDescription = $this->formatPostgresArrayString(array_column(static::$productAbstractLocalizedAttributesCollection, 'meta_description'));
        $metaKeywords = $this->formatPostgresArrayString(array_column(static::$productAbstractLocalizedAttributesCollection, 'meta_keywords'));
        $attributes = $this->formatPostgresArrayFromJson(array_column(static::$productAbstractLocalizedAttributesCollection, 'attributes'));

        $sql = $this->createAbstractProductLocalizedAttributesSQL();

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute([
            $abstractSkus,
            $name,
            $description,
            $metaTitle,
            $metaDescription,
            $metaKeywords,
            $idLocale,
            $attributes
        ]);
    }

    /**
     * @return void
     */
    protected function flushMemory()
    {
        static::$productAbstractCollection = [];
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
     * @return string
     */
    protected function createAbstractProductLocalizedAttributesSQL()
    {
        $sql = "WITH records AS (
    SELECT
      input.abstract_sku,
      input.name,
      input.description,
      input.metaTitle,
      input.metaDescription,
      input.metaKeywords,
      input.idLocale,
      input.attributes,
      id_product_abstract,
      id_abstract_attributes
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS abstract_sku,
             unnest(? :: VARCHAR []) AS name,
             unnest(? :: TEXT []) AS description,
             unnest(? :: VARCHAR []) AS metaTitle,
             unnest(? :: TEXT []) AS metaDescription,
             unnest(? :: VARCHAR []) AS metaKeywords,
             unnest(? :: INTEGER []) AS idLocale,
             json_array_elements(?) AS attributes
         ) input
      INNER JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
      LEFT JOIN spy_product_abstract_localized_attributes ON (spy_product_abstract_localized_attributes.fk_product_abstract = id_product_abstract and spy_product_abstract_localized_attributes.fk_locale = input.idLocale)
),
    updated AS (
    UPDATE spy_product_abstract_localized_attributes
    SET
      updated_at = now(),
      name = records.name,
      description = records.description,
      meta_title = records.metaTitle,
      meta_description = records.metaDescription,
      meta_keywords = records.metaKeywords,
      attributes = records.attributes
    FROM records
    WHERE records.id_product_abstract = spy_product_abstract_localized_attributes.fk_product_abstract and spy_product_abstract_localized_attributes.fk_locale = records.idLocale
  ),
    inserted AS(
    INSERT INTO spy_product_abstract_localized_attributes (
      id_abstract_attributes,
      name,
      description,
      meta_title,
      meta_description,
      meta_keywords,
      attributes,
      fk_locale,
      fk_product_abstract
    ) (
      SELECT
        nextval('spy_product_abstract_localized_attributes_pk_seq'),
        name,
        description,
        metaTitle,
        metaDescription,
        metaKeywords,
        attributes,
        idLocale,
        id_product_abstract
      FROM records
      WHERE id_abstract_attributes is null
    )
  )
SELECT 1;
";

        return $sql;
    }

    /**
     * //TODO move this to abstract class or trait
     *
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
     * //TODO move this to abstract class or trait
     *
     * @param array $values
     *
     * @return string
     */
    protected function formatPostgresArrayString(array $values)
    {
        return sprintf(
            '{"%s"}',
            join('","', $values)
        );
    }

    /**
     * //TODO move this to abstract class or trait
     *
     * @param array $values
     *
     * @return string
     */
    protected function formatPostgresArrayFromJson(array $values)
    {
        return sprintf(
            '[%s]',
            join(',', $values)
        );
    }
}
