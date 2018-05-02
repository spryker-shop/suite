<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductAbstract;

use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributesQuery;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductCategory\Persistence\SpyProductCategoryQuery;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Propel;
use Pyz\Zed\DataImport\Business\Model\Product\ProductLocalizedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;
use Spryker\Zed\ProductCategory\Dependency\ProductCategoryEvents;
use Spryker\Zed\Url\Dependency\UrlEvents;

/**
 */
class ProductAbstractWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    const BULK_SIZE = 100000;

    const KEY_ABSTRACT_SKU = 'abstract_sku';
    const KEY_COLOR_CODE = 'color_code';
    const KEY_ID_TAX_SET = 'idTaxSet';
    const KEY_ATTRIBUTES = 'attributes';
    const KEY_NAME = 'name';
    const KEY_URL = 'url';
    const KEY_DESCRIPTION = 'description';
    const KEY_META_TITLE = 'meta_title';
    const KEY_META_DESCRIPTION = 'meta_description';
    const KEY_META_KEYWORDS = 'meta_keywords';
    const KEY_TAX_SET_NAME = 'tax_set_name';
    const KEY_CATEGORY_KEY = 'category_key';
    const KEY_CATEGORY_KEYS = 'categoryKeys';
    const KEY_CATEGORY_PRODUCT_ORDER = 'category_product_order';
    const KEY_LOCALES = 'locales';
    const KEY_NEW_FROM = 'new_from';
    const KEY_NEW_TO = 'new_to';

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository
     */
    protected $productRepository;

    /**
     * @var int
     */
    protected $linesCount;

    protected static $objectCollection = [];
    protected static $objectCollectionLocalizedAttributes = [];
    protected static $objectCollectionProductCategory = [];
    protected static $objectCollectionProductUrl = [];
    protected static $lineNumber = 0;

    protected static $affectedProductAbstract = [];

    /**
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository $productRepository
     * @param int $lineCount
     */
    public function __construct(ProductRepository $productRepository, $lineCount)
    {
        $this->productRepository = $productRepository;
        $this->linesCount = $lineCount;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $this->importBulkProductAbstract($dataSet);
        $this->importBulkProductAbstractLocalizedAttributes($dataSet, $dataSet[static::KEY_ABSTRACT_SKU]);
        $this->importBulkProductCategories($dataSet, $dataSet[static::KEY_ABSTRACT_SKU]);
        $this->importBulkProductUrls($dataSet, $dataSet[static::KEY_ABSTRACT_SKU]);
        static::$lineNumber++;

        if (count(static::$objectCollection) >= static::BULK_SIZE || static::$lineNumber === $this->linesCount) {
            $bulkSize = count(static::$objectCollection);
            dump('Importing of chunk: ' . $bulkSize);
            $this->persistAbstractProductEntities();
            $this->persistAbstractProductLocalizedAttributesEntities();
            $this->persistAbstractProductCategoryEntities();
            $this->persistAbstractProductUrlEntities();
            dump(' --------- || Lines imported : '. static::$lineNumber);

            static::$objectCollection = [];
            static::$objectCollectionLocalizedAttributes = [];
            static::$objectCollectionProductCategory = [];
            static::$objectCollectionProductUrl = [];
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function importBulkProductAbstract(DataSetInterface $dataSet)
    {
        $productAbstract = [
            'sku' => $dataSet[static::KEY_ABSTRACT_SKU],
            'attributes' => json_encode($dataSet[static::KEY_ATTRIBUTES]),
            'fkTaxSet' => $dataSet[static::KEY_ID_TAX_SET],
            'colorCode' => $dataSet[static::KEY_COLOR_CODE],
            'newFrom' => $dataSet[static::KEY_NEW_FROM],
            'newTo' => $dataSet[static::KEY_NEW_TO]
        ];

        static::$objectCollection[$dataSet[static::KEY_ABSTRACT_SKU]] = $productAbstract;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param $abstractSku
     *
     * @return void
     *
     */
    protected function importBulkProductAbstractLocalizedAttributes(DataSetInterface $dataSet, $abstractSku)
    {
        foreach ($dataSet[ProductLocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES] as $idLocale => $localizedAttributes) {
            $productAbstractLocalizedAttributes = [
                'abstract_sku' => $abstractSku,
                'idLocale' => $idLocale,
                'name' => $localizedAttributes[static::KEY_NAME],
                'description' => $localizedAttributes[static::KEY_DESCRIPTION],
                'metaTitle' => $localizedAttributes[static::KEY_META_TITLE],
                'metaDescription' => $localizedAttributes[static::KEY_META_DESCRIPTION],
                'metaKeywords' => $localizedAttributes[static::KEY_META_KEYWORDS],
                'attributes' => json_encode($localizedAttributes[static::KEY_ATTRIBUTES])
            ];
            static::$objectCollectionLocalizedAttributes[$abstractSku . '-' . $idLocale] = $productAbstractLocalizedAttributes;
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $abstractSku
     *
     * @throws DataKeyNotFoundInDataSetException
     */
    protected function importBulkProductCategories(DataSetInterface $dataSet, $abstractSku)
    {
        $categoryKeys = $this->getCategoryKeys($dataSet[static::KEY_CATEGORY_KEY]);
        $categoryProductOrder = $this->getCategoryProductOrder($dataSet[static::KEY_CATEGORY_PRODUCT_ORDER]);

        foreach ($categoryKeys as $index => $categoryKey) {
            if (!isset($dataSet[static::KEY_CATEGORY_KEYS][$categoryKey])) {
                throw new DataKeyNotFoundInDataSetException(sprintf(
                    'The category with key "%s" was not found in categoryKeys. Maybe there is a typo. Given Categories: "%s"',
                    $categoryKey,
                    implode(array_values($dataSet[static::KEY_CATEGORY_KEYS]))
                ));
            }
            $productOrder = null;
            if (count($categoryProductOrder) > 0 && isset($categoryProductOrder[$index])) {
                $productOrder = $categoryProductOrder[$index];
            }

            $productCategory = [
                'abstract_sku' => $abstractSku,
                'productOrder' => $productOrder,
                'fkCategory' => $dataSet[static::KEY_CATEGORY_KEYS][$categoryKey]
            ];

            static::$objectCollectionProductCategory[$abstractSku] = $productCategory;
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $abstractSku
     *
     * @return void
     */
    protected function importBulkProductUrls(DataSetInterface $dataSet, $abstractSku)
    {
        foreach ($dataSet[ProductLocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES] as $idLocale => $localizedAttributes) {
            $abstractProductUrl = $localizedAttributes[static::KEY_URL];

            $productAbstractUrls = [
                'abstract_sku' => $abstractSku,
                'idLocale' => $idLocale,
                'url' => $abstractProductUrl
            ];

            static::$objectCollectionProductUrl[$abstractSku . '-' . $idLocale] = $productAbstractUrls;
        }
    }

    /**
     * @return void
     */
    protected function persistAbstractProductEntities()
    {
        $abstractSkus = $this->formatPostgresArray(array_column(static::$objectCollection, 'sku'));
        $attributes = $this->formatPostgresArrayFromJson(array_column(static::$objectCollection, 'attributes'));
        $fkTaxSets = $this->formatPostgresArray(array_column(static::$objectCollection, 'fkTaxSet'));
        $colorCode = $this->formatPostgresArrayString(array_column(static::$objectCollection, 'colorCode'));
        $newFrom = $this->formatPostgresArray(array_column(static::$objectCollection, 'newFrom'));
        $newTo = $this->formatPostgresArray(array_column(static::$objectCollection, 'newTo'));

        $sql = $this->createAbstractProductSQL();

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $start = microtime(true);
        $stmt->execute([
            $abstractSkus,
            $attributes,
            $fkTaxSets,
            $colorCode,
            $newFrom,
            $newTo
        ]);

//        $result = $stmt->fetchAll();
//        foreach ($result as $columns) {
//            static::$affectedProductAbstract[$columns['sku']] = $columns['id_product_abstract'];
//        }
        $time = sprintf('Product Abstract: %.4s', microtime(true) - $start);
        dump($time);
    }

    /**
     * @return void
     */
    protected function persistAbstractProductLocalizedAttributesEntities()
    {
        $abstractSkus = $this->formatPostgresArray(array_column(static::$objectCollectionLocalizedAttributes, 'abstract_sku'));
        $idLocale = $this->formatPostgresArray(array_column(static::$objectCollectionLocalizedAttributes, 'idLocale'));
        $name = $this->formatPostgresArrayString(array_column(static::$objectCollectionLocalizedAttributes, 'name'));
        $description = $this->formatPostgresArrayString(array_column(static::$objectCollectionLocalizedAttributes, 'description'));
        $metaTitle = $this->formatPostgresArrayString(array_column(static::$objectCollectionLocalizedAttributes, 'metaTitle'));
        $metaDescription = $this->formatPostgresArrayString(array_column(static::$objectCollectionLocalizedAttributes, 'metaDescription'));
        $metaKeywords = $this->formatPostgresArrayString(array_column(static::$objectCollectionLocalizedAttributes, 'metaKeywords'));
        $attributes = $this->formatPostgresArrayFromJson(array_column(static::$objectCollectionLocalizedAttributes, 'attributes'));

        $sql = $this->createAbstractProductLocalizedAttributesSQL();

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $start = microtime(true);
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

        $time = sprintf('Product Localized Attribute: %.4s', microtime(true) - $start);
        dump($time);
    }

    /**
     * @return void
     */
    protected function persistAbstractProductCategoryEntities()
    {
        $abstractSkus = $this->formatPostgresArray(array_column(static::$objectCollectionProductCategory, 'abstract_sku'));
        $fkCategory = $this->formatPostgresArray(array_column(static::$objectCollectionProductCategory, 'fkCategory'));
        $productOrder = $this->formatPostgresArrayString(array_column(static::$objectCollectionProductCategory, 'productOrder'));

        $sql = $this->createAbstractProductCategorySQL();

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $start = microtime(true);
        $stmt->execute([
            $abstractSkus,
            $fkCategory,
            $productOrder
        ]);

        $time = sprintf('Product Category: %.4s', microtime(true) - $start);
        dump($time);
    }

    /**
     * @return void
     */
    protected function persistAbstractProductUrlEntities()
    {
        $abstractSkus = $this->formatPostgresArray(array_column(static::$objectCollectionProductUrl, 'abstract_sku'));
        $idLocale = $this->formatPostgresArray(array_column(static::$objectCollectionProductUrl, 'idLocale'));
        $url = $this->formatPostgresArrayString(array_column(static::$objectCollectionProductUrl, 'url'));

        $sql = $this->createAbstractProductUrlSQL();

        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $start = microtime(true);
        $stmt->execute([
            $abstractSkus,
            $idLocale,
            $url
        ]);

        $time = sprintf('Product Url: %.4s', microtime(true) - $start);
        dump($time);
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
     * @return string
     */
    protected function createAbstractProductCategorySQL()
    {
        $sql = "WITH records AS (
    SELECT
      input.abstract_sku,
      input.fkCategory,
      input.productOrder,
      id_product_abstract,
      id_product_category
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS abstract_sku,
             unnest(? :: INTEGER []) AS fkCategory,
             unnest(? :: INTEGER []) AS productOrder
         ) input
      INNER JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
      LEFT JOIN spy_product_category ON (spy_product_category.fk_product_abstract = id_product_abstract and spy_product_category.fk_category = input.fkCategory)
),
    updated AS (
    UPDATE spy_product_category
    SET
      fk_category = records.fkCategory,
      product_order = records.productOrder
    FROM records
    WHERE records.id_product_abstract = spy_product_category.fk_product_abstract and spy_product_category.fk_category = records.fkCategory
  ),
    inserted AS(
    INSERT INTO spy_product_category (
      id_product_category,
      fk_category,
      product_order,
      fk_product_abstract
    ) (
      SELECT
        nextval('spy_product_category_pk_seq'),
        fkCategory,
        productOrder,
        id_product_abstract
      FROM records
      WHERE id_product_category is null
    )
  )
SELECT 1;
";

        return $sql;
    }

    /**
     * @return string
     */
    protected function createAbstractProductUrlSQL()
    {
        $sql = "WITH records AS (
    SELECT
      input.abstract_sku,
      input.idLocale,
      input.url,
      id_product_abstract,
      id_url
    FROM (
           SELECT
             unnest(? :: VARCHAR []) AS abstract_sku,
             unnest(? :: INTEGER []) AS idLocale,
             unnest(? :: VARCHAR []) AS url
         ) input
      INNER JOIN spy_product_abstract ON spy_product_abstract.sku = input.abstract_sku
      LEFT JOIN spy_url ON (spy_url.fk_resource_product_abstract = id_product_abstract and spy_url.fk_locale = input.idLocale)
),
    updated AS (
    UPDATE spy_url
    SET
      url = records.url
    FROM records
    WHERE records.id_product_abstract = spy_url.fk_resource_product_abstract and spy_url.fk_locale = records.idLocale
  ),
    inserted AS(
    INSERT INTO spy_url (
      id_url,
      fk_locale,
      url,
      fk_resource_product_abstract
    ) (
      SELECT
        nextval('spy_url_pk_seq'),
        idLocale,
        url,
        id_product_abstract
      FROM records
      WHERE id_url is null
    )
  )
SELECT 1;
";
        return $sql;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
//    public function execute(DataSetInterface $dataSet)
//    {
//        $productAbstractEntity = $this->importProductAbstract($dataSet);
//        $this->importProductAbstractLocalizedAttributes($dataSet, $productAbstractEntity);
//
//        $this->productRepository->addProductAbstract($productAbstractEntity);
//
//        $this->importProductCategories($dataSet, $productAbstractEntity);
//        $this->importProductUrls($dataSet, $productAbstractEntity);
//
//        $this->addPublishEvents(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $productAbstractEntity->getIdProductAbstract());
//    }

    /**
     * @param string $categoryKeys
     *
     * @return array
     */

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return void
     */
    protected function importProductAbstractLocalizedAttributes(DataSetInterface $dataSet, SpyProductAbstract $productAbstractEntity)
    {
        foreach ($dataSet[ProductLocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES] as $idLocale => $localizedAttributes) {
            $productAbstractLocalizedAttributesEntity = SpyProductAbstractLocalizedAttributesQuery::create()
                ->filterByFkProductAbstract($productAbstractEntity->getIdProductAbstract())
                ->filterByFkLocale($idLocale)
                ->findOneOrCreate();

            $productAbstractLocalizedAttributesEntity
                ->setName($localizedAttributes[static::KEY_NAME])
                ->setDescription($localizedAttributes[static::KEY_DESCRIPTION])
                ->setMetaTitle($localizedAttributes[static::KEY_META_TITLE])
                ->setMetaDescription($localizedAttributes[static::KEY_META_DESCRIPTION])
                ->setMetaKeywords($localizedAttributes[static::KEY_META_KEYWORDS])
                ->setAttributes(json_encode($localizedAttributes[static::KEY_ATTRIBUTES]));

            if ($productAbstractLocalizedAttributesEntity->isNew() || $productAbstractLocalizedAttributesEntity->isModified()) {
                $productAbstractLocalizedAttributesEntity->save();
            }
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    protected function importProductCategories(DataSetInterface $dataSet, SpyProductAbstract $productAbstractEntity)
    {
        $categoryKeys = $this->getCategoryKeys($dataSet[static::KEY_CATEGORY_KEY]);
        $categoryProductOrder = $this->getCategoryProductOrder($dataSet[static::KEY_CATEGORY_PRODUCT_ORDER]);

        foreach ($categoryKeys as $index => $categoryKey) {
            if (!isset($dataSet[static::KEY_CATEGORY_KEYS][$categoryKey])) {
                throw new DataKeyNotFoundInDataSetException(sprintf(
                    'The category with key "%s" was not found in categoryKeys. Maybe there is a typo. Given Categories: "%s"',
                    $categoryKey,
                    implode(array_values($dataSet[static::KEY_CATEGORY_KEYS]))
                ));
            }
            $productOrder = null;
            if (count($categoryProductOrder) > 0 && isset($categoryProductOrder[$index])) {
                $productOrder = $categoryProductOrder[$index];
            }

            $productCategoryEntity = SpyProductCategoryQuery::create()
                ->filterByFkProductAbstract($productAbstractEntity->getIdProductAbstract())
                ->filterByFkCategory($dataSet[static::KEY_CATEGORY_KEYS][$categoryKey])
                ->findOneOrCreate();

            $productCategoryEntity
                ->setProductOrder($productOrder);

            if ($productCategoryEntity->isNew() || $productCategoryEntity->isModified()) {
                $productCategoryEntity->save();

                $this->addPublishEvents(ProductCategoryEvents::PRODUCT_CATEGORY_PUBLISH, $productAbstractEntity->getIdProductAbstract());
                $this->addPublishEvents(ProductEvents::PRODUCT_ABSTRACT_PUBLISH, $productAbstractEntity->getIdProductAbstract());
            }
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return void
     */
    protected function importProductUrls(DataSetInterface $dataSet, SpyProductAbstract $productAbstractEntity)
    {
        foreach ($dataSet[ProductLocalizedAttributesExtractorStep::KEY_LOCALIZED_ATTRIBUTES] as $idLocale => $localizedAttributes) {
            $abstractProductUrl = $localizedAttributes[static::KEY_URL];

//            $this->cleanupRedirectUrls($abstractProductUrl);

            $urlEntity = SpyUrlQuery::create()
                ->filterByFkLocale($idLocale)
                ->filterByFkResourceProductAbstract($productAbstractEntity->getIdProductAbstract())
                ->findOneOrCreate();

            $urlEntity->setUrl($abstractProductUrl);

            if ($urlEntity->isNew() || $urlEntity->isModified()) {
                $urlEntity->save();

                $this->addPublishEvents(UrlEvents::URL_PUBLISH, $urlEntity->getIdUrl());
            }
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function importProductAbstract(DataSetInterface $dataSet)
    {
        $productAbstractEntity = SpyProductAbstractQuery::create()
            ->filterBySku($dataSet[static::KEY_ABSTRACT_SKU])
            ->findOneOrCreate();

        $productAbstractEntity
            ->setColorCode($dataSet[static::KEY_COLOR_CODE])
            ->setFkTaxSet($dataSet[static::KEY_ID_TAX_SET])
            ->setAttributes(json_encode($dataSet[static::KEY_ATTRIBUTES]))
            ->setNewFrom($dataSet[static::KEY_NEW_FROM])
            ->setNewTo($dataSet[static::KEY_NEW_TO]);

        if ($productAbstractEntity->isNew() || $productAbstractEntity->isModified()) {
            $productAbstractEntity->save();
        }

        return $productAbstractEntity;
    }

    protected function getCategoryKeys($categoryKeys)
    {
        $categoryKeys = explode(',', $categoryKeys);

        return array_map('trim', $categoryKeys);
    }

    /**
     * @param string $categoryProductOrder
     *
     * @return array
     */
    protected function getCategoryProductOrder($categoryProductOrder)
    {
        $categoryProductOrder = explode(',', $categoryProductOrder);

        return array_map('trim', $categoryProductOrder);
    }

    /**
     * @param string $abstractProductUrl
     *
     * @return void
     */
    protected function cleanupRedirectUrls($abstractProductUrl)
    {
        SpyUrlQuery::create()
            ->filterByUrl($abstractProductUrl)
            ->filterByFkResourceRedirect(null, Criteria::ISNOTNULL)
            ->delete();
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
            '{%s}',
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
