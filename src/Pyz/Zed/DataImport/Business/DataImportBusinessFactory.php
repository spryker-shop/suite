<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business;

use Generated\Shared\Transfer\DataImportConfigurationActionTransfer;
use Generated\Shared\Transfer\DataImporterConfigurationTransfer;
use Pyz\Zed\DataImport\Business\CombinedProduct\Product\CombinedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\Product\CombinedProductLocalizedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstract\CombinedProductAbstractHydratorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstract\CombinedProductAbstractTypeDataSetCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstract\Writer\CombinedProductAbstractBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstract\Writer\CombinedProductAbstractPropelDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore\CombinedProductAbstractStoreHydratorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore\CombinedProductAbstractStoreMandatoryColumnCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore\Writer\CombinedProductAbstractStoreBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore\Writer\CombinedProductAbstractStoreBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductAbstractStore\Writer\CombinedProductAbstractStorePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductConcrete\CombinedProductConcreteHydratorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductConcrete\CombinedProductConcreteTypeDataSetCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductConcrete\Writer\CombinedProductConcreteBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductConcrete\Writer\CombinedProductConcreteBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductConcrete\Writer\CombinedProductConcretePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductGroup\CombinedProductGroupMandatoryColumnCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductGroup\CombinedProductGroupWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductImage\CombinedProductImageHydratorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductImage\CombinedProductImageMandatoryColumnCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductImage\Writer\CombinedProductImageBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductImage\Writer\CombinedProductImageBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductImage\Writer\CombinedProductImagePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice\CombinedProductPriceHydratorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice\CombinedProductPriceMandatoryColumnCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice\Writer\CombinedProductPriceBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice\Writer\CombinedProductPriceBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductPrice\Writer\CombinedProductPricePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductStock\CombinedProductStockHydratorStep;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductStock\CombinedProductStockMandatoryColumnCondition;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductStock\Writer\CombinedProductStockBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductStock\Writer\CombinedProductStockBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\CombinedProduct\ProductStock\Writer\CombinedProductStockPropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\CategoryTemplate\CategoryTemplateWriterStep;
use Pyz\Zed\DataImport\Business\Model\CmsBlock\Category\Repository\CategoryRepository;
use Pyz\Zed\DataImport\Business\Model\CmsBlock\CmsBlockWriterStep;
use Pyz\Zed\DataImport\Business\Model\CmsBlockCategory\CmsBlockCategoryWriterStep;
use Pyz\Zed\DataImport\Business\Model\CmsBlockCategoryPosition\CmsBlockCategoryPositionWriterStep;
use Pyz\Zed\DataImport\Business\Model\CmsBlockStore\CmsBlockStoreWriterStep;
use Pyz\Zed\DataImport\Business\Model\CmsTemplate\CmsTemplateWriterStep;
use Pyz\Zed\DataImport\Business\Model\Country\Repository\CountryRepository;
use Pyz\Zed\DataImport\Business\Model\Currency\CurrencyWriterStep;
use Pyz\Zed\DataImport\Business\Model\Customer\CustomerWriterStep;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatter;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\DataImporterConditional;
use Pyz\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareConditional;
use Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface;
use Pyz\Zed\DataImport\Business\Model\Discount\DiscountWriterStep;
use Pyz\Zed\DataImport\Business\Model\DiscountAmount\DiscountAmountWriterStep;
use Pyz\Zed\DataImport\Business\Model\DiscountStore\DiscountStoreWriterStep;
use Pyz\Zed\DataImport\Business\Model\DiscountVoucher\DiscountVoucherWriterStep;
use Pyz\Zed\DataImport\Business\Model\GiftCard\GiftCardAbstractConfigurationWriterStep;
use Pyz\Zed\DataImport\Business\Model\GiftCard\GiftCardConcreteConfigurationWriterStep;
use Pyz\Zed\DataImport\Business\Model\Glossary\GlossaryWriterStep;
use Pyz\Zed\DataImport\Business\Model\Locale\AddLocalesStep;
use Pyz\Zed\DataImport\Business\Model\Locale\LocaleNameToIdLocaleStep;
use Pyz\Zed\DataImport\Business\Model\Locale\Repository\LocaleRepository;
use Pyz\Zed\DataImport\Business\Model\MerchantUser\MerchantUserWriterStep;
use Pyz\Zed\DataImport\Business\Model\Navigation\NavigationKeyToIdNavigationStep;
use Pyz\Zed\DataImport\Business\Model\Navigation\NavigationWriterStep;
use Pyz\Zed\DataImport\Business\Model\NavigationNode\NavigationNodeValidityDatesStep;
use Pyz\Zed\DataImport\Business\Model\NavigationNode\NavigationNodeWriterStep;
use Pyz\Zed\DataImport\Business\Model\OrderSource\OrderSourceWriterStep;
use Pyz\Zed\DataImport\Business\Model\Product\AttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Model\Product\ProductLocalizedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\AddCategoryKeysStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\AddProductAbstractSkusStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractCheckExistenceStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractSkuToIdProductAbstractStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\ProductAbstractBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\ProductAbstractBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\ProductAbstractPropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractMariaDbSql;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractSql;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\ProductAbstractStoreHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\ProductAbstractStoreBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\ProductAbstractStoreBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\ProductAbstractStorePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreMariaDbSql;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSql;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductAttributeKey\AddProductAttributeKeysStep;
use Pyz\Zed\DataImport\Business\Model\ProductAttributeKey\ProductAttributeKeyWriter;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteAttributesUniqueCheckStep;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteCheckExistenceStep;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductSkuToIdProductStep;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\ProductConcreteBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\ProductConcreteBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\ProductConcretePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteMariaDbSql;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteSql;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductGroup\ProductGroupWriter;
use Pyz\Zed\DataImport\Business\Model\ProductImage\ProductImageHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Repository\ProductImageRepository;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Repository\ProductImageRepositoryInterface;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\ProductImageBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\ProductImageBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\ProductImagePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageMariaDbSql;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSql;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductManagementAttribute\ProductManagementAttributeWriter;
use Pyz\Zed\DataImport\Business\Model\ProductManagementAttribute\ProductManagementLocalizedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Model\ProductOption\ProductOptionWriterStep;
use Pyz\Zed\DataImport\Business\Model\ProductOptionPrice\ProductOptionPriceWriterStep;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\ProductPriceHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\ProductPriceBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\ProductPriceBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\ProductPricePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceMariaDbSql;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceSql;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductReview\ProductReviewWriterStep;
use Pyz\Zed\DataImport\Business\Model\ProductSearchAttribute\Hook\ProductSearchAfterImportHook;
use Pyz\Zed\DataImport\Business\Model\ProductSearchAttribute\ProductSearchAttributeWriter;
use Pyz\Zed\DataImport\Business\Model\ProductSearchAttributeMap\ProductSearchAttributeMapWriter;
use Pyz\Zed\DataImport\Business\Model\ProductSet\ProductSetImageExtractorStep;
use Pyz\Zed\DataImport\Business\Model\ProductSet\ProductSetWriterStep;
use Pyz\Zed\DataImport\Business\Model\ProductStock\ProductStockHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Reader\ProductStockReader;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Reader\ProductStockReaderInterface;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\ProductStockBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\ProductStockBulkPdoMariaDbDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\ProductStockPropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockMariaDbSql;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSql;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutor;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Pyz\Zed\DataImport\Business\Model\Store\StoreReader;
use Pyz\Zed\DataImport\Business\Model\Store\StoreWriterStep;
use Pyz\Zed\DataImport\Business\Model\Tax\TaxSetNameToIdTaxSetStep;
use Pyz\Zed\DataImport\Business\Model\Tax\TaxWriterStep;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductAbstract\CombinedProductAbstractBulkPdoWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductAbstract\CombinedProductAbstractPropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductAbstractStore\CombinedProductAbstractStoreBulkPdoWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductAbstractStore\CombinedProductAbstractStorePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductConcrete\CombinedProductConcreteBulkPdoWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductConcrete\CombinedProductConcretePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductImage\CombinedProductImageBulkPdoWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductImage\CombinedProductImagePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductPrice\CombinedProductPriceBulkPdoWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductPrice\CombinedProductPricePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductStock\CombinedProductStockBulkPdoWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\CombinedProduct\ProductStock\CombinedProductStockPropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductAbstract\ProductAbstractBulkPdoWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductAbstract\ProductAbstractPropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductAbstractStore\ProductAbstractStoreBulkPdoWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductAbstractStore\ProductAbstractStorePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductConcrete\ProductConcreteBulkPdoWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductConcrete\ProductConcretePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductImage\ProductImageBulkPdoWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductImage\ProductImagePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductPrice\ProductPriceBulkPdoWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductPrice\ProductPricePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductStock\ProductStockBulkPdoWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductStock\ProductStockPropelWriterPlugin;
use Pyz\Zed\DataImport\DataImportConfig;
use Pyz\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductSearch\Code\KeyBuilder\FilterGlossaryKeyBuilder;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory as SprykerDataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\DetermineStrategy\DatabaseDetermineStrategy;
use Spryker\Zed\DataImport\Business\Model\DataImporterInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterCollection;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface;
use Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface;
use Spryker\Zed\Stock\Business\StockFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

/**
 * @method \Pyz\Zed\DataImport\DataImportConfig getConfig()
 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class DataImportBusinessFactory extends SprykerDataImportBusinessFactory
{
    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|null
     */
    public function getDataImporterByType(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer): ?DataImporterInterface
    {
        switch ($dataImportConfigurationActionTransfer->getDataEntity()) {
            case DataImportConfig::IMPORT_TYPE_STORE:
                return $this->createStoreImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CURRENCY:
                return $this->createCurrencyImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_ORDER_SOURCE:
                return $this->createOrderSourceImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CATEGORY_TEMPLATE:
                return $this->createCategoryTemplateImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CUSTOMER:
                return $this->createCustomerImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_GLOSSARY:
                return $this->createGlossaryImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_TAX:
                return $this->createTaxImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_DISCOUNT:
                return $this->createDiscountImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_DISCOUNT_STORE:
                return $this->createDiscountStoreImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_DISCOUNT_VOUCHER:
                return $this->createDiscountVoucherImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_ATTRIBUTE_KEY:
                return $this->createProductAttributeKeyImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_MANAGEMENT_ATTRIBUTE:
                return $this->createProductManagementAttributeImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_ABSTRACT:
                return $this->createProductAbstractImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_ABSTRACT_STORE:
                return $this->createProductAbstractStoreImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_CONCRETE:
                return $this->createProductConcreteImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_IMAGE:
                return $this->createProductImageImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_OPTION:
                return $this->createProductOptionImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_OPTION_PRICE:
                return $this->createProductOptionPriceImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_PRICE:
                return $this->createProductPriceImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_GROUP:
                return $this->createProductGroupImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_ABSTRACT:
                return $this->createCombinedProductAbstractImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_ABSTRACT_STORE:
                return $this->createCombinedProductAbstractStoreImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_CONCRETE:
                return $this->createCombinedProductConcreteImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_IMAGE:
                return $this->createCombinedProductImageImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_PRICE:
                return $this->createCombinedProductPriceImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_STOCK:
                return $this->createCombinedProductStockImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_COMBINED_PRODUCT_GROUP:
                return $this->createCombinedProductGroupImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_REVIEW:
                return $this->createProductReviewImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_SET:
                return $this->createProductSetImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE_MAP:
                return $this->createProductSearchAttributeMapImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_SEARCH_ATTRIBUTE:
                return $this->createProductSearchAttributeImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CMS_TEMPLATE:
                return $this->createCmsTemplateImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CMS_BLOCK:
                return $this->createCmsBlockImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CMS_BLOCK_STORE:
                return $this->createCmsBlockStoreImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CMS_BLOCK_CATEGORY_POSITION:
                return $this->createCmsBlockCategoryPositionImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CMS_BLOCK_CATEGORY:
                return $this->createCmsBlockCategoryImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_DISCOUNT_AMOUNT:
                return $this->createDiscountAmountImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_ABSTRACT_GIFT_CARD_CONFIGURATION:
                return $this->createAbstractGiftCardConfigurationImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_CONCRETE_GIFT_CARD_CONFIGURATION:
                return $this->createConcreteGiftCardConfigurationImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_PRODUCT_STOCK:
                return $this->createProductStockImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_NAVIGATION:
                return $this->createNavigationImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_NAVIGATION_NODE:
                return $this->createNavigationNodeImporter($dataImportConfigurationActionTransfer);
            case DataImportConfig::IMPORT_TYPE_MERCHANT_USER:
                return $this->createMerchantUserImporter($dataImportConfigurationActionTransfer);
            default:
                return null;
        }
    }

    /**
     * @param string $importType
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $reader
     *
     * @return \Pyz\Zed\DataImport\Business\Model\DataImporterConditional
     */
    public function createDataImporterConditional($importType, DataReaderInterface $reader)
    {
        return new DataImporterConditional($importType, $reader, $this->getGracefulRunnerFacade());
    }

    /**
     * @param string $importType
     * @param \Spryker\Zed\DataImport\Business\Model\DataReader\DataReaderInterface $reader
     *
     * @return \Pyz\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareConditional
     */
    public function createDataImporterWriterAwareConditional($importType, DataReaderInterface $reader)
    {
        return new DataImporterDataSetWriterAwareConditional($importType, $reader, $this->getGracefulRunnerFacade());
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
     *
     * @return \Pyz\Zed\DataImport\Business\Model\DataImporterConditional
     */
    public function getConditionalCsvDataImporterFromConfig(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
    {
        $csvReader = $this->createCsvReaderFromConfig($dataImporterConfigurationTransfer->getReaderConfiguration());

        return $this->createDataImporterConditional($dataImporterConfigurationTransfer->getImportType(), $csvReader);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImporterConfigurationTransfer $dataImporterConfigurationTransfer
     *
     * @return \Pyz\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareConditional
     */
    public function getConditionalCsvDataImporterWriterAwareFromConfig(DataImporterConfigurationTransfer $dataImporterConfigurationTransfer)
    {
        $csvReader = $this->createCsvReaderFromConfig($dataImporterConfigurationTransfer->getReaderConfiguration());

        return $this->createDataImporterWriterAwareConditional($dataImporterConfigurationTransfer->getImportType(), $csvReader);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductPriceBulkPdoDataSetWriter(): DataSetWriterInterface
    {
        $determineStrategy = new DatabaseDetermineStrategy(
            [
                $this->createCombinedProductPriceBulkPdoPostgresDataSetWriter(),
                $this->createCombinedProductPriceBulkPdoMariaDbDataSetWriter(),
            ]
        );

        return $determineStrategy->getApplicableDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductPriceBulkPdoPostgresDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductPriceBulkPdoDataSetWriter(
            $this->createProductPriceSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductPriceBulkPdoMariaDbDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductPriceBulkPdoMariaDbDataSetWriter(
            $this->createProductPriceMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductPricePropelDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductPricePropelDataSetWriter(
            $this->createProductRepository(),
            $this->getStoreFacade(),
            $this->getCurrencyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductImageBulkPdoDataSetWriter(): DataSetWriterInterface
    {
        $determineStrategy = new DatabaseDetermineStrategy(
            [
                $this->createCombinedProductImageBulkPdoPostgresDataSetWriter(),
                $this->createCombinedProductImageBulkPdoMariaDbDataSetWriter(),
            ]
        );

        return $determineStrategy->getApplicableDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductImageBulkPdoPostgresDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductImageBulkPdoDataSetWriter(
            $this->createProductImageSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductImageBulkPdoMariaDbDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductImageBulkPdoMariaDbDataSetWriter(
            $this->createProductImageMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductImagePropelDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductImagePropelDataSetWriter(
            $this->createProductImageRepository()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductStockBulkPdoDataSetWriter(): DataSetWriterInterface
    {
        $determineStrategy = new DatabaseDetermineStrategy(
            [
                $this->createCombinedProductStockBulkPdoPostgresDataSetWriter(),
                $this->createCombinedProductStockBulkPdoMariaDbDataSetWriter(),
            ]
        );

        return $determineStrategy->getApplicableDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductStockBulkPdoPostgresDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductStockBulkPdoDataSetWriter(
            $this->getStockFacade(),
            $this->getProductBundleFacade(),
            $this->createProductStockSql(),
            $this->createPropelExecutor(),
            $this->getStoreFacade(),
            $this->createDataFormatter(),
            $this->getConfig(),
            $this->createProductStockReader()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductStockBulkPdoMariaDbDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductStockBulkPdoMariaDbDataSetWriter(
            $this->getStockFacade(),
            $this->getProductBundleFacade(),
            $this->createProductStockMariaDbSql(),
            $this->createPropelExecutor(),
            $this->getStoreFacade(),
            $this->createDataFormatter(),
            $this->getConfig(),
            $this->createProductStockReader()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductStockPropelDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductStockPropelDataSetWriter(
            $this->getProductBundleFacade(),
            $this->createProductRepository(),
            $this->getStoreFacade(),
            $this->getStockFacade()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductAbstractStoreBulkPdoDataSetWriter(): DataSetWriterInterface
    {
        $determineStrategy = new DatabaseDetermineStrategy(
            [
                $this->createCombinedProductAbstractStoreBulkPdoPostgresDataSetWriter(),
                $this->createCombinedProductAbstractStoreBulkPdoMariaDbDataSetWriter(),
            ]
        );

        return $determineStrategy->getApplicableDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductAbstractStoreBulkPdoPostgresDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductAbstractStoreBulkPdoDataSetWriter(
            $this->createProductAbstractStoreSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductAbstractStoreBulkPdoMariaDbDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductAbstractStoreBulkPdoMariaDbDataSetWriter(
            $this->createProductAbstractStoreMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductAbstractStorePropelDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductAbstractStorePropelDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductAbstractBulkPdoDataSetWriter(): DataSetWriterInterface
    {
        $determineStrategy = new DatabaseDetermineStrategy(
            [
                $this->createCombinedProductAbstractBulkPostgresPdoDataSetWriter(),
            ]
        );

        return $determineStrategy->getApplicableDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductAbstractBulkPostgresPdoDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductAbstractBulkPdoDataSetWriter(
            $this->createProductAbstractSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductAbstractPropelDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductAbstractPropelDataSetWriter(
            $this->createProductRepository()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductConcreteBulkPdoDataSetWriter(): DataSetWriterInterface
    {
        $determineStrategy = new DatabaseDetermineStrategy(
            [
                $this->createCombinedProductConcreteBulkPdoPostgresDataSetWriter(),
                $this->createCombinedProductConcreteBulkPdoMariaDbDataSetWriter(),
            ]
        );

        return $determineStrategy->getApplicableDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductConcreteBulkPdoPostgresDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductConcreteBulkPdoDataSetWriter(
            $this->createProductConcreteSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductConcreteBulkPdoMariaDbDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductConcreteBulkPdoMariaDbDataSetWriter(
            $this->createProductConcreteMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createCombinedProductConcretePropelDataSetWriter(): DataSetWriterInterface
    {
        return new CombinedProductConcretePropelDataSetWriter(
            $this->createProductRepository()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCombinedProductPriceImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CombinedProductPriceHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new CombinedProductPriceHydratorStep(
                $this->getPriceProductFacade(),
                $this->getUtilEncodingService()
            ));

        $dataImporter->setDataSetCondition($this->createCombinedProductPriceMandatoryColumnCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createCombinedProductPriceDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductPriceMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedProductPriceMandatoryColumnCondition();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createCombinedProductPriceDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection(
            [
                $this->createCombinedProductPriceDataSetWriterPlugin(),
                $this->createCombinedProductPriceBulkPdoWriterPlugin(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createCombinedProductPriceDataSetWriterPlugin(): DataSetWriterPluginInterface
    {
        return new CombinedProductPricePropelWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createCombinedProductPriceBulkPdoWriterPlugin(): DataSetWriterPluginInterface
    {
        return new CombinedProductPriceBulkPdoWriterPlugin();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCombinedProductImageImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CombinedProductImageHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createProductAbstractSkuToIdProductAbstractStep(CombinedProductImageHydratorStep::COLUMN_ABSTRACT_SKU, ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT))
            ->addStep($this->createProductSkuToIdProductStep(CombinedProductImageHydratorStep::COLUMN_CONCRETE_SKU, ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT))
            ->addStep($this->createLocaleNameToIdStep(CombinedProductImageHydratorStep::COLUMN_LOCALE, ProductImageHydratorStep::KEY_IMAGE_SET_FK_LOCALE))
            ->addStep(new CombinedProductImageHydratorStep());

        $dataImporter->setDataSetCondition($this->createCombinedProductImageMandatoryColumnCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createCombinedProductImageDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductImageMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedProductImageMandatoryColumnCondition();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createCombinedProductImageDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection(
            [
                $this->createCombinedProductImageDataSetWriterPlugin(),
                $this->createCombinedProductImageBulkPdoWriterPlugin(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createCombinedProductImageDataSetWriterPlugin(): DataSetWriterPluginInterface
    {
        return new CombinedProductImagePropelWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createCombinedProductImageBulkPdoWriterPlugin(): DataSetWriterPluginInterface
    {
        return new CombinedProductImageBulkPdoWriterPlugin();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCombinedProductStockImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CombinedProductStockHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new CombinedProductStockHydratorStep());

        $dataImporter->setDataSetCondition($this->createCombinedProductStockMandatoryColumnCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createCombinedProductStockDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductStockMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedProductStockMandatoryColumnCondition();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createCombinedProductStockDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection(
            [
                $this->createCombinedProductStockDataSetWriterPlugins(),
                $this->createCombinedProductStockBulkPdoWriterPlugin(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createCombinedProductStockDataSetWriterPlugins(): DataSetWriterPluginInterface
    {
        return new CombinedProductStockPropelWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createCombinedProductStockBulkPdoWriterPlugin(): DataSetWriterPluginInterface
    {
        return new CombinedProductStockBulkPdoWriterPlugin();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCombinedProductAbstractStoreImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CombinedProductAbstractStoreHydratorStep::BULK_SIZE);
        $dataSetStepBroker->addStep(new CombinedProductAbstractStoreHydratorStep());

        $dataImporter->setDataSetCondition($this->createCombinedProductAbstractStoreMandatoryColumnCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createCombinedProductAbstractStoreDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductAbstractStoreMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedProductAbstractStoreMandatoryColumnCondition();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createCombinedProductAbstractStoreDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection(
            [
                $this->createCombinedProductAbstractStoreDataSetWriterPlugin(),
                $this->createCombinedProductAbstractStoreBulkPdoWriterPlugin(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createCombinedProductAbstractStoreDataSetWriterPlugin(): DataSetWriterPluginInterface
    {
        return new CombinedProductAbstractStorePropelWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createCombinedProductAbstractStoreBulkPdoWriterPlugin(): DataSetWriterPluginInterface
    {
        return new CombinedProductAbstractStoreBulkPdoWriterPlugin();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCombinedProductAbstractImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CombinedProductAbstractHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createProductAbstractCheckExistenceStep())
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createAddCategoryKeysStep())
            ->addStep($this->createTaxSetNameToIdTaxSetStep(CombinedProductAbstractHydratorStep::COLUMN_TAX_SET_NAME))
            ->addStep($this->createCombinedAttributesExtractorStep())
            ->addStep($this->createCombinedProductLocalizedAttributesExtractorStep([
                CombinedProductAbstractHydratorStep::COLUMN_NAME,
                CombinedProductAbstractHydratorStep::COLUMN_URL,
                CombinedProductAbstractHydratorStep::COLUMN_DESCRIPTION,
                CombinedProductAbstractHydratorStep::COLUMN_META_TITLE,
                CombinedProductAbstractHydratorStep::COLUMN_META_DESCRIPTION,
                CombinedProductAbstractHydratorStep::COLUMN_META_KEYWORDS,
            ]))
            ->addStep(new CombinedProductAbstractHydratorStep());

        $dataImporter->setDataSetCondition($this->createCombinedProductAbstractTypeDataSetCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createCombinedProductAbstractDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductAbstractTypeDataSetCondition(): DataSetConditionInterface
    {
        return new CombinedProductAbstractTypeDataSetCondition();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createCombinedProductAbstractDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection(
            [
                $this->createCombinedProductAbstractDataSetWriterPlugin(),
                $this->createCombinedProductAbstractBulkPdoWriterPlugin(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createCombinedProductAbstractDataSetWriterPlugin(): DataSetWriterPluginInterface
    {
        return new CombinedProductAbstractPropelWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createCombinedProductAbstractBulkPdoWriterPlugin(): DataSetWriterPluginInterface
    {
        return new CombinedProductAbstractBulkPdoWriterPlugin();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCombinedProductConcreteImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getConditionalCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CombinedProductConcreteHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createProductConcreteCheckExistenceStep())
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createCombinedAttributesExtractorStep())
            ->addStep($this->createProductConcreteAttributesUniqueCheckStep())
            ->addStep($this->createCombinedProductLocalizedAttributesExtractorStep([
                CombinedProductConcreteHydratorStep::COLUMN_NAME,
                CombinedProductConcreteHydratorStep::COLUMN_DESCRIPTION,
                CombinedProductConcreteHydratorStep::COLUMN_IS_SEARCHABLE,
            ]))
            ->addStep(new CombinedProductConcreteHydratorStep(
                $this->createProductRepository()
            ));

        $dataImporter->setDataSetCondition($this->createCombinedProductConcreteTypeDataSetCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createCombinedProductConcreteDataSetWriters());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductConcreteTypeDataSetCondition(): DataSetConditionInterface
    {
        return new CombinedProductConcreteTypeDataSetCondition();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createCombinedProductConcreteDataSetWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection(
            [
                $this->createCombinedProductConcreteDataSetWriterPlugin(),
                $this->createCombinedProductConcreteBulkPdoWriterPlugin(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createCombinedProductConcreteDataSetWriterPlugin(): DataSetWriterPluginInterface
    {
        return new CombinedProductConcretePropelWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createCombinedProductConcreteBulkPdoWriterPlugin(): DataSetWriterPluginInterface
    {
        return new CombinedProductConcreteBulkPdoWriterPlugin();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createCombinedProductGroupImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getConditionalCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductGroupWriter::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new CombinedProductGroupWriter($this->createProductRepository()));

        $dataImporter->setDataSetCondition($this->createCombinedProductGroupMandatoryColumnCondition());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataSet\DataSetConditionInterface
     */
    protected function createCombinedProductGroupMandatoryColumnCondition(): DataSetConditionInterface
    {
        return new CombinedProductGroupMandatoryColumnCondition();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductAbstractBulkPdoWriter(): DataSetWriterInterface
    {
        $determineStrategy = new DatabaseDetermineStrategy(
            [
                $this->createProductAbstractBulkPdoDataSetWriter(),
                $this->createProductAbstractBulkPdoMariaDbDataSetWriter(),
            ]
        );

        return $determineStrategy->getApplicableDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductAbstractBulkPdoDataSetWriter(): DataSetWriterInterface
    {
        return new ProductAbstractBulkPdoDataSetWriter(
            $this->createProductAbstractSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductAbstractBulkPdoMariaDbDataSetWriter(): DataSetWriterInterface
    {
        return new ProductAbstractBulkPdoMariaDbDataSetWriter(
            $this->createProductAbstractMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractSqlInterface
     */
    protected function createProductAbstractSql(): ProductAbstractSqlInterface
    {
        return new ProductAbstractSql();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractSqlInterface
     */
    protected function createProductAbstractMariaDbSql(): ProductAbstractSqlInterface
    {
        return new ProductAbstractMariaDbSql();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductAbstractPropelWriter(): DataSetWriterInterface
    {
        return new ProductAbstractPropelDataSetWriter(
            $this->createProductRepository()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductPriceBulkPdoWriter(): DataSetWriterInterface
    {
        $determineStrategy = new DatabaseDetermineStrategy(
            [
                $this->createProductPriceBulkPdoDataSetWriter(),
                $this->createProductPriceBulkPdoMariaDbDataSetWriter(),
            ]
        );

        return $determineStrategy->getApplicableDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductPriceBulkPdoDataSetWriter(): DataSetWriterInterface
    {
        return new ProductPriceBulkPdoDataSetWriter(
            $this->createProductPriceSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductPriceBulkPdoMariaDbDataSetWriter(): DataSetWriterInterface
    {
        return new ProductPriceBulkPdoMariaDbDataSetWriter(
            $this->createProductPriceMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceSqlInterface
     */
    protected function createProductPriceSql(): ProductPriceSqlInterface
    {
        return new ProductPriceSql();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceSqlInterface
     */
    protected function createProductPriceMariaDbSql(): ProductPriceSqlInterface
    {
        return new ProductPriceMariaDbSql();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductPricePropelWriter(): DataSetWriterInterface
    {
        return new ProductPricePropelDataSetWriter(
            $this->createProductRepository(),
            $this->getStoreFacade(),
            $this->getCurrencyFacade()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductAbstractStoreBulkPdoWriter(): DataSetWriterInterface
    {
        $determineStrategy = new DatabaseDetermineStrategy(
            [
                $this->createProductAbstractStoreBulkPdoDataSetWriter(),
                $this->createProductAbstractStoreBulkPdoMariaDbDataSetWriter(),
            ]
        );

        return $determineStrategy->getApplicableDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductAbstractStoreBulkPdoDataSetWriter(): DataSetWriterInterface
    {
        return new ProductAbstractStoreBulkPdoDataSetWriter(
            $this->createProductAbstractStoreSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductAbstractStoreBulkPdoMariaDbDataSetWriter(): DataSetWriterInterface
    {
        return new ProductAbstractStoreBulkPdoMariaDbDataSetWriter(
            $this->createProductAbstractStoreMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSqlInterface
     */
    public function createProductAbstractStoreSql(): ProductAbstractStoreSqlInterface
    {
        return new ProductAbstractStoreSql();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSqlInterface
     */
    public function createProductAbstractStoreMariaDbSql(): ProductAbstractStoreSqlInterface
    {
        return new ProductAbstractStoreMariaDbSql();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductAbstractStorePropelWriter(): DataSetWriterInterface
    {
        return new ProductAbstractStorePropelDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductConcretePropelWriter(): DataSetWriterInterface
    {
        return new ProductConcretePropelDataSetWriter($this->createProductRepository());
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductConcreteBulkPdoWriter(): DataSetWriterInterface
    {
        $determineStrategy = new DatabaseDetermineStrategy(
            [
                $this->createProductConcreteBulkPdoDataSetWriter(),
                $this->createProductConcreteBulkPdoMariaDbDataSetWriter(),
            ]
        );

        return $determineStrategy->getApplicableDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductConcreteBulkPdoDataSetWriter(): DataSetWriterInterface
    {
        return new ProductConcreteBulkPdoDataSetWriter(
            $this->createProductConcreteSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductConcreteBulkPdoMariaDbDataSetWriter(): DataSetWriterInterface
    {
        return new ProductConcreteBulkPdoMariaDbDataSetWriter(
            $this->createProductConcreteMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteSqlInterface
     */
    public function createProductConcreteSql(): ProductConcreteSqlInterface
    {
        return new ProductConcreteSql();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteSqlInterface
     */
    public function createProductConcreteMariaDbSql(): ProductConcreteSqlInterface
    {
        return new ProductConcreteMariaDbSql();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    public function createPropelExecutor(): PropelExecutorInterface
    {
        return new PropelExecutor();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductConcreteImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterDataSetWriterAwareInterface $dataImporter */
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductConcreteHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createProductConcreteCheckExistenceStep())
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createAttributesExtractorStep())
            ->addStep($this->createProductConcreteAttributesUniqueCheckStep())
            ->addStep($this->createProductLocalizedAttributesExtractorStep([
                ProductConcreteHydratorStep::COLUMN_NAME,
                ProductConcreteHydratorStep::COLUMN_DESCRIPTION,
                ProductConcreteHydratorStep::COLUMN_IS_SEARCHABLE,
            ]))
            ->addStep(new ProductConcreteHydratorStep(
                $this->createProductRepository()
            ));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createProductConcreteDataImportWriters());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductConcreteAttributesUniqueCheckStep(): DataImportStepInterface
    {
        return new ProductConcreteAttributesUniqueCheckStep(
            $this->createProductRepository(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductConcreteCheckExistenceStep()
    {
        return new ProductConcreteCheckExistenceStep(
            $this->createProductRepository()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductImagePropelWriter(): DataSetWriterInterface
    {
        return new ProductImagePropelDataSetWriter(
            $this->createProductImageRepository()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductImageBulkPdoWriter(): DataSetWriterInterface
    {
        $determineStrategy = new DatabaseDetermineStrategy(
            [
                $this->createProductImageBulkPdoDataSetWriter(),
                $this->createProductImageBulkPdoMariaDbDataSetWriter(),
            ]
        );

        return $determineStrategy->getApplicableDataSetWriter();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\ProductImageBulkPdoMariaDbDataSetWriter
     */
    public function createProductImageBulkPdoMariaDbDataSetWriter(): ProductImageBulkPdoMariaDbDataSetWriter
    {
        return new ProductImageBulkPdoMariaDbDataSetWriter(
            $this->createProductImageMariaDbSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\ProductImageBulkPdoDataSetWriter
     */
    public function createProductImageBulkPdoDataSetWriter(): ProductImageBulkPdoDataSetWriter
    {
        return new ProductImageBulkPdoDataSetWriter(
            $this->createProductImageSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface
     */
    public function createProductImageSql(): ProductImageSqlInterface
    {
        return new ProductImageSql();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageMariaDbSql
     */
    public function createProductImageMariaDbSql(): ProductImageSqlInterface
    {
        return new ProductImageMariaDbSql();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductStockPropelWriter(): DataSetWriterInterface
    {
        return new ProductStockPropelDataSetWriter(
            $this->getProductBundleFacade(),
            $this->createProductRepository(),
            $this->getStoreFacade(),
            $this->getStockFacade()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductStockBulkPdoWriter(): DataSetWriterInterface
    {
        $determineStrategy = new DatabaseDetermineStrategy(
            [
                $this->createProductStockBulkPdoDataSetWriter(),
                $this->createProductStockBulkPdoMariaDbDataSetWriter(),
            ]
        );

        return $determineStrategy->getApplicableDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductStockBulkPdoDataSetWriter(): DataSetWriterInterface
    {
        return new ProductStockBulkPdoDataSetWriter(
            $this->getStockFacade(),
            $this->getProductBundleFacade(),
            $this->createProductStockSql(),
            $this->createPropelExecutor(),
            $this->getStoreFacade(),
            $this->createDataFormatter(),
            $this->getConfig(),
            $this->createProductStockReader()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductStockBulkPdoMariaDbDataSetWriter(): DataSetWriterInterface
    {
        return new ProductStockBulkPdoMariaDbDataSetWriter(
            $this->getStockFacade(),
            $this->getProductBundleFacade(),
            $this->createProductStockMariaDbSql(),
            $this->createPropelExecutor(),
            $this->getStoreFacade(),
            $this->createDataFormatter(),
            $this->getConfig(),
            $this->createProductStockReader()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSqlInterface
     */
    public function createProductStockSql(): ProductStockSqlInterface
    {
        return new ProductStockSql();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSqlInterface
     */
    public function createProductStockMariaDbSql(): ProductStockSqlInterface
    {
        return new ProductStockMariaDbSql();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createCurrencyImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new CurrencyWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createOrderSourceImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new OrderSourceWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createStoreImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface $dataImporter */
        $dataImporter = $this->createDataImporter(
            $dataImportConfigurationActionTransfer->getDataEntity(),
            new StoreReader(
                $this->createDataSet(
                    Store::getInstance()->getAllowedStores()
                )
            )
        );

        $dataSetStepBroker = $this->createDataSetStepBroker();
        $dataSetStepBroker->addStep(new StoreWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createGlossaryImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(GlossaryWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createLocaleNameToIdStep(GlossaryWriterStep::KEY_LOCALE))
            ->addStep(new GlossaryWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createCategoryTemplateImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new CategoryTemplateWriterStep());

        $dataImporter
            ->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createCustomerImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new CustomerWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createCmsTemplateImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new CmsTemplateWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createCmsBlockImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CmsBlockWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createLocalizedAttributesExtractorStep([
                CmsBlockWriterStep::KEY_PLACEHOLDER_TITLE,
                CmsBlockWriterStep::KEY_PLACEHOLDER_DESCRIPTION,
                CmsBlockWriterStep::KEY_PLACEHOLDER_CONTENT,
            ]))
            ->addStep(new CmsBlockWriterStep(
                $this->createCategoryRepository(),
                $this->createProductRepository()
            ));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\CmsBlock\Category\Repository\CategoryRepositoryInterface
     */
    protected function createCategoryRepository()
    {
        return new CategoryRepository();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createCmsBlockStoreImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CmsBlockStoreWriterStep::BULK_SIZE);
        $dataSetStepBroker->addStep(new CmsBlockStoreWriterStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createCmsBlockCategoryPositionImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new CmsBlockCategoryPositionWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createCmsBlockCategoryImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new CmsBlockCategoryWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createDiscountImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(DiscountWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new DiscountWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createDiscountStoreImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(DiscountStoreWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new DiscountStoreWriterStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createDiscountAmountImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(DiscountAmountWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new DiscountAmountWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createDiscountVoucherImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(DiscountVoucherWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new DiscountVoucherWriterStep($this->createDiscountConfig()));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\Discount\DiscountConfig
     */
    protected function createDiscountConfig()
    {
        return new DiscountConfig();
    }

    /**
     * @deprecated Only used for testing.
     *
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductPriceImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface $dataImporter */
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductPriceHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new ProductPriceHydratorStep(
                $this->getPriceProductFacade(),
                $this->getUtilEncodingService()
            ));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createProductPriceDataImportWriters());

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createProductOptionImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createTaxSetNameToIdTaxSetStep(ProductOptionWriterStep::KEY_TAX_SET_NAME))
            ->addStep($this->createLocalizedAttributesExtractorStep([
                ProductOptionWriterStep::KEY_GROUP_NAME,
                ProductOptionWriterStep::KEY_OPTION_NAME,
            ]))
            ->addStep(new ProductOptionWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createProductOptionPriceImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new ProductOptionPriceWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductStockImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface $dataImporter */
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductStockHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new ProductStockHydratorStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker)
            ->setDataSetWriter($this->createProductStockDataImportWriters());

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createAbstractGiftCardConfigurationImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(GiftCardAbstractConfigurationWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new GiftCardAbstractConfigurationWriterStep($this->createProductRepository()));
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createConcreteGiftCardConfigurationImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(GiftCardConcreteConfigurationWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new GiftCardConcreteConfigurationWriterStep($this->createProductRepository()));
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductPriceDataImportWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection(
            [
                $this->createProductPricePropelWriterPlugin(),
                $this->createProductPriceBulkPdoWriterPlugin(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createProductPricePropelWriterPlugin(): DataSetWriterPluginInterface
    {
        return new ProductPricePropelWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createProductPriceBulkPdoWriterPlugin(): DataSetWriterPluginInterface
    {
        return new ProductPriceBulkPdoWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductStockDataImportWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection(
            [
                $this->createProductStockPropelWriterPlugin(),
                $this->createProductStockBulkPdoWriterPlugin(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createProductStockPropelWriterPlugin(): DataSetWriterPluginInterface
    {
        return new ProductStockPropelWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createProductStockBulkPdoWriterPlugin(): DataSetWriterPluginInterface
    {
        return new ProductStockBulkPdoWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    protected function getStockFacade(): StockFacadeInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_STOCK);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface
     */
    protected function getProductBundleFacade()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_PRODUCT_BUNDLE);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductImageImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface $dataImporter */
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductImageHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createProductAbstractSkuToIdProductAbstractStep(ProductImageHydratorStep::COLUMN_ABSTRACT_SKU, ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT))
            ->addStep($this->createProductSkuToIdProductStep(ProductImageHydratorStep::COLUMN_CONCRETE_SKU, ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT))
            ->addStep($this->createLocaleNameToIdStep(ProductImageHydratorStep::COLUMN_LOCALE, ProductImageHydratorStep::KEY_IMAGE_SET_FK_LOCALE))
            ->addStep(new ProductImageHydratorStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createProductImageDataWriters());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductImageDataWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection(
            [
                $this->createProductImagePropelWriterPlugin(),
                $this->createProductImageBulkPdoWriterPlugin(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createProductImageBulkPdoWriterPlugin(): DataSetWriterPluginInterface
    {
        return new ProductImageBulkPdoWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createProductImagePropelWriterPlugin(): DataSetWriterPluginInterface
    {
        return new ProductImagePropelWriterPlugin();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\Locale\Repository\LocaleRepositoryInterface
     */
    protected function createLocaleRepository()
    {
        return new LocaleRepository();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createTaxImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(TaxWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new TaxWriterStep($this->createCountryRepository()));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\Country\Repository\CountryRepositoryInterface
     */
    protected function createCountryRepository()
    {
        return new CountryRepository();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createNavigationImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(NavigationWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new NavigationWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createNavigationNodeImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(NavigationNodeWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createNavigationKeyToIdNavigationStep(NavigationNodeWriterStep::KEY_NAVIGATION_KEY))
            ->addStep($this->createLocalizedAttributesExtractorStep([
                NavigationNodeWriterStep::KEY_TITLE,
                NavigationNodeWriterStep::KEY_URL,
                NavigationNodeWriterStep::KEY_CSS_CLASS,
            ]))
            ->addStep($this->createNavigationNodeValidityDatesStep(NavigationNodeWriterStep::KEY_VALID_FROM, NavigationNodeWriterStep::KEY_VALID_TO))
            ->addStep(new NavigationNodeWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param string $source
     * @param string $target
     *
     * @return \Pyz\Zed\DataImport\Business\Model\Navigation\NavigationKeyToIdNavigationStep
     */
    protected function createNavigationKeyToIdNavigationStep(
        $source = NavigationKeyToIdNavigationStep::KEY_SOURCE,
        $target = NavigationKeyToIdNavigationStep::KEY_TARGET
    ) {
        return new NavigationKeyToIdNavigationStep($source, $target);
    }

    /**
     * @param string $keyValidFrom
     * @param string $keyValidTo
     *
     * @return \Pyz\Zed\DataImport\Business\Model\NavigationNode\NavigationNodeValidityDatesStep
     */
    protected function createNavigationNodeValidityDatesStep(string $keyValidFrom, string $keyValidTo)
    {
        return new NavigationNodeValidityDatesStep($keyValidFrom, $keyValidTo);
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductAbstractImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface $dataImporter */
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductAbstractHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createProductAbstractCheckExistenceStep())
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createAddCategoryKeysStep())
            ->addStep($this->createTaxSetNameToIdTaxSetStep(ProductAbstractHydratorStep::COLUMN_TAX_SET_NAME))
            ->addStep($this->createAttributesExtractorStep())
            ->addStep($this->createProductLocalizedAttributesExtractorStep([
                ProductAbstractHydratorStep::COLUMN_NAME,
                ProductAbstractHydratorStep::COLUMN_URL,
                ProductAbstractHydratorStep::COLUMN_DESCRIPTION,
                ProductAbstractHydratorStep::COLUMN_META_TITLE,
                ProductAbstractHydratorStep::COLUMN_META_DESCRIPTION,
                ProductAbstractHydratorStep::COLUMN_META_KEYWORDS,
            ]))
            ->addStep(new ProductAbstractHydratorStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createProductAbstractDataImportWriters());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    public function createProductAbstractCheckExistenceStep()
    {
        return new ProductAbstractCheckExistenceStep(
            $this->createProductRepository()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductAbstractDataImportWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection(
            [
                $this->createProductAbstractPropelWriterPlugin(),
                $this->createProductAbstractBulkPdoWriterPlugin(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createProductAbstractPropelWriterPlugin(): DataSetWriterPluginInterface
    {
        return new ProductAbstractPropelWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createProductAbstractBulkPdoWriterPlugin(): DataSetWriterPluginInterface
    {
        return new ProductAbstractBulkPdoWriterPlugin();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createProductAbstractStoreImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface $dataImporter */
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductAbstractStoreHydratorStep::BULK_SIZE);
        $dataSetStepBroker->addStep(new ProductAbstractStoreHydratorStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createProductAbstractStoreDataImportWriters());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductAbstractStoreDataImportWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection(
            [
                $this->createProductAbstractStorePropelWriterPlugin(),
                $this->createProductAbstractStoreBulkPdoWriterPlugin(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createProductAbstractStorePropelWriterPlugin(): DataSetWriterPluginInterface
    {
        return new ProductAbstractStorePropelWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createProductAbstractStoreBulkPdoWriterPlugin(): DataSetWriterPluginInterface
    {
        return new ProductAbstractStoreBulkPdoWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductConcreteDataImportWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection(
            [
                $this->createProductConcretePropelWriterPlugin(),
                $this->createProductConcreteBulkPdoWriterPlugin(),
            ]
        );
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createProductConcretePropelWriterPlugin(): DataSetWriterPluginInterface
    {
        return new ProductConcretePropelWriterPlugin();
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface
     */
    protected function createProductConcreteBulkPdoWriterPlugin(): DataSetWriterPluginInterface
    {
        return new ProductConcreteBulkPdoWriterPlugin();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository
     */
    protected function createProductRepository()
    {
        return new ProductRepository();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createProductAttributeKeyImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new ProductAttributeKeyWriter());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createProductManagementAttributeImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createAddProductAttributeKeysStep())
            ->addStep($this->createProductManagementLocalizedAttributesExtractorStep())
            ->addStep(new ProductManagementAttributeWriter());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductManagementAttribute\ProductManagementLocalizedAttributesExtractorStep
     */
    protected function createProductManagementLocalizedAttributesExtractorStep()
    {
        return new ProductManagementLocalizedAttributesExtractorStep();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createProductGroupImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductGroupWriter::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new ProductGroupWriter(
                $this->createProductRepository()
            ));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createProductReviewImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new ProductReviewWriterStep(
            $this->createProductRepository(),
            $this->createLocaleRepository()
        ));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createProductSetImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddProductAbstractSkusStep())
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createProductSetImageExtractorStep())
            ->addStep($this->createLocalizedAttributesExtractorStep([
                ProductSetWriterStep::KEY_NAME,
                ProductSetWriterStep::KEY_URL,
                ProductSetWriterStep::KEY_DESCRIPTION,
                ProductSetWriterStep::KEY_META_TITLE,
                ProductSetWriterStep::KEY_META_DESCRIPTION,
                ProductSetWriterStep::KEY_META_KEYWORDS,
            ]))
            ->addStep(new ProductSetWriterStep(
                $this->createProductRepository()
            ));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductSet\ProductSetImageExtractorStep
     */
    protected function createProductSetImageExtractorStep()
    {
        return new ProductSetImageExtractorStep();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createProductSearchAttributeMapImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddProductAttributeKeysStep())
            ->addStep(new ProductSearchAttributeMapWriter());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createProductSearchAttributeImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createAddProductAttributeKeysStep())
            ->addStep($this->createLocalizedAttributesExtractorStep([ProductSearchAttributeWriter::KEY]))
            ->addStep(new ProductSearchAttributeWriter(
                $this->createSearchGlossaryKeyBuilder()
            ));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->addAfterImportHook($this->createProductSearchAfterImportHook());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductSearchAttribute\Hook\ProductSearchAfterImportHook
     */
    protected function createProductSearchAfterImportHook()
    {
        return new ProductSearchAfterImportHook();
    }

    /**
     * @return \Spryker\Shared\ProductSearch\Code\KeyBuilder\FilterGlossaryKeyBuilder
     */
    protected function createSearchGlossaryKeyBuilder()
    {
        return new FilterGlossaryKeyBuilder();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductAbstract\AddCategoryKeysStep
     */
    protected function createAddCategoryKeysStep()
    {
        return new AddCategoryKeysStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\Product\AttributesExtractorStep
     */
    protected function createAttributesExtractorStep()
    {
        return new AttributesExtractorStep();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\Product\AttributesExtractorStep
     */
    protected function createCombinedAttributesExtractorStep()
    {
        return new CombinedAttributesExtractorStep();
    }

    /**
     * @param array $defaultAttributes
     *
     * @return \Pyz\Zed\DataImport\Business\Model\Product\ProductLocalizedAttributesExtractorStep
     */
    protected function createProductLocalizedAttributesExtractorStep(array $defaultAttributes = [])
    {
        return new ProductLocalizedAttributesExtractorStep($defaultAttributes);
    }

    /**
     * @param array $defaultAttributes
     *
     * @return \Pyz\Zed\DataImport\Business\Model\Product\ProductLocalizedAttributesExtractorStep
     */
    protected function createCombinedProductLocalizedAttributesExtractorStep(array $defaultAttributes = [])
    {
        return new CombinedProductLocalizedAttributesExtractorStep($defaultAttributes);
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductAbstract\AddProductAbstractSkusStep
     */
    protected function createAddProductAbstractSkusStep()
    {
        return new AddProductAbstractSkusStep();
    }

    /**
     * @param string $source
     * @param string $target
     *
     * @return \Pyz\Zed\DataImport\Business\Model\Locale\LocaleNameToIdLocaleStep
     */
    protected function createLocaleNameToIdStep(
        $source = LocaleNameToIdLocaleStep::KEY_SOURCE,
        $target = LocaleNameToIdLocaleStep::KEY_TARGET
    ) {
        return new LocaleNameToIdLocaleStep($source, $target);
    }

    /**
     * @param string $source
     * @param string $target
     *
     * @return \Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractSkuToIdProductAbstractStep
     */
    protected function createProductAbstractSkuToIdProductAbstractStep(
        string $source = ProductAbstractSkuToIdProductAbstractStep::KEY_SOURCE,
        string $target = ProductAbstractSkuToIdProductAbstractStep::KEY_TARGET
    ): ProductAbstractSkuToIdProductAbstractStep {
        return new ProductAbstractSkuToIdProductAbstractStep($source, $target);
    }

    /**
     * @param string $source
     * @param string $target
     *
     * @return \Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductSkuToIdProductStep
     */
    protected function createProductSkuToIdProductStep(
        string $source = ProductSkuToIdProductStep::KEY_SOURCE,
        string $target = ProductSkuToIdProductStep::KEY_TARGET
    ): ProductSkuToIdProductStep {
        return new ProductSkuToIdProductStep($source, $target);
    }

    /**
     * @param string $source
     * @param string $target
     *
     * @return \Pyz\Zed\DataImport\Business\Model\Tax\TaxSetNameToIdTaxSetStep
     */
    protected function createTaxSetNameToIdTaxSetStep(
        $source = TaxSetNameToIdTaxSetStep::KEY_SOURCE,
        $target = TaxSetNameToIdTaxSetStep::KEY_TARGET
    ) {
        return new TaxSetNameToIdTaxSetStep($source, $target);
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductAttributeKey\AddProductAttributeKeysStep
     */
    protected function createAddProductAttributeKeysStep()
    {
        return new AddProductAttributeKeysStep();
    }

    /**
     * @return \Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface
     */
    protected function getEventFacade(): DataImportToEventFacadeInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_EVENT);
    }

    /**
     * @return \Spryker\Zed\Store\Business\StoreFacadeInterface
     */
    protected function getStoreFacade(): StoreFacadeInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\Currency\Business\CurrencyFacadeInterface
     */
    protected function getCurrencyFacade(): CurrencyFacadeInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_CURRENCY);
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface
     */
    protected function createDataFormatter(): DataImportDataFormatterInterface
    {
        return new DataImportDataFormatter(
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    public function getPriceProductFacade(): PriceProductFacadeInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_PRICE_PRODUCT);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createAddLocalesStep(): DataImportStepInterface
    {
        return new AddLocalesStep($this->getStore());
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductImage\Repository\ProductImageRepositoryInterface
     */
    public function createProductImageRepository(): ProductImageRepositoryInterface
    {
        return new ProductImageRepository();
    }

    /**
     * @param \Generated\Shared\Transfer\DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    public function createMerchantUserImporter(DataImportConfigurationActionTransfer $dataImportConfigurationActionTransfer)
    {
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->buildImporterConfigurationByDataImportConfigAction($dataImportConfigurationActionTransfer)
        );

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new MerchantUserWriterStep(
            $this->getMerchantUserFacade()
        ));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductStock\Reader\ProductStockReaderInterface
     */
    public function createProductStockReader(): ProductStockReaderInterface
    {
        return new ProductStockReader();
    }

    /**
     * @return \Spryker\Zed\MerchantUser\Business\MerchantUserFacadeInterface
     */
    public function getMerchantUserFacade(): MerchantUserFacadeInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_MERCHANT_USER);
    }
}
