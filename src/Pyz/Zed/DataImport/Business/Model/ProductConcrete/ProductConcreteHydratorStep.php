<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model\ProductConcrete;

use Generated\Shared\Transfer\SpyProductBundleEntityTransfer;
use Generated\Shared\Transfer\SpyProductEntityTransfer;
use Generated\Shared\Transfer\SpyProductLocalizedAttributesEntityTransfer;
use Generated\Shared\Transfer\SpyProductSearchEntityTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ProductConcreteHydratorStep implements DataImportStepInterface
{
    const BULK_SIZE = 100;

    const KEY_ATTRIBUTES = 'attributes';
    const KEY_DISCOUNT = 'discount';
    const KEY_QUANTITY = 'quantity';
    const KEY_WAREHOUSES = 'warehouses';
    const KEY_SPY_PRODUCT = 'spy_product';
    const KEY_ID_PRODUCT = 'id_product';
    const KEY_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';
    const KEY_LOCALIZED_ATTRIBUTES = 'localizedAttributes';
    const KEY_NAME = 'name';
    const KEY_DESCRIPTION = 'description';
    const KEY_LOCALES = 'locales';
    const KEY_FK_LOCALE = 'fk_locale';
    const KEY_FK_PRODUCT = 'fk_product';
    const KEY_FK_BUNDLED_PRODUCT = 'fk_bundled_product';
    const KEY_CONCRETE_SKU = 'concrete_sku';
    const KEY_ABSTRACT_SKU = 'abstract_sku';
    const KEY_SKU = 'sku';
    const KEY_IS_ACTIVE = 'is_active';
    const KEY_IS_COMPLETE = 'is_complete';
    const KEY_IS_SEARCHABLE = 'is_searchable';
    const KEY_BUNDLES = 'bundled';
    const KEY_PRODUCT_BUNDLE_TRANSFER = 'productBundleEntityTransfer';
    const KEY_PRODUCT_CONCRETE_LOCALIZED_TRANSFER = 'localizedAttributeTransfer';
    const KEY_PRODUCT_SEARCH_TRANSFER = 'productSearchEntityTransfer';
    const KEY_PRODUCT_BUNDLE_SKU = 'bundledProductSku';
    const PRODUCT_CONCRETE_TRANSFER = 'PRODUCT_CONCRETE_TRANSFER';
    const PRODUCT_CONCRETE_LOCALIZED_TRANSFER = 'PRODUCT_CONCRETE_LOCALIZED_TRANSFER';
    const PRODUCT_BUNDLE_TRANSFER = 'PRODUCT_BUNDLE_TRANSFER';
    const KEY_IS_QUANTITY_SPLITTABLE = 'is_quantity_splittable';

    /**
     * @var \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository
     */
    protected $productRepository;

    /**
     * @var bool[] Keys are product column names
     */
    protected static $isProductColumnBuffer = [];

    /**
     * ProductConcreteHydratorStep constructor.
     *
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository $productRepository
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->importProduct($dataSet);
        $this->importProductLocalizedAttributes($dataSet);
        $this->importBundles($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importProduct(DataSetInterface $dataSet): void
    {
        $productEntityTransfer = new SpyProductEntityTransfer();
        $productEntityTransfer->setSku($dataSet[static::KEY_CONCRETE_SKU]);
        $productEntityTransfer
            ->setIsActive($dataSet[static::KEY_IS_ACTIVE] ?? true)
            ->setAttributes(json_encode($dataSet[static::KEY_ATTRIBUTES]));

        if ($this->isProductColumn(static::KEY_IS_QUANTITY_SPLITTABLE)) {
            $isQuantitySplittable = (
                !isset($dataSet[static::KEY_IS_QUANTITY_SPLITTABLE]) ||
                $dataSet[static::KEY_IS_QUANTITY_SPLITTABLE] === ""
            ) ? true : $dataSet[static::KEY_IS_QUANTITY_SPLITTABLE];
            $productEntityTransfer->setIsQuantitySplittable($isQuantitySplittable);
        }

        $dataSet[static::PRODUCT_CONCRETE_TRANSFER] = $productEntityTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importProductLocalizedAttributes(DataSetInterface $dataSet): void
    {
        $localizedAttributeTransfer = [];

        foreach ($dataSet[static::KEY_LOCALIZED_ATTRIBUTES] as $idLocale => $localizedAttributes) {
            $productLocalizedAttributesEntityTransfer = new SpyProductLocalizedAttributesEntityTransfer();
            $productLocalizedAttributesEntityTransfer
                ->setName($localizedAttributes[static::KEY_NAME])
                ->setDescription($localizedAttributes[static::KEY_DESCRIPTION])
                ->setIsComplete($localizedAttributes[static::KEY_IS_COMPLETE] ?? true)
                ->setAttributes(json_encode($localizedAttributes[static::KEY_ATTRIBUTES]))
                ->setFkLocale($idLocale);

            $productSearchEntityTransfer = new SpyProductSearchEntityTransfer();
            $productSearchEntityTransfer
                ->setFkLocale($idLocale)
                ->setIsSearchable($localizedAttributes[static::KEY_IS_SEARCHABLE]);

            $localizedAttributeTransfer[] = [
                static::KEY_PRODUCT_CONCRETE_LOCALIZED_TRANSFER => $productLocalizedAttributesEntityTransfer,
                static::KEY_PRODUCT_SEARCH_TRANSFER => $productSearchEntityTransfer,
                static::KEY_SKU => $dataSet[static::KEY_CONCRETE_SKU],
            ];
        }

        $dataSet[static::PRODUCT_CONCRETE_LOCALIZED_TRANSFER] = $localizedAttributeTransfer;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importBundles(DataSetInterface $dataSet): void
    {
        $productBundleTransfer = [];

        if (!empty($dataSet[static::KEY_BUNDLES])) {
            $bundleProducts = explode(',', $dataSet[static::KEY_BUNDLES]);

            foreach ($bundleProducts as $bundleProduct) {
                $bundleProduct = trim($bundleProduct);
                [$sku, $quantity] = explode('/', $bundleProduct);

                $productBundleEntityTransfer = new SpyProductBundleEntityTransfer();
                $productBundleEntityTransfer->setQuantity($quantity);
                $productBundleTransfer[] = [
                    static::KEY_PRODUCT_BUNDLE_TRANSFER => $productBundleEntityTransfer,
                    static::KEY_SKU => $dataSet[static::KEY_CONCRETE_SKU],
                    static::KEY_PRODUCT_BUNDLE_SKU => $sku,
                ];
            }
        }
        $dataSet[static::PRODUCT_BUNDLE_TRANSFER] = $productBundleTransfer;
    }

    /**
     * @param string $columnName
     *
     * @return bool
     */
    protected function isProductColumn(string $columnName): bool
    {
        if (isset(static::$isProductColumnBuffer[$columnName])) {
            return static::$isProductColumnBuffer[$columnName];
        }
        $isColumnExists = SpyProductTableMap::getTableMap()->hasColumn($columnName);
        static::$isProductColumnBuffer[$columnName] = $isColumnExists;

        return $isColumnExists;
    }
}
