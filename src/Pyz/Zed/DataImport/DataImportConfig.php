<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport;

use Generated\Shared\Transfer\DataImporterQueueWriterConfigurationTransfer;
use Spryker\Zed\BusinessOnBehalfDataImport\BusinessOnBehalfDataImportConfig;
use Spryker\Zed\CategoryDataImport\CategoryDataImportConfig;
use Spryker\Zed\CmsPageDataImport\CmsPageDataImportConfig;
use Spryker\Zed\CmsSlotBlockDataImport\CmsSlotBlockDataImportConfig;
use Spryker\Zed\CmsSlotDataImport\CmsSlotDataImportConfig;
use Spryker\Zed\CommentDataImport\CommentDataImportConfig;
use Spryker\Zed\CompanyBusinessUnitDataImport\CompanyBusinessUnitDataImportConfig;
use Spryker\Zed\CompanyDataImport\CompanyDataImportConfig;
use Spryker\Zed\CompanyRoleDataImport\CompanyRoleDataImportConfig;
use Spryker\Zed\CompanySupplierDataImport\CompanySupplierDataImportConfig;
use Spryker\Zed\CompanyUnitAddressDataImport\CompanyUnitAddressDataImportConfig;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\CompanyUnitAddressLabelDataImportConfig;
use Spryker\Zed\CompanyUserDataImport\CompanyUserDataImportConfig;
use Spryker\Zed\ConfigurableBundleDataImport\ConfigurableBundleDataImportConfig;
use Spryker\Zed\ContentBannerDataImport\ContentBannerDataImportConfig;
use Spryker\Zed\ContentProductDataImport\ContentProductDataImportConfig;
use Spryker\Zed\ContentProductSetDataImport\ContentProductSetDataImportConfig;
use Spryker\Zed\DataImport\DataImportConfig as SprykerDataImportConfig;
use Spryker\Zed\FileManagerDataImport\FileManagerDataImportConfig;
use Spryker\Zed\MerchantDataImport\MerchantDataImportConfig;
use Spryker\Zed\MerchantOmsDataImport\MerchantOmsDataImportConfig;
use Spryker\Zed\MerchantOpeningHoursDataImport\MerchantOpeningHoursDataImportConfig;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;
use Spryker\Zed\MerchantProductOfferDataImport\MerchantProductOfferDataImportConfig;
use Spryker\Zed\MerchantProfileDataImport\MerchantProfileDataImportConfig;
use Spryker\Zed\MerchantRelationshipDataImport\MerchantRelationshipDataImportConfig;
use Spryker\Zed\MerchantRelationshipProductListDataImport\MerchantRelationshipProductListDataImportConfig;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\MerchantRelationshipSalesOrderThresholdDataImportConfig;
use Spryker\Zed\MerchantStockDataImport\MerchantStockDataImportConfig;
use Spryker\Zed\MultiCartDataImport\MultiCartDataImportConfig;
use Spryker\Zed\PaymentDataImport\PaymentDataImportConfig;
use Spryker\Zed\PriceProductDataImport\PriceProductDataImportConfig;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\PriceProductMerchantRelationshipDataImportConfig;
use Spryker\Zed\PriceProductOfferDataImport\PriceProductOfferDataImportConfig;
use Spryker\Zed\PriceProductScheduleDataImport\PriceProductScheduleDataImportConfig;
use Spryker\Zed\ProductAlternativeDataImport\ProductAlternativeDataImportConfig;
use Spryker\Zed\ProductDiscontinuedDataImport\ProductDiscontinuedDataImportConfig;
use Spryker\Zed\ProductLabelDataImport\ProductLabelDataImportConfig;
use Spryker\Zed\ProductListDataImport\ProductListDataImportConfig;
use Spryker\Zed\ProductMeasurementUnitDataImport\ProductMeasurementUnitDataImportConfig;
use Spryker\Zed\ProductOfferStockDataImport\ProductOfferStockDataImportConfig;
use Spryker\Zed\ProductOfferValidityDataImport\ProductOfferValidityDataImportConfig;
use Spryker\Zed\ProductPackagingUnitDataImport\ProductPackagingUnitDataImportConfig;
use Spryker\Zed\ProductQuantityDataImport\ProductQuantityDataImportConfig;
use Spryker\Zed\ProductRelationDataImport\ProductRelationDataImportConfig;
use Spryker\Zed\QuoteRequestDataImport\QuoteRequestDataImportConfig;
use Spryker\Zed\SalesOrderThresholdDataImport\SalesOrderThresholdDataImportConfig;
use Spryker\Zed\SalesReturnDataImport\SalesReturnDataImportConfig;
use Spryker\Zed\SharedCartDataImport\SharedCartDataImportConfig;
use Spryker\Zed\ShipmentDataImport\ShipmentDataImportConfig;
use Spryker\Zed\ShoppingListDataImport\ShoppingListDataImportConfig;
use Spryker\Zed\StockDataImport\StockDataImportConfig;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
class DataImportConfig extends SprykerDataImportConfig
{
    public const IMPORT_TYPE_CATEGORY_TEMPLATE = 'category-template';
    public const IMPORT_TYPE_CUSTOMER = 'customer';
    public const IMPORT_TYPE_GLOSSARY = 'glossary';
    public const IMPORT_TYPE_NAVIGATION = 'navigation';
    public const IMPORT_TYPE_NAVIGATION_NODE = 'navigation-node';
    public const IMPORT_TYPE_PRODUCT_PRICE = 'product-price';
    public const IMPORT_TYPE_PRODUCT_STOCK = 'product-stock';
    public const IMPORT_TYPE_PRODUCT_ABSTRACT = 'product-abstract';
    public const IMPORT_TYPE_PRODUCT_ABSTRACT_STORE = 'product-abstract-store';
    public const IMPORT_TYPE_PRODUCT_CONCRETE = 'product-concrete';
    public const IMPORT_TYPE_PRODUCT_ATTRIBUTE_KEY = 'product-attribute-key';
    public const IMPORT_TYPE_PRODUCT_MANAGEMENT_ATTRIBUTE = 'product-management-attribute';
    public const IMPORT_TYPE_PRODUCT_REVIEW = 'product-review';
    public const IMPORT_TYPE_PRODUCT_SET = 'product-set';
    public const IMPORT_TYPE_PRODUCT_GROUP = 'product-group';
    public const IMPORT_TYPE_PRODUCT_OPTION = 'product-option';
    public const IMPORT_TYPE_PRODUCT_OPTION_PRICE = 'product-option-price';
    public const IMPORT_TYPE_PRODUCT_IMAGE = 'product-image';
    public const IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE_MAP = 'product-search-attribute-map';
    public const IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE = 'product-search-attribute';
    public const IMPORT_TYPE_CMS_TEMPLATE = 'cms-template';
    public const IMPORT_TYPE_CMS_BLOCK = 'cms-block';
    public const IMPORT_TYPE_CMS_BLOCK_STORE = 'cms-block-store';
    public const IMPORT_TYPE_CMS_BLOCK_CATEGORY_POSITION = 'cms-block-category-position';
    public const IMPORT_TYPE_CMS_BLOCK_CATEGORY = 'cms-block-category';
    public const IMPORT_TYPE_DISCOUNT = 'discount';
    public const IMPORT_TYPE_DISCOUNT_STORE = 'discount-store';
    public const IMPORT_TYPE_DISCOUNT_AMOUNT = 'discount-amount';
    public const IMPORT_TYPE_DISCOUNT_VOUCHER = 'discount-voucher';
    public const IMPORT_TYPE_TAX = 'tax';
    public const IMPORT_TYPE_CURRENCY = 'currency';
    public const IMPORT_TYPE_STORE = 'store';
    public const IMPORT_TYPE_ORDER_SOURCE = 'order-source';
    public const IMPORT_TYPE_ABSTRACT_GIFT_CARD_CONFIGURATION = 'gift-card-abstract-configuration';
    public const IMPORT_TYPE_CONCRETE_GIFT_CARD_CONFIGURATION = 'gift-card-concrete-configuration';

    public const PRODUCT_ABSTRACT_QUEUE = 'import.product_abstract';
    public const PRODUCT_ABSTRACT_QUEUE_ERROR = 'import.product_abstract.error';
    public const PRODUCT_CONCRETE_QUEUE = 'import.product_concrete';
    public const PRODUCT_CONCRETE_QUEUE_ERROR = 'import.product_concrete.error';
    public const PRODUCT_IMAGE_QUEUE = 'import.product_image';
    public const PRODUCT_IMAGE_QUEUE_ERROR = 'import.product_image.error';
    public const PRODUCT_PRICE_QUEUE = 'import.product_price';
    public const PRODUCT_PRICE_QUEUE_ERROR = 'import.product_price.error';
    public const IMPORT_TYPE_MERCHANT_USER = 'merchant-user';

    /**
     * @return string|null
     */
    public function getDefaultYamlConfigPath(): ?string
    {
        return APPLICATION_ROOT_DIR . DIRECTORY_SEPARATOR . 'data/import/config/full_import_config.yml';
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterQueueWriterConfigurationTransfer
     */
    public function getProductAbstractQueueWriterConfiguration(): DataImporterQueueWriterConfigurationTransfer
    {
        return (new DataImporterQueueWriterConfigurationTransfer())
            ->setQueueName(static::PRODUCT_ABSTRACT_QUEUE)
            ->setChunkSize($this->getQueueWriterChunkSize());
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterQueueWriterConfigurationTransfer
     */
    public function getProductConcreteQueueWriterConfiguration(): DataImporterQueueWriterConfigurationTransfer
    {
        return (new DataImporterQueueWriterConfigurationTransfer())
            ->setQueueName(static::PRODUCT_CONCRETE_QUEUE)
            ->setChunkSize($this->getQueueWriterChunkSize());
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterQueueWriterConfigurationTransfer
     */
    public function getProductImageQueueWriterConfiguration(): DataImporterQueueWriterConfigurationTransfer
    {
        return (new DataImporterQueueWriterConfigurationTransfer())
            ->setQueueName(static::PRODUCT_IMAGE_QUEUE)
            ->setChunkSize($this->getQueueWriterChunkSize());
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterQueueWriterConfigurationTransfer
     */
    public function getProductPriceQueueWriterConfiguration(): DataImporterQueueWriterConfigurationTransfer
    {
        return (new DataImporterQueueWriterConfigurationTransfer())
            ->setQueueName(static::PRODUCT_PRICE_QUEUE)
            ->setChunkSize($this->getQueueWriterChunkSize());
    }

    /**
     * @return string[]
     */
    public function getFullImportTypes(): array
    {
        return [
            static::IMPORT_TYPE_CATEGORY_TEMPLATE,
            static::IMPORT_TYPE_CUSTOMER,
            static::IMPORT_TYPE_GLOSSARY,
            static::IMPORT_TYPE_NAVIGATION,
            static::IMPORT_TYPE_NAVIGATION_NODE,
            static::IMPORT_TYPE_PRODUCT_PRICE,
            static::IMPORT_TYPE_PRODUCT_ABSTRACT,
            static::IMPORT_TYPE_PRODUCT_ABSTRACT_STORE,
            static::IMPORT_TYPE_PRODUCT_CONCRETE,
            static::IMPORT_TYPE_PRODUCT_ATTRIBUTE_KEY,
            static::IMPORT_TYPE_PRODUCT_MANAGEMENT_ATTRIBUTE,
            ProductRelationDataImportConfig::IMPORT_TYPE_PRODUCT_RELATION,
            ProductRelationDataImportConfig::IMPORT_TYPE_PRODUCT_RELATION_STORE,
            static::IMPORT_TYPE_PRODUCT_REVIEW,
            ProductLabelDataImportConfig::IMPORT_TYPE_PRODUCT_LABEL,
            ProductLabelDataImportConfig::IMPORT_TYPE_PRODUCT_LABEL_STORE,
            static::IMPORT_TYPE_PRODUCT_SET,
            static::IMPORT_TYPE_PRODUCT_GROUP,
            static::IMPORT_TYPE_PRODUCT_OPTION,
            static::IMPORT_TYPE_PRODUCT_OPTION_PRICE,
            static::IMPORT_TYPE_PRODUCT_IMAGE,
            static::IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE_MAP,
            static::IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE,
            static::IMPORT_TYPE_CMS_TEMPLATE,
            static::IMPORT_TYPE_CMS_BLOCK,
            static::IMPORT_TYPE_CMS_BLOCK_STORE,
            static::IMPORT_TYPE_CMS_BLOCK_CATEGORY_POSITION,
            static::IMPORT_TYPE_CMS_BLOCK_CATEGORY,
            static::IMPORT_TYPE_DISCOUNT,
            static::IMPORT_TYPE_DISCOUNT_STORE,
            static::IMPORT_TYPE_DISCOUNT_AMOUNT,
            static::IMPORT_TYPE_DISCOUNT_VOUCHER,
            static::IMPORT_TYPE_TAX,
            static::IMPORT_TYPE_CURRENCY,
            static::IMPORT_TYPE_STORE,
            static::IMPORT_TYPE_PRODUCT_STOCK,
            static::IMPORT_TYPE_ORDER_SOURCE,
            static::IMPORT_TYPE_ABSTRACT_GIFT_CARD_CONFIGURATION,
            static::IMPORT_TYPE_CONCRETE_GIFT_CARD_CONFIGURATION,
            CmsPageDataImportConfig::IMPORT_TYPE_CMS_PAGE_STORE,
            CmsPageDataImportConfig::IMPORT_TYPE_CMS_PAGE,
            CompanyDataImportConfig::IMPORT_TYPE_COMPANY,
            CategoryDataImportConfig::IMPORT_TYPE_CATEGORY,
            MerchantDataImportConfig::IMPORT_TYPE_MERCHANT,
            MerchantDataImportConfig::IMPORT_TYPE_MERCHANT_STORE,
            MerchantProductDataImportConfig::IMPORT_TYPE_MERCHANT_PRODUCT,
            MerchantProfileDataImportConfig::IMPORT_TYPE_MERCHANT_PROFILE,
            MerchantProfileDataImportConfig::IMPORT_TYPE_MERCHANT_PROFILE_ADDRESS,
            MerchantProductOfferDataImportConfig::IMPORT_TYPE_MERCHANT_PRODUCT_OFFER,
            MerchantProductOfferDataImportConfig::IMPORT_TYPE_MERCHANT_PRODUCT_OFFER_STORE,
            MerchantOpeningHoursDataImportConfig::IMPORT_TYPE_MERCHANT_OPENING_HOURS_WEEKDAY_SCHEDULE,
            MerchantOpeningHoursDataImportConfig::IMPORT_TYPE_MERCHANT_OPENING_HOURS_DATE_SCHEDULE,
            ProductOfferValidityDataImportConfig::IMPORT_TYPE_PRODUCT_OFFER_VALIDITY,
            PriceProductOfferDataImportConfig::IMPORT_TYPE_PRICE_PRODUCT_OFFER,
            ProductOfferStockDataImportConfig::IMPORT_TYPE_PRODUCT_OFFER_STOCK,
            MultiCartDataImportConfig::IMPORT_TYPE_MULTI_CART,
            SharedCartDataImportConfig::IMPORT_TYPE_SHARED_CART,
            CompanyRoleDataImportConfig::IMPORT_TYPE_COMPANY_USER_ROLE,
            CompanyRoleDataImportConfig::IMPORT_TYPE_COMPANY_ROLE_PERMISSION,
            CompanyRoleDataImportConfig::IMPORT_TYPE_COMPANY_ROLE,
            CompanyUserDataImportConfig::IMPORT_TYPE_COMPANY_USER,
            FileManagerDataImportConfig::IMPORT_TYPE_MIME_TYPE,
            ProductListDataImportConfig::IMPORT_TYPE_PRODUCT_LIST_CATEGORY,
            ProductListDataImportConfig::IMPORT_TYPE_PRODUCT_LIST_PRODUCT_CONCRETE,
            ProductListDataImportConfig::IMPORT_TYPE_PRODUCT_LIST,
            PriceProductDataImportConfig::IMPORT_TYPE_PRODUCT_PRICE,
            QuoteRequestDataImportConfig::IMPORT_TYPE_QUOTE_REQUEST,
            QuoteRequestDataImportConfig::IMPORT_TYPE_QUOTE_REQUEST_VERSION,
            ShoppingListDataImportConfig::IMPORT_TYPE_SHOPPING_LIST_COMPANY_USER,
            ShoppingListDataImportConfig::IMPORT_TYPE_SHOPPING_LIST_ITEM,
            ShoppingListDataImportConfig::IMPORT_TYPE_SHOPPING_LIST,
            ShoppingListDataImportConfig::IMPORT_TYPE_SHOPPING_LIST_COMPANY_BUSINESS_UNIT,
            ContentBannerDataImportConfig::IMPORT_TYPE_CONTENT_BANNER,
            ContentProductDataImportConfig::IMPORT_TYPE_CONTENT_PRODUCT,
            CompanySupplierDataImportConfig::IMPORT_TYPE_COMPANY_SUPPLIER,
            CompanySupplierDataImportConfig::IMPORT_TYPE_PRODUCT_PRICE,
            CompanySupplierDataImportConfig::IMPORT_TYPE_COMPANY_TYPE,
            ProductQuantityDataImportConfig::IMPORT_TYPE_PRODUCT_QUANTITY,
            BusinessOnBehalfDataImportConfig::IMPORT_TYPE_COMPANY_USER,
            ContentProductSetDataImportConfig::IMPORT_TYPE_CONTENT_PRODUCT_SET,
            CompanyUnitAddressDataImportConfig::IMPORT_TYPE_COMPANY_UNIT_ADDRESS,
            ProductAlternativeDataImportConfig::IMPORT_TYPE_PRODUCT_ALTERNATIVE,
            CompanyBusinessUnitDataImportConfig::IMPORT_TYPE_COMPANY_BUSINESS_UNIT_USER,
            CompanyBusinessUnitDataImportConfig::IMPORT_TYPE_COMPANY_BUSINESS_UNIT_ADDRESS,
            CompanyBusinessUnitDataImportConfig::IMPORT_TYPE_COMPANY_BUSINESS_UNIT,
            ProductDiscontinuedDataImportConfig::IMPORT_TYPE_PRODUCT_DISCONTINUED,
            SalesOrderThresholdDataImportConfig::IMPORT_TYPE_SALES_ORDER_THRESHOLD,
            MerchantRelationshipDataImportConfig::IMPORT_TYPE_MERCHANT_RELATIONSHIP,
            PriceProductScheduleDataImportConfig::IMPORT_TYPE_PRODUCT_PRICE_SCHEDULE,
            ProductPackagingUnitDataImportConfig::IMPORT_TYPE_PRODUCT_PACKAGING_UNIT_TYPE,
            ProductPackagingUnitDataImportConfig::IMPORT_TYPE_PRODUCT_PACKAGING_UNIT,
            ProductMeasurementUnitDataImportConfig::IMPORT_TYPE_PRODUCT_MEASUREMENT_SALES_UNIT_STORE,
            ProductMeasurementUnitDataImportConfig::IMPORT_TYPE_PRODUCT_MEASUREMENT_SALES_UNIT,
            ProductMeasurementUnitDataImportConfig::IMPORT_TYPE_PRODUCT_MEASUREMENT_UNIT,
            ProductMeasurementUnitDataImportConfig::IMPORT_TYPE_PRODUCT_MEASUREMENT_BASE_UNIT,
            CompanyUnitAddressLabelDataImportConfig::IMPORT_TYPE_COMPANY_UNIT_ADDRESS_LABEL,
            CompanyUnitAddressLabelDataImportConfig::IMPORT_TYPE_COMPANY_UNIT_ADDRESS_LABEL_RELATION,
            MerchantRelationshipProductListDataImportConfig::IMPORT_TYPE_MERCHANT_RELATIONSHIP_PRODUCT_LIST,
            PriceProductMerchantRelationshipDataImportConfig::IMPORT_TYPE_PRICE_PRODUCT_MERCHANT_RELATIONSHIP,
            MerchantRelationshipSalesOrderThresholdDataImportConfig::IMPORT_TYPE_MERCHANT_RELATIONSHIP_SALES_ORDER_THRESHOLD,
            CommentDataImportConfig::IMPORT_TYPE_COMMENT,
            ConfigurableBundleDataImportConfig::IMPORT_TYPE_CONFIGURABLE_BUNDLE_TEMPLATE,
            ConfigurableBundleDataImportConfig::IMPORT_TYPE_CONFIGURABLE_BUNDLE_TEMPLATE_SLOT,
            ConfigurableBundleDataImportConfig::IMPORT_TYPE_CONFIGURABLE_BUNDLE_TEMPLATE_IMAGE,
            CmsSlotDataImportConfig::IMPORT_TYPE_CMS_SLOT_TEMPLATE,
            CmsSlotDataImportConfig::IMPORT_TYPE_CMS_SLOT,
            CmsSlotBlockDataImportConfig::IMPORT_TYPE_CMS_SLOT_BLOCK,
            ShipmentDataImportConfig::IMPORT_TYPE_SHIPMENT,
            ShipmentDataImportConfig::IMPORT_TYPE_SHIPMENT_PRICE,
            ShipmentDataImportConfig::IMPORT_TYPE_SHIPMENT_METHOD_STORE,
            PaymentDataImportConfig::IMPORT_TYPE_PAYMENT_METHOD,
            PaymentDataImportConfig::IMPORT_TYPE_PAYMENT_METHOD_STORE,
            StockDataImportConfig::IMPORT_TYPE_STOCK,
            StockDataImportConfig::IMPORT_TYPE_STOCK_STORE,
            MerchantStockDataImportConfig::IMPORT_TYPE_MERCHANT_STOCK,
            MerchantOmsDataImportConfig::IMPORT_TYPE_MERCHANT_OMS_PROCESS,
            SalesReturnDataImportConfig::IMPORT_TYPE_RETURN_REASON,
            static::IMPORT_TYPE_MERCHANT_USER,
        ];
    }
}
