<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business;

use Pyz\Zed\DataImport\Business\Model\CategoryTemplate\CategoryTemplateWriterStep;
use Pyz\Zed\DataImport\Business\Model\CmsBlock\Category\Repository\CategoryRepository;
use Pyz\Zed\DataImport\Business\Model\CmsBlock\CmsBlockWriterStep;
use Pyz\Zed\DataImport\Business\Model\CmsBlockCategory\CmsBlockCategoryWriterStep;
use Pyz\Zed\DataImport\Business\Model\CmsBlockCategoryPosition\CmsBlockCategoryPositionWriterStep;
use Pyz\Zed\DataImport\Business\Model\CmsBlockStore\CmsBlockStoreWriterStep;
use Pyz\Zed\DataImport\Business\Model\CmsPage\CmsPageWriterStep;
use Pyz\Zed\DataImport\Business\Model\CmsPage\PlaceholderExtractorStep;
use Pyz\Zed\DataImport\Business\Model\CmsTemplate\CmsTemplateWriterStep;
use Pyz\Zed\DataImport\Business\Model\Country\Repository\CountryRepository;
use Pyz\Zed\DataImport\Business\Model\Currency\CurrencyWriterStep;
use Pyz\Zed\DataImport\Business\Model\Customer\CustomerWriterStep;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatter;
use Pyz\Zed\DataImport\Business\Model\DataFormatter\DataImportDataFormatterInterface;
use Pyz\Zed\DataImport\Business\Model\Discount\DiscountWriterStep;
use Pyz\Zed\DataImport\Business\Model\DiscountAmount\DiscountAmountWriterStep;
use Pyz\Zed\DataImport\Business\Model\DiscountStore\DiscountStoreWriterStep;
use Pyz\Zed\DataImport\Business\Model\DiscountVoucher\DiscountVoucherWriterStep;
use Pyz\Zed\DataImport\Business\Model\Glossary\GlossaryWriterStep;
use Pyz\Zed\DataImport\Business\Model\Locale\LocaleNameToIdLocaleStep;
use Pyz\Zed\DataImport\Business\Model\Locale\Repository\LocaleRepository;
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
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\ProductAbstractSkuToIdProductAbstractStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\ProductAbstractBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\ProductAbstractPropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractSql;
use Pyz\Zed\DataImport\Business\Model\ProductAbstract\Writer\Sql\ProductAbstractSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\ProductAbstractStoreHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\ProductAbstractStoreBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\ProductAbstractStorePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSql;
use Pyz\Zed\DataImport\Business\Model\ProductAbstractStore\Writer\Sql\ProductAbstractStoreSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductAttributeKey\AddProductAttributeKeysStep;
use Pyz\Zed\DataImport\Business\Model\ProductAttributeKey\ProductAttributeKeyWriter;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductConcreteHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\ProductSkuToIdProductStep;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\ProductConcreteBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\ProductConcretePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteSql;
use Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductGroup\ProductGroupWriter;
use Pyz\Zed\DataImport\Business\Model\ProductImage\ProductImageHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\ProductImageBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\ProductImagePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSql;
use Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductLabel\Hook\ProductLabelAfterImportPublishHook;
use Pyz\Zed\DataImport\Business\Model\ProductLabel\ProductLabelWriterStep;
use Pyz\Zed\DataImport\Business\Model\ProductManagementAttribute\ProductManagementAttributeWriter;
use Pyz\Zed\DataImport\Business\Model\ProductManagementAttribute\ProductManagementLocalizedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Model\ProductOption\ProductOptionWriterStep;
use Pyz\Zed\DataImport\Business\Model\ProductOptionPrice\ProductOptionPriceWriterStep;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\ProductPriceHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\ProductPriceBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\ProductPricePropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceSql;
use Pyz\Zed\DataImport\Business\Model\ProductPrice\Writer\Sql\ProductPriceSqlInterface;
use Pyz\Zed\DataImport\Business\Model\ProductRelation\Hook\ProductRelationAfterImportHook;
use Pyz\Zed\DataImport\Business\Model\ProductRelation\ProductRelationWriter;
use Pyz\Zed\DataImport\Business\Model\ProductReview\ProductReviewWriterStep;
use Pyz\Zed\DataImport\Business\Model\ProductSearchAttribute\Hook\ProductSearchAfterImportHook;
use Pyz\Zed\DataImport\Business\Model\ProductSearchAttribute\ProductSearchAttributeWriter;
use Pyz\Zed\DataImport\Business\Model\ProductSearchAttributeMap\ProductSearchAttributeMapWriter;
use Pyz\Zed\DataImport\Business\Model\ProductSet\ProductSetImageExtractorStep;
use Pyz\Zed\DataImport\Business\Model\ProductSet\ProductSetWriterStep;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Hook\ProductStockAfterImportPublishHook;
use Pyz\Zed\DataImport\Business\Model\ProductStock\ProductStockHydratorStep;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\ProductStockBulkPdoDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\ProductStockPropelDataSetWriter;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSql;
use Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSqlInterface;
use Pyz\Zed\DataImport\Business\Model\PropelExecutor;
use Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface;
use Pyz\Zed\DataImport\Business\Model\Shipment\ShipmentWriterStep;
use Pyz\Zed\DataImport\Business\Model\ShipmentPrice\ShipmentPriceWriterStep;
use Pyz\Zed\DataImport\Business\Model\Stock\StockWriterStep;
use Pyz\Zed\DataImport\Business\Model\Store\StoreReader;
use Pyz\Zed\DataImport\Business\Model\Store\StoreWriterStep;
use Pyz\Zed\DataImport\Business\Model\Tax\TaxSetNameToIdTaxSetStep;
use Pyz\Zed\DataImport\Business\Model\Tax\TaxWriterStep;
use Pyz\Zed\DataImport\Communication\Plugin\ProductAbstract\ProductAbstractPropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductAbstractStore\ProductAbstractStorePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductConcrete\ProductConcretePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductImage\ProductImagePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductPrice\ProductPricePropelWriterPlugin;
use Pyz\Zed\DataImport\Communication\Plugin\ProductStock\ProductStockPropelWriterPlugin;
use Pyz\Zed\DataImport\DataImportDependencyProvider;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\ProductSearch\Code\KeyBuilder\FilterGlossaryKeyBuilder;
use Spryker\Zed\Currency\Business\CurrencyFacadeInterface;
use Spryker\Zed\DataImport\Business\DataImportBusinessFactory as SprykerDataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterCollection;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToEventFacadeInterface;
use Spryker\Zed\Discount\DiscountConfig;
use Spryker\Zed\Stock\Business\StockFacadeInterface;
use Spryker\Zed\Store\Business\StoreFacadeInterface;

/**
 * @method \Pyz\Zed\DataImport\DataImportConfig getConfig()
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.ExcessiveClassLength)
 */
class DataImportBusinessFactory extends SprykerDataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface
     */
    public function getImporter()
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporterPluginCollectionInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface $dataImporterCollection */
        $dataImporterCollection = $this->createDataImporterCollection();
        $dataImporterCollection
            ->addDataImporter($this->createStoreImporter())
            ->addDataImporter($this->createCurrencyImporter())
            ->addDataImporter($this->createOrderSourceImporter())
            ->addDataImporter($this->createCategoryTemplateImporter())
            ->addDataImporter($this->createCustomerImporter())
            ->addDataImporter($this->createGlossaryImporter())
            ->addDataImporter($this->createTaxImporter())
            ->addDataImporter($this->createShipmentImporter())
            ->addDataImporter($this->createShipmentPriceImporter())
            ->addDataImporter($this->createDiscountImporter())
            ->addDataImporter($this->createDiscountStoreImporter())
            ->addDataImporter($this->createDiscountVoucherImporter())
            ->addDataImporter($this->createStockImporter())
            ->addDataImporter($this->createProductAttributeKeyImporter())
            ->addDataImporter($this->createProductManagementAttributeImporter())
            ->addDataImporter($this->createProductAbstractImporter())
            ->addDataImporter($this->createProductAbstractStoreImporter())
            ->addDataImporter($this->createProductConcreteImporter())
            ->addDataImporter($this->createProductImageImporter())
            ->addDataImporter($this->createProductStockImporter())
            ->addDataImporter($this->createProductOptionImporter())
            ->addDataImporter($this->createProductOptionPriceImporter())
            ->addDataImporter($this->createProductGroupImporter())
            ->addDataImporter($this->createProductPriceImporter())
            ->addDataImporter($this->createProductRelationImporter())
            ->addDataImporter($this->createProductReviewImporter())
            ->addDataImporter($this->createProductLabelImporter())
            ->addDataImporter($this->createProductSetImporter())
            ->addDataImporter($this->createProductSearchAttributeMapImporter())
            ->addDataImporter($this->createProductSearchAttributeImporter())
            ->addDataImporter($this->createCmsTemplateImporter())
            ->addDataImporter($this->createCmsPageImporter())
            ->addDataImporter($this->createCmsBlockImporter())
            ->addDataImporter($this->createCmsBlockStoreImporter())
            ->addDataImporter($this->createCmsBlockCategoryPositionImporter())
            ->addDataImporter($this->createCmsBlockCategoryImporter())
            ->addDataImporter($this->createNavigationImporter())
            ->addDataImporter($this->createNavigationNodeImporter())
            ->addDataImporter($this->createDiscountAmountImporter());

        $dataImporterCollection->addDataImporterPlugins($this->getDataImporterPlugins());

        return $dataImporterCollection;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductAbstractBulkPdoWriter(): DataSetWriterInterface
    {
        return new ProductAbstractBulkPdoDataSetWriter(
            $this->createProductAbstractSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter()
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
        return new ProductPriceBulkPdoDataSetWriter(
            $this->createProductPriceSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter()
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
        return new ProductAbstractStoreBulkPdoDataSetWriter(
            $this->createProductAbstractStoreSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter()
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
     * @return \Pyz\Zed\DataImport\Business\Model\ProductConcrete\Writer\Sql\ProductConcreteSqlInterface
     */
    public function createProductConcreteSql(): ProductConcreteSqlInterface
    {
        return new ProductConcreteSql();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductStock\Writer\Sql\ProductStockSqlInterface
     */
    public function createProductStockSql(): ProductStockSqlInterface
    {
        return new ProductStockSql();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductImage\Writer\Sql\ProductImageSqlInterface
     */
    public function createProductImageSql(): ProductImageSqlInterface
    {
        return new ProductImageSql();
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
        return new ProductConcreteBulkPdoDataSetWriter(
            $this->createProductConcreteSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter()
        );
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface
     */
    public function createPropelExecutor(): PropelExecutorInterface
    {
        return new PropelExecutor();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createProductConcreteImporter()
    {
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig($this->getConfig()->getProductConcreteDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductConcreteHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createAttributesExtractorStep())
            ->addStep($this->createProductLocalizedAttributesExtractorStep([
                ProductConcreteHydratorStep::KEY_NAME,
                ProductConcreteHydratorStep::KEY_DESCRIPTION,
                ProductConcreteHydratorStep::KEY_IS_SEARCHABLE,
            ]))
            ->addStep(new ProductConcreteHydratorStep(
                $this->createProductRepository()
            ));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createProductConcreteDataImportWriters());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductImagePropelWriter(): DataSetWriterInterface
    {
        return new ProductImagePropelDataSetWriter();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductImageBulkPdoWriter(): DataSetWriterInterface
    {
        return new ProductImageBulkPdoDataSetWriter(
            $this->createProductImageSql(),
            $this->createPropelExecutor(),
            $this->createDataFormatter()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductStockPropelWriter(): DataSetWriterInterface
    {
        return new ProductStockPropelDataSetWriter(
            $this->getAvailabilityFacade(),
            $this->getProductBundleFacade(),
            $this->createProductRepository()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    public function createProductStockBulkPdoWriter(): DataSetWriterInterface
    {
        return new ProductStockBulkPdoDataSetWriter(
            $this->getStockFacade(),
            $this->getProductBundleFacade(),
            $this->createProductStockSql(),
            $this->createPropelExecutor(),
            $this->getStoreFacade(),
            $this->createDataFormatter()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createCurrencyImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCurrencyDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new CurrencyWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createOrderSourceImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getOrderSourceDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new OrderSourceWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createStoreImporter()
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface $dataImporter */
        $dataImporter = $this->createDataImporter(
            $this->getConfig()->getStoreDataImporterConfiguration()->getImportType(),
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
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createGlossaryImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getGlossaryDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(GlossaryWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createLocaleNameToIdStep(GlossaryWriterStep::KEY_LOCALE))
            ->addStep(new GlossaryWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface
     */
    protected function createCategoryTemplateImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCategoryTemplateDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new CategoryTemplateWriterStep());

        $dataImporter
            ->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createCustomerImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCustomerDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new CustomerWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createCmsTemplateImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCmsTemplateDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new CmsTemplateWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createCmsPageImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCmsPageDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CmsPageWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createPlaceholderExtractorStep([
                CmsPageWriterStep::KEY_PLACEHOLDER_TITLE,
                CmsPageWriterStep::KEY_PLACEHOLDER_CONTENT,
            ]))
            ->addStep($this->createLocalizedAttributesExtractorStep([
                CmsPageWriterStep::KEY_URL,
                CmsPageWriterStep::KEY_NAME,
                CmsPageWriterStep::KEY_META_TITLE,
                CmsPageWriterStep::KEY_META_DESCRIPTION,
                CmsPageWriterStep::KEY_META_KEYWORDS,
            ]))
            ->addStep(new CmsPageWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createCmsBlockImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCmsBlockDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CmsBlockWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createLocalizedAttributesExtractorStep([
                CmsBlockWriterStep::KEY_PLACEHOLDER_TITLE,
                CmsBlockWriterStep::KEY_PLACEHOLDER_DESCRIPTION,
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
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createCmsBlockStoreImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCmsBlockStoreDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(CmsBlockStoreWriterStep::BULK_SIZE);
        $dataSetStepBroker->addStep(new CmsBlockStoreWriterStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createCmsBlockCategoryPositionImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCmsBlockCategoryPositionDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new CmsBlockCategoryPositionWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createCmsBlockCategoryImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getCmsBlockCategoryDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new CmsBlockCategoryWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @param array $defaultPlaceholder
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface
     */
    protected function createPlaceholderExtractorStep(array $defaultPlaceholder = [])
    {
        return new PlaceholderExtractorStep($defaultPlaceholder);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createDiscountImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getDiscountDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(DiscountWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new DiscountWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createDiscountStoreImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getDiscountStoreDataImporterConfiguration());
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(DiscountStoreWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new DiscountStoreWriterStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createDiscountAmountImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getDiscountAmountDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(DiscountAmountWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new DiscountAmountWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createDiscountVoucherImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getDiscountVoucherDataImporterConfiguration());

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
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createProductPriceImporter()
    {
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig($this->getConfig()->getProductPriceDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductPriceHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new ProductPriceHydratorStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createProductPriceDataImportWriters());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductOptionImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductOptionDataImporterConfiguration());

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
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductOptionPriceImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductOptionPriceDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new ProductOptionPriceWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createProductStockImporter()
    {
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig($this->getConfig()->getProductStockDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductStockHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new ProductStockHydratorStep());
        $dataImporter->addDataSetStepBroker($dataSetStepBroker)
            ->addAfterImportHook($this->createProductStockAfterImportPublishHook());
        $dataImporter->setDataSetWriter($this->createProductStockDataImportWriters());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductPriceDataImportWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createProductPriceWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createProductPriceWriterPlugins()
    {
        return [
            new ProductPricePropelWriterPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductStockDataImportWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createProductStockWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createProductStockWriterPlugins()
    {
        return [
            new ProductStockPropelWriterPlugin(),
        ];
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductStock\Hook\ProductStockAfterImportPublishHook
     */
    protected function createProductStockAfterImportPublishHook()
    {
        return new ProductStockAfterImportPublishHook();
    }

    /**
     * @return \Spryker\Zed\Stock\Business\StockFacadeInterface
     */
    protected function getStockFacade(): StockFacadeInterface
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_STOCK);
    }

    /**
     * @return \Spryker\Zed\Availability\Business\AvailabilityFacadeInterface
     */
    protected function getAvailabilityFacade()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_AVAILABILITY);
    }

    /**
     * @return \Spryker\Zed\ProductBundle\Business\ProductBundleFacadeInterface
     */
    protected function getProductBundleFacade()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_PRODUCT_BUNDLE);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createProductImageImporter()
    {
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig($this->getConfig()->getProductImageDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductImageBulkPdoDataSetWriter::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createProductAbstractSkuToIdProductAbstractStep(ProductImageHydratorStep::KEY_ABSTRACT_SKU, ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT_ABSTRACT))
            ->addStep($this->createProductSkuToIdProductStep(ProductImageHydratorStep::KEY_CONCRETE_SKU, ProductImageHydratorStep::KEY_IMAGE_SET_FK_PRODUCT))
            ->addStep($this->createLocaleNameToIdStep(ProductImageHydratorStep::KEY_LOCALE, ProductImageHydratorStep::KEY_IMAGE_SET_FK_LOCALE))
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
        return new DataSetWriterCollection($this->createProductImageWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createProductImageWriterPlugins()
    {
        return [
            new ProductImagePropelWriterPlugin(),
        ];
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\Locale\Repository\LocaleRepositoryInterface
     */
    protected function createLocaleRepository()
    {
        return new LocaleRepository();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createStockImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getStockDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new StockWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createShipmentImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getShipmentDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ShipmentWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createTaxSetNameToIdTaxSetStep())
            ->addStep(new ShipmentWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createShipmentPriceImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getShipmentPriceDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ShipmentWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new ShipmentPriceWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createTaxImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getTaxDataImporterConfiguration());

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
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createNavigationImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getNavigationDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(NavigationWriterStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new NavigationWriterStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createNavigationNodeImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getNavigationNodeDataImporterConfiguration());

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
    protected function createNavigationKeyToIdNavigationStep($source = NavigationKeyToIdNavigationStep::KEY_SOURCE, $target = NavigationKeyToIdNavigationStep::KEY_TARGET)
    {
        return new NavigationKeyToIdNavigationStep($source, $target);
    }

    /**
     * @param string $keyValidFrom
     * @param string $keyValidTo
     *
     * @return \Pyz\Zed\DataImport\Business\Model\NavigationNode\NavigationNodeValidityDatesStep
     */
    protected function createNavigationNodeValidityDatesStep($keyValidFrom, $keyValidTo)
    {
        return new NavigationNodeValidityDatesStep($keyValidFrom, $keyValidTo);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createProductAbstractImporter()
    {
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig($this->getConfig()->getProductAbstractDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductAbstractHydratorStep::BULK_SIZE);
        $dataSetStepBroker
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createAddCategoryKeysStep())
            ->addStep($this->createTaxSetNameToIdTaxSetStep(ProductAbstractHydratorStep::KEY_TAX_SET_NAME))
            ->addStep($this->createAttributesExtractorStep())
            ->addStep($this->createProductLocalizedAttributesExtractorStep([
                ProductAbstractHydratorStep::KEY_NAME,
                ProductAbstractHydratorStep::KEY_URL,
                ProductAbstractHydratorStep::KEY_DESCRIPTION,
                ProductAbstractHydratorStep::KEY_META_TITLE,
                ProductAbstractHydratorStep::KEY_META_DESCRIPTION,
                ProductAbstractHydratorStep::KEY_META_KEYWORDS,
            ]))
            ->addStep(new ProductAbstractHydratorStep());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->setDataSetWriter($this->createProductAbstractDataImportWriters());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductAbstractDataImportWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createProductAbstractWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createProductAbstractWriterPlugins()
    {
        return [
            new ProductAbstractPropelWriterPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function createProductAbstractStoreImporter()
    {
        $dataImporter = $this->getCsvDataImporterWriterAwareFromConfig($this->getConfig()->getProductAbstractStoreDataImporterConfiguration());

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
        return new DataSetWriterCollection($this->createProductAbstractStoreWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createProductAbstractStoreWriterPlugins()
    {
        return [
            new ProductAbstractStorePropelWriterPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetWriterInterface
     */
    protected function createProductConcreteDataImportWriters(): DataSetWriterInterface
    {
        return new DataSetWriterCollection($this->createProductConcreteWriterPlugins());
    }

    /**
     * @return \Spryker\Zed\DataImportExtension\Dependency\Plugin\DataSetWriterPluginInterface[]
     */
    protected function createProductConcreteWriterPlugins()
    {
        return [
            new ProductConcretePropelWriterPlugin(),
        ];
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepository
     */
    protected function createProductRepository()
    {
        return new ProductRepository();
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductAttributeKeyImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductAttributeKeyDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep(new ProductAttributeKeyWriter());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductManagementAttributeImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductManagementAttributeDataImporterConfiguration());

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
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductGroupImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductGroupDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker(ProductGroupWriter::BULK_SIZE);
        $dataSetStepBroker
            ->addStep(new ProductGroupWriter(
                $this->createProductRepository()
            ));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductRelationImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductRelationDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddProductAbstractSkusStep())
            ->addStep(new ProductRelationWriter());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);
        $dataImporter->addAfterImportHook($this->createProductRelationAfterImportHook());

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductReviewImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductReviewDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker->addStep(new ProductReviewWriterStep(
            $this->createProductRepository(),
            $this->createLocaleRepository()
        ));

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductLabelImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductLabelDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddProductAbstractSkusStep())
            ->addStep($this->createAddLocalesStep())
            ->addStep($this->createLocalizedAttributesExtractorStep(['name']))
            ->addStep(new ProductLabelWriterStep());

        $dataImporter
            ->addDataSetStepBroker($dataSetStepBroker)
            ->addAfterImportHook($this->createProductLabelAfterImportPublishHook());

        return $dataImporter;
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductLabel\Hook\ProductLabelAfterImportPublishHook
     */
    protected function createProductLabelAfterImportPublishHook()
    {
        return new ProductLabelAfterImportPublishHook();
    }

    /**
     * @return \Pyz\Zed\DataImport\Business\Model\ProductRelation\Hook\ProductRelationAfterImportHook
     */
    protected function createProductRelationAfterImportHook()
    {
        return new ProductRelationAfterImportHook(
            $this->getProductRelationFacade()
        );
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductSetImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductSetDataImporterConfiguration());

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
     * @return \Spryker\Zed\ProductRelation\Business\ProductRelationFacadeInterface
     */
    protected function getProductRelationFacade()
    {
        return $this->getProvidedDependency(DataImportDependencyProvider::FACADE_PRODUCT_RELATION);
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductSearchAttributeMapImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductSearchAttributeMapDataImporterConfiguration());

        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker
            ->addStep($this->createAddProductAttributeKeysStep())
            ->addStep(new ProductSearchAttributeMapWriter());

        $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    protected function createProductSearchAttributeImporter()
    {
        $dataImporter = $this->getCsvDataImporterFromConfig($this->getConfig()->getProductSearchAttributeDataImporterConfiguration());

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
     * @param array $defaultAttributes
     *
     * @return \Pyz\Zed\DataImport\Business\Model\Product\ProductLocalizedAttributesExtractorStep
     */
    protected function createProductLocalizedAttributesExtractorStep(array $defaultAttributes = [])
    {
        return new ProductLocalizedAttributesExtractorStep($defaultAttributes);
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
    protected function createLocaleNameToIdStep($source = LocaleNameToIdLocaleStep::KEY_SOURCE, $target = LocaleNameToIdLocaleStep::KEY_TARGET)
    {
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
    protected function createTaxSetNameToIdTaxSetStep($source = TaxSetNameToIdTaxSetStep::KEY_SOURCE, $target = TaxSetNameToIdTaxSetStep::KEY_TARGET)
    {
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
        return new DataImportDataFormatter();
    }
}
