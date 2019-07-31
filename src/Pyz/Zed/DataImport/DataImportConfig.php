<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport;

use Generated\Shared\Transfer\DataImporterQueueWriterConfigurationTransfer;
use Spryker\Zed\BusinessOnBehalfDataImport\Communication\Plugin\DataImport\BusinessOnBehalfCompanyUserDataImportPlugin;
use Spryker\Zed\CategoryDataImport\Communication\Plugin\CategoryDataImportPlugin;
use Spryker\Zed\CmsPageDataImport\Communication\Plugin\CmsPageDataImportPlugin;
use Spryker\Zed\CmsPageDataImport\Communication\Plugin\CmsPageStoreDataImportPlugin;
use Spryker\Zed\CommentDataImport\Communication\Plugin\CommentDataImportPlugin;
use Spryker\Zed\CompanyBusinessUnitDataImport\Communication\Plugin\CompanyBusinessUnitAddressDataImportPlugin;
use Spryker\Zed\CompanyBusinessUnitDataImport\Communication\Plugin\CompanyBusinessUnitDataImportPlugin;
use Spryker\Zed\CompanyBusinessUnitDataImport\Communication\Plugin\CompanyBusinessUnitUserDataImportPlugin;
use Spryker\Zed\CompanyDataImport\Communication\Plugin\CompanyDataImportPlugin;
use Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyRoleDataImportPlugin;
use Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyRolePermissionDataImportPlugin;
use Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyUserRoleDataImportPlugin;
use Spryker\Zed\CompanySupplierDataImport\Communication\Plugin\CompanySupplierDataImportPlugin;
use Spryker\Zed\CompanySupplierDataImport\Communication\Plugin\CompanySupplierProductPriceDataImportPlugin;
use Spryker\Zed\CompanySupplierDataImport\Communication\Plugin\CompanyTypeDataImportPlugin;
use Spryker\Zed\CompanyUnitAddressDataImport\Communication\Plugin\CompanyUnitAddressDataImportPlugin;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Communication\Plugin\CompanyUnitAddressLabelDataImportPlugin;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Communication\Plugin\CompanyUnitAddressLabelRelationDataImportPlugin;
use Spryker\Zed\CompanyUserDataImport\Communication\Plugin\DataImport\CompanyUserDataImportPlugin;
use Spryker\Zed\ContentBannerDataImport\Communication\Plugin\ContentBannerDataImportPlugin;
use Spryker\Zed\ContentProductDataImport\Communication\Plugin\ContentProductAbstractListDataImportPlugin;
use Spryker\Zed\ContentProductSetDataImport\Communication\Plugin\ContentProductSetDataImportPlugin;
use Spryker\Zed\DataImport\DataImportConfig as SprykerDataImportConfig;
use Spryker\Zed\FileManagerDataImport\Communication\Plugin\FileManagerDataImportPlugin;
use Spryker\Zed\MerchantDataImport\Communication\Plugin\MerchantDataImportPlugin;
use Spryker\Zed\MerchantRelationshipDataImport\Communication\Plugin\MerchantRelationshipDataImportPlugin;
use Spryker\Zed\MerchantRelationshipProductListDataImport\Communication\Plugin\MerchantRelationshipProductListDataImportPlugin;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Communication\Plugin\DataImport\MerchantRelationshipSalesOrderThresholdDataImportPlugin;
use Spryker\Zed\MultiCartDataImport\Communication\Plugin\MultiCartDataImportPlugin;
use Spryker\Zed\PriceProductDataImport\Communication\Plugin\PriceProductDataImportPlugin;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Communication\Plugin\PriceProductMerchantRelationshipDataImportPlugin;
use Spryker\Zed\PriceProductScheduleDataImport\Communication\Plugin\PriceProductScheduleDataImportPlugin;
use Spryker\Zed\ProductAlternativeDataImport\Communication\Plugin\ProductAlternativeDataImportPlugin;
use Spryker\Zed\ProductDiscontinuedDataImport\Communication\Plugin\ProductDiscontinuedDataImportPlugin;
use Spryker\Zed\ProductListDataImport\Communication\Plugin\ProductListCategoryDataImportPlugin;
use Spryker\Zed\ProductListDataImport\Communication\Plugin\ProductListDataImportPlugin;
use Spryker\Zed\ProductListDataImport\Communication\Plugin\ProductListProductConcreteDataImportPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementBaseUnitDataImportPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementSalesUnitDataImportPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementSalesUnitStoreDataImportPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementUnitDataImportPlugin;
use Spryker\Zed\ProductPackagingUnitDataImport\Communication\Plugin\DataImport\ProductPackagingUnitDataImportPlugin;
use Spryker\Zed\ProductPackagingUnitDataImport\Communication\Plugin\DataImport\ProductPackagingUnitTypeDataImportPlugin;
use Spryker\Zed\ProductQuantityDataImport\Communication\Plugin\ProductQuantityDataImportPlugin;
use Spryker\Zed\QuoteRequestDataImport\Communication\Plugin\QuoteRequestDataImportPlugin;
use Spryker\Zed\QuoteRequestDataImport\Communication\Plugin\QuoteRequestVersionDataImportPlugin;
use Spryker\Zed\SalesOrderThresholdDataImport\Communication\Plugin\DataImport\SalesOrderThresholdDataImportPlugin;
use Spryker\Zed\SharedCartDataImport\Communication\Plugin\SharedCartDataImportPlugin;
use Spryker\Zed\ShoppingListDataImport\Communication\Plugin\ShoppingListCompanyBusinessUnitDataImportPlugin;
use Spryker\Zed\ShoppingListDataImport\Communication\Plugin\ShoppingListCompanyUserDataImportPlugin;
use Spryker\Zed\ShoppingListDataImport\Communication\Plugin\ShoppingListDataImportPlugin;
use Spryker\Zed\ShoppingListDataImport\Communication\Plugin\ShoppingListItemDataImportPlugin;

/**
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
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
    public const IMPORT_TYPE_PRODUCT_RELATION = 'product-relation';
    public const IMPORT_TYPE_PRODUCT_REVIEW = 'product-review';
    public const IMPORT_TYPE_PRODUCT_LABEL = 'product-label';
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
    public const IMPORT_TYPE_SHIPMENT = 'shipment';
    public const IMPORT_TYPE_SHIPMENT_PRICE = 'shipment-price';
    public const IMPORT_TYPE_STOCK = 'stock';
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

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCurrencyDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('currency.csv', static::IMPORT_TYPE_CURRENCY);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getOrderSourceDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('order_source.csv', static::IMPORT_TYPE_ORDER_SOURCE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getStoreDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('', static::IMPORT_TYPE_STORE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getGlossaryDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('glossary.csv', static::IMPORT_TYPE_GLOSSARY);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCustomerDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('customer.csv', static::IMPORT_TYPE_CUSTOMER);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCategoryTemplateDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('category_template.csv', static::IMPORT_TYPE_CATEGORY_TEMPLATE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getTaxDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('tax.csv', static::IMPORT_TYPE_TAX);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductPriceDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('product_price.csv', static::IMPORT_TYPE_PRODUCT_PRICE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductStockDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('product_stock.csv', static::IMPORT_TYPE_PRODUCT_STOCK);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getStockDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('stock.csv', static::IMPORT_TYPE_STOCK);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getShipmentDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('shipment.csv', static::IMPORT_TYPE_SHIPMENT);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getShipmentPriceDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('shipment_price.csv', static::IMPORT_TYPE_SHIPMENT_PRICE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getNavigationDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('navigation.csv', static::IMPORT_TYPE_NAVIGATION);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getNavigationNodeDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('navigation_node.csv', static::IMPORT_TYPE_NAVIGATION_NODE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductAbstractDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('icecat_biz_data' . DIRECTORY_SEPARATOR . 'product_abstract.csv', static::IMPORT_TYPE_PRODUCT_ABSTRACT);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductAbstractStoreDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('icecat_biz_data' . DIRECTORY_SEPARATOR . 'product_abstract_store.csv', static::IMPORT_TYPE_PRODUCT_ABSTRACT_STORE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductConcreteDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('icecat_biz_data' . DIRECTORY_SEPARATOR . 'product_concrete.csv', static::IMPORT_TYPE_PRODUCT_CONCRETE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductAttributeKeyDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('product_attribute_key.csv', static::IMPORT_TYPE_PRODUCT_ATTRIBUTE_KEY);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductManagementAttributeDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('product_management_attribute.csv', static::IMPORT_TYPE_PRODUCT_MANAGEMENT_ATTRIBUTE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductRelationDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('product_relation.csv', static::IMPORT_TYPE_PRODUCT_RELATION);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductReviewDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('product_review.csv', static::IMPORT_TYPE_PRODUCT_REVIEW);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductLabelDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('product_label.csv', static::IMPORT_TYPE_PRODUCT_LABEL);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductSetDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('icecat_biz_data' . DIRECTORY_SEPARATOR . 'product_set.csv', static::IMPORT_TYPE_PRODUCT_SET);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductSearchAttributeMapDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('product_search_attribute_map.csv', static::IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE_MAP);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductSearchAttributeDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('product_search_attribute.csv', static::IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductGroupDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('product_group.csv', static::IMPORT_TYPE_PRODUCT_GROUP);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductOptionDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('product_option.csv', static::IMPORT_TYPE_PRODUCT_OPTION);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductOptionPriceDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('product_option_price.csv', static::IMPORT_TYPE_PRODUCT_OPTION_PRICE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getProductImageDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('icecat_biz_data' . DIRECTORY_SEPARATOR . 'product_image.csv', static::IMPORT_TYPE_PRODUCT_IMAGE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCmsTemplateDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('cms_template.csv', static::IMPORT_TYPE_CMS_TEMPLATE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCmsBlockDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('cms_block.csv', static::IMPORT_TYPE_CMS_BLOCK);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCmsBlockStoreDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('cms_block_store.csv', static::IMPORT_TYPE_CMS_BLOCK_STORE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCmsBlockCategoryPositionDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('cms_block_category_position.csv', static::IMPORT_TYPE_CMS_BLOCK_CATEGORY_POSITION);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getCmsBlockCategoryDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('cms_block_category.csv', static::IMPORT_TYPE_CMS_BLOCK_CATEGORY);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getDiscountDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('discount.csv', static::IMPORT_TYPE_DISCOUNT);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getDiscountStoreDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('discount_store.csv', static::IMPORT_TYPE_DISCOUNT_STORE);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getDiscountAmountDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('discount_amount.csv', static::IMPORT_TYPE_DISCOUNT_AMOUNT);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getDiscountVoucherDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('discount_voucher.csv', static::IMPORT_TYPE_DISCOUNT_VOUCHER);
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
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getAbstractGiftCardProductConfigurationDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('gift_card_abstract_configuration.csv', static::IMPORT_TYPE_ABSTRACT_GIFT_CARD_CONFIGURATION);
    }

    /**
     * @return \Generated\Shared\Transfer\DataImporterConfigurationTransfer
     */
    public function getConcreteGiftCardProductConfigurationDataImporterConfiguration()
    {
        return $this->buildImporterConfiguration('gift_card_concrete_configuration.csv', static::IMPORT_TYPE_CONCRETE_GIFT_CARD_CONFIGURATION);
    }

    /**
     * @return string[]
     */
    public function getFullImportPlugins(): array
    {
        return [
            CmsPageStoreDataImportPlugin::class,
            CmsPageDataImportPlugin::class,
            CompanyDataImportPlugin::class,
            CategoryDataImportPlugin::class,
            MerchantDataImportPlugin::class,
            MultiCartDataImportPlugin::class,
            SharedCartDataImportPlugin::class,
            CompanyUserRoleDataImportPlugin::class,
            CompanyRolePermissionDataImportPlugin::class,
            CompanyRoleDataImportPlugin::class,
            CompanyUserDataImportPlugin::class,
            FileManagerDataImportPlugin::class,
            ProductListCategoryDataImportPlugin::class,
            ProductListProductConcreteDataImportPlugin::class,
            ProductListDataImportPlugin::class,
            PriceProductDataImportPlugin::class,
            QuoteRequestDataImportPlugin::class,
            QuoteRequestVersionDataImportPlugin::class,
            ShoppingListCompanyUserDataImportPlugin::class,
            ShoppingListItemDataImportPlugin::class,
            ShoppingListDataImportPlugin::class,
            ShoppingListCompanyBusinessUnitDataImportPlugin::class,
            ContentBannerDataImportPlugin::class,
            ContentProductAbstractListDataImportPlugin::class,
            CompanySupplierDataImportPlugin::class,
            CompanySupplierProductPriceDataImportPlugin::class,
            CompanyTypeDataImportPlugin::class,
            ProductQuantityDataImportPlugin::class,
            BusinessOnBehalfCompanyUserDataImportPlugin::class,
            ContentProductSetDataImportPlugin::class,
            CompanyUnitAddressDataImportPlugin::class,
            ProductAlternativeDataImportPlugin::class,
            CompanyBusinessUnitUserDataImportPlugin::class,
            CompanyBusinessUnitAddressDataImportPlugin::class,
            CompanyBusinessUnitDataImportPlugin::class,
            ProductDiscontinuedDataImportPlugin::class,
            SalesOrderThresholdDataImportPlugin::class,
            MerchantRelationshipDataImportPlugin::class,
            PriceProductScheduleDataImportPlugin::class,
            ProductPackagingUnitTypeDataImportPlugin::class,
            ProductPackagingUnitDataImportPlugin::class,
            ProductMeasurementSalesUnitStoreDataImportPlugin::class,
            ProductMeasurementSalesUnitDataImportPlugin::class,
            ProductMeasurementUnitDataImportPlugin::class,
            ProductMeasurementBaseUnitDataImportPlugin::class,
            CompanyUnitAddressLabelDataImportPlugin::class,
            CompanyUnitAddressLabelRelationDataImportPlugin::class,
            MerchantRelationshipProductListDataImportPlugin::class,
            PriceProductMerchantRelationshipDataImportPlugin::class,
            MerchantRelationshipSalesOrderThresholdDataImportPlugin::class,
            CommentDataImportPlugin::class,
        ];
    }
}
