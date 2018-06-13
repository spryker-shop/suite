<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class DemoDataGeneratorConfig extends AbstractBundleConfig
{
    protected const ICE_CAT_BIZ_DATA_FOLDER_PATH = 'data/import/icecat_biz_data/';
    protected const DATA_FOLDER_PATH = 'data/import/';
    protected const PRODUCT_ABSTRACT_CSV_PATH = self::ICE_CAT_BIZ_DATA_FOLDER_PATH . 'product_abstract.csv';
    protected const PRODUCT_CONCRETE_CSV_PATH = self::ICE_CAT_BIZ_DATA_FOLDER_PATH . 'product_concrete.csv';
    protected const PRODUCT_ABSTRACT_STORE_CSV_PATH = self::ICE_CAT_BIZ_DATA_FOLDER_PATH . 'product_abstract_store.csv';
    protected const PRODUCT_IMAGE_CSV_PATH = self::ICE_CAT_BIZ_DATA_FOLDER_PATH . 'product_image.csv';
    protected const PRODUCT_PRICE_CSV_PATH = self::DATA_FOLDER_PATH . 'product_price.csv';
    protected const PRODUCT_STOCK_CSV_PATH = self::DATA_FOLDER_PATH . 'product_stock.csv';
    protected const STOCK_CSV_PATH = self::DATA_FOLDER_PATH . 'stock.csv';
    protected const DEFAULT_CATEGORY_KEY = 'digital-cameras';
    protected const DEFAULT_DE_URL = '/de/demo/url/';
    protected const DEFAULT_EN_URL = '/en/demo/url/';
    protected const DEFAULT_TAX_SET_NAME = 'Entertainment Electronics';
    protected const MIN_PRODUCT_BUNDLE_COUNT = 1;
    protected const MAX_PRODUCT_BUNDLE_COUNT = 5;
    /**
     * @var array
     */
    protected static $defaultPriceTypes = ['DEFAULT', 'ORIGINAL'];

    /**
     * @return string
     */
    public function getProductAbstractCsvPath(): string
    {
        return static::PRODUCT_ABSTRACT_CSV_PATH;
    }

    /**
     * @return string
     */
    public function getProductConcreteCsvPath(): string
    {
        return static::PRODUCT_CONCRETE_CSV_PATH;
    }

    /**
     * @return string
     */
    public function getProductAbstractStoreCsvPath(): string
    {
        return static::PRODUCT_ABSTRACT_STORE_CSV_PATH;
    }

    /**
     * @return string
     */
    public function getProductPriceCsvPath(): string
    {
        return static::PRODUCT_PRICE_CSV_PATH;
    }

    /**
     * @return array
     */
    public function getProductPriceDefaultTypes(): array
    {
        return static::$defaultPriceTypes;
    }

    /**
     * @return string
     */
    public function getProductImageCsvPath(): string
    {
        return static::PRODUCT_IMAGE_CSV_PATH;
    }

    /**
     * @return string
     */
    public function getProductStockCsvPath(): string
    {
        return static::PRODUCT_STOCK_CSV_PATH;
    }

    /**
     * @return string
     */
    public function getStockCsvPath(): string
    {
        return static::STOCK_CSV_PATH;
    }

    /**
     * @return array
     */
    public function getSkuPrefixStrategy(): array
    {
        return [
            [
                'digits' => 1,
                'prefix' => '00',
            ],
            [
                'digits' => 2,
                'prefix' => '0',
            ],
        ];
    }

    /**
     * @return string
     */
    public function getDefaultCategoryKey(): string
    {
         return static::DEFAULT_CATEGORY_KEY;
    }

    /**
     * @return string
     */
    public function getDefaultUrlDe(): string
    {
        return static::DEFAULT_DE_URL;
    }

    /**
     * @return string
     */
    public function getDefaultUrlEn(): string
    {
        return static::DEFAULT_EN_URL;
    }

    /**
     * @return string
     */
    public function getDefaultTaxSetName(): string
    {
        return static::DEFAULT_TAX_SET_NAME;
    }

    /**
     * @return int
     */
    public function getMinProductBundleCount(): int
    {
        return static::MIN_PRODUCT_BUNDLE_COUNT;
    }

    /**
     * @return int
     */
    public function getMaxProductBundleCount(): int
    {
        return static::MAX_PRODUCT_BUNDLE_COUNT;
    }
}
