<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport;

use Pyz\Zed\MerchantProductOfferDataImport\Communication\Plugin\CombinedMerchantProductOfferDataImportPlugin;
use Pyz\Zed\MerchantProductOfferDataImport\Communication\Plugin\CombinedMerchantProductOfferStoreDataImportPlugin;
use Pyz\Zed\PriceProductOfferDataImport\Communication\Plugin\CombinedPriceProductOfferDataImportPlugin;
use Pyz\Zed\ProductOfferStockDataImport\Communication\Plugin\CombinedProductOfferStockDataImportPlugin;
use Pyz\Zed\ProductOfferValidityDataImport\Communication\Plugin\CombinedProductOfferValidityDataImportPlugin;
use Spryker\Zed\AclDataImport\Communication\Plugin\AclGroupDataImportPlugin;
use Spryker\Zed\AclDataImport\Communication\Plugin\AclGroupRoleDataImportPlugin;
use Spryker\Zed\AclDataImport\Communication\Plugin\AclRoleDataImportPlugin;
use Spryker\Zed\AclEntityDataImport\Communication\Plugin\AclEntityRuleDataImportPlugin;
use Spryker\Zed\AclEntityDataImport\Communication\Plugin\AclEntitySegmentConnectorDataImportPlugin;
use Spryker\Zed\AclEntityDataImport\Communication\Plugin\AclEntitySegmentDataImportPlugin;
use Spryker\Zed\BusinessOnBehalfDataImport\Communication\Plugin\DataImport\BusinessOnBehalfCompanyUserDataImportPlugin;
use Spryker\Zed\CategoryDataImport\Communication\Plugin\CategoryDataImportPlugin;
use Spryker\Zed\CategoryDataImport\Communication\Plugin\DataImport\CategoryStoreDataImportPlugin;
use Spryker\Zed\CmsPageDataImport\Communication\Plugin\CmsPageDataImportPlugin;
use Spryker\Zed\CmsPageDataImport\Communication\Plugin\CmsPageStoreDataImportPlugin;
use Spryker\Zed\CmsSlotBlockDataImport\Communication\Plugin\CmsSlotBlockDataImportPlugin;
use Spryker\Zed\CmsSlotDataImport\Communication\Plugin\CmsSlotDataImportPlugin;
use Spryker\Zed\CmsSlotDataImport\Communication\Plugin\CmsSlotTemplateDataImportPlugin;
use Spryker\Zed\CommentDataImport\Communication\Plugin\CommentDataImportPlugin;
use Spryker\Zed\CompanyBusinessUnitDataImport\Communication\Plugin\CompanyBusinessUnitAddressDataImportPlugin;
use Spryker\Zed\CompanyBusinessUnitDataImport\Communication\Plugin\CompanyBusinessUnitDataImportPlugin;
use Spryker\Zed\CompanyBusinessUnitDataImport\Communication\Plugin\CompanyBusinessUnitUserDataImportPlugin;
use Spryker\Zed\CompanyDataImport\Communication\Plugin\CompanyDataImportPlugin;
use Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyRoleDataImportPlugin;
use Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyRolePermissionDataImportPlugin;
use Spryker\Zed\CompanyRoleDataImport\Communication\Plugin\DataImport\CompanyUserRoleDataImportPlugin;
use Spryker\Zed\CompanyUnitAddressDataImport\Communication\Plugin\CompanyUnitAddressDataImportPlugin;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Communication\Plugin\CompanyUnitAddressLabelDataImportPlugin;
use Spryker\Zed\CompanyUnitAddressLabelDataImport\Communication\Plugin\CompanyUnitAddressLabelRelationDataImportPlugin;
use Spryker\Zed\CompanyUserDataImport\Communication\Plugin\DataImport\CompanyUserDataImportPlugin;
use Spryker\Zed\ConfigurableBundleDataImport\Communication\Plugin\ConfigurableBundleTemplateDataImportPlugin;
use Spryker\Zed\ConfigurableBundleDataImport\Communication\Plugin\ConfigurableBundleTemplateImageDataImportPlugin;
use Spryker\Zed\ConfigurableBundleDataImport\Communication\Plugin\ConfigurableBundleTemplateSlotDataImportPlugin;
use Spryker\Zed\ContentBannerDataImport\Communication\Plugin\ContentBannerDataImportPlugin;
use Spryker\Zed\ContentNavigationDataImport\Communication\Plugin\DataImport\ContentNavigationDataImportPlugin;
use Spryker\Zed\ContentProductDataImport\Communication\Plugin\ContentProductAbstractListDataImportPlugin;
use Spryker\Zed\ContentProductSetDataImport\Communication\Plugin\ContentProductSetDataImportPlugin;
use Spryker\Zed\CountryDataImport\Communication\Plugin\DataImport\CountryStoreDataImportPlugin;
use Spryker\Zed\CurrencyDataImport\Communication\Plugin\DataImport\CurrencyStoreDataImportPlugin;
use Spryker\Zed\DataImport\Communication\Plugin\DataImportEventBehaviorPlugin;
use Spryker\Zed\DataImport\Communication\Plugin\DataImportPublisherPlugin;
use Spryker\Zed\DataImport\DataImportDependencyProvider as SprykerDataImportDependencyProvider;
use Spryker\Zed\FileManagerDataImport\Communication\Plugin\FileManagerDataImportPlugin;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\LocaleDataImport\Communication\Plugin\DataImport\DefaultLocaleStoreDataImportPlugin;
use Spryker\Zed\LocaleDataImport\Communication\Plugin\DataImport\LocaleStoreDataImportPlugin;
use Spryker\Zed\MerchantCategoryDataImport\Communication\Plugin\DataImport\MerchantCategoryDataImportPlugin;
use Spryker\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport\MerchantCommissionAmountDataImportPlugin;
use Spryker\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport\MerchantCommissionDataImportPlugin;
use Spryker\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport\MerchantCommissionGroupDataImportPlugin;
use Spryker\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport\MerchantCommissionMerchantDataImportPlugin;
use Spryker\Zed\MerchantCommissionDataImport\Communication\Plugin\DataImport\MerchantCommissionStoreDataImportPlugin;
use Spryker\Zed\MerchantDataImport\Communication\Plugin\MerchantDataImportPlugin;
use Spryker\Zed\MerchantDataImport\Communication\Plugin\MerchantStoreDataImportPlugin;
use Spryker\Zed\MerchantOmsDataImport\Communication\Plugin\DataImport\MerchantOmsProcessDataImportPlugin;
use Spryker\Zed\MerchantOpeningHoursDataImport\Communication\Plugin\MerchantOpeningHoursDateScheduleDataImportPlugin;
use Spryker\Zed\MerchantOpeningHoursDataImport\Communication\Plugin\MerchantOpeningHoursWeekdayScheduleDataImportPlugin;
use Spryker\Zed\MerchantProductApprovalDataImport\Communication\Plugin\DataImport\MerchantProductApprovalStatusDefaultDataImportPlugin;
use Spryker\Zed\MerchantProductDataImport\Communication\Plugin\MerchantProductDataImportPlugin;
use Spryker\Zed\MerchantProductOfferDataImport\Communication\Plugin\DataImport\MerchantProductOfferDataImportPlugin;
use Spryker\Zed\MerchantProductOfferDataImport\Communication\Plugin\DataImport\MerchantProductOfferStoreDataImportPlugin;
use Spryker\Zed\MerchantProductOptionDataImport\Communication\Plugin\DataImport\MerchantProductOptionGroupDataImportPlugin;
use Spryker\Zed\MerchantProfileDataImport\Communication\Plugin\MerchantProfileAddressDataImportPlugin;
use Spryker\Zed\MerchantProfileDataImport\Communication\Plugin\MerchantProfileDataImportPlugin;
use Spryker\Zed\MerchantRelationshipDataImport\Communication\Plugin\MerchantRelationshipDataImportPlugin;
use Spryker\Zed\MerchantRelationshipProductListDataImport\Communication\Plugin\MerchantRelationshipProductListDataImportPlugin;
use Spryker\Zed\MerchantRelationshipSalesOrderThresholdDataImport\Communication\Plugin\DataImport\MerchantRelationshipSalesOrderThresholdDataImportPlugin;
use Spryker\Zed\MerchantStockDataImport\Communication\Plugin\MerchantStockDataImportPlugin;
use Spryker\Zed\MultiCartDataImport\Communication\Plugin\MultiCartDataImportPlugin;
use Spryker\Zed\PaymentDataImport\Communication\Plugin\PaymentMethodDataImportPlugin;
use Spryker\Zed\PaymentDataImport\Communication\Plugin\PaymentMethodStoreDataImportPlugin;
use Spryker\Zed\PriceProductDataImport\Communication\Plugin\PriceProductDataImportPlugin;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Communication\Plugin\PriceProductMerchantRelationshipDataImportPlugin;
use Spryker\Zed\PriceProductOfferDataImport\Communication\Plugin\PriceProductOfferDataImportPlugin;
use Spryker\Zed\PriceProductScheduleDataImport\Communication\Plugin\PriceProductScheduleDataImportPlugin;
use Spryker\Zed\ProductAlternativeDataImport\Communication\Plugin\ProductAlternativeDataImportPlugin;
use Spryker\Zed\ProductApprovalDataImport\Communication\Plugin\DataImport\ProductAbstractApprovalStatusDataImportPlugin;
use Spryker\Zed\ProductConfigurationDataImport\Communication\Plugin\ProductConfigurationDataImportPlugin;
use Spryker\Zed\ProductDiscontinuedDataImport\Communication\Plugin\ProductDiscontinuedDataImportPlugin;
use Spryker\Zed\ProductLabelDataImport\Communication\Plugin\ProductLabelDataImportPlugin;
use Spryker\Zed\ProductLabelDataImport\Communication\Plugin\ProductLabelStoreDataImportPlugin;
use Spryker\Zed\ProductListDataImport\Communication\Plugin\ProductListCategoryDataImportPlugin;
use Spryker\Zed\ProductListDataImport\Communication\Plugin\ProductListDataImportPlugin;
use Spryker\Zed\ProductListDataImport\Communication\Plugin\ProductListProductConcreteDataImportPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementBaseUnitDataImportPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementSalesUnitDataImportPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementSalesUnitStoreDataImportPlugin;
use Spryker\Zed\ProductMeasurementUnitDataImport\Communication\Plugin\ProductMeasurementUnitDataImportPlugin;
use Spryker\Zed\ProductOfferServicePointDataImport\Communication\Plugin\DataImport\ProductOfferServiceDataImportPlugin;
use Spryker\Zed\ProductOfferShipmentTypeDataImport\Communication\Plugin\DataImport\ProductOfferShipmentTypeDataImportPlugin;
use Spryker\Zed\ProductOfferShoppingListDataImport\Communication\Plugin\DataImport\ProductOfferShoppingListItemDataImportPlugin;
use Spryker\Zed\ProductOfferStockDataImport\Communication\Plugin\ProductOfferStockDataImportPlugin;
use Spryker\Zed\ProductOfferValidityDataImport\Communication\DataImport\ProductOfferValidityDataImportPlugin;
use Spryker\Zed\ProductPackagingUnitDataImport\Communication\Plugin\DataImport\ProductPackagingUnitDataImportPlugin;
use Spryker\Zed\ProductPackagingUnitDataImport\Communication\Plugin\DataImport\ProductPackagingUnitTypeDataImportPlugin;
use Spryker\Zed\ProductQuantityDataImport\Communication\Plugin\ProductQuantityDataImportPlugin;
use Spryker\Zed\ProductRelationDataImport\Communication\Plugin\ProductRelationDataImportPlugin;
use Spryker\Zed\ProductRelationDataImport\Communication\Plugin\ProductRelationStoreDataImportPlugin;
use Spryker\Zed\QuoteRequestDataImport\Communication\Plugin\QuoteRequestDataImportPlugin;
use Spryker\Zed\QuoteRequestDataImport\Communication\Plugin\QuoteRequestVersionDataImportPlugin;
use Spryker\Zed\SalesOrderThresholdDataImport\Communication\Plugin\DataImport\SalesOrderThresholdDataImportPlugin;
use Spryker\Zed\SalesReturnDataImport\Communication\Plugin\ReturnReasonDataImportPlugin;
use Spryker\Zed\ServicePointDataImport\Communication\Plugin\DataImport\ServiceDataImportPlugin;
use Spryker\Zed\ServicePointDataImport\Communication\Plugin\DataImport\ServicePointAddressDataImportPlugin;
use Spryker\Zed\ServicePointDataImport\Communication\Plugin\DataImport\ServicePointDataImportPlugin;
use Spryker\Zed\ServicePointDataImport\Communication\Plugin\DataImport\ServicePointStoreDataImportPlugin;
use Spryker\Zed\ServicePointDataImport\Communication\Plugin\DataImport\ServiceTypeDataImportPlugin;
use Spryker\Zed\SharedCartDataImport\Communication\Plugin\SharedCartDataImportPlugin;
use Spryker\Zed\ShipmentDataImport\Communication\Plugin\ShipmentDataImportPlugin;
use Spryker\Zed\ShipmentDataImport\Communication\Plugin\ShipmentMethodPriceDataImportPlugin;
use Spryker\Zed\ShipmentDataImport\Communication\Plugin\ShipmentMethodStoreDataImportPlugin;
use Spryker\Zed\ShipmentTypeDataImport\Communication\Plugin\DataImport\ShipmentMethodShipmentTypeDataImportPlugin;
use Spryker\Zed\ShipmentTypeDataImport\Communication\Plugin\DataImport\ShipmentTypeDataImportPlugin;
use Spryker\Zed\ShipmentTypeDataImport\Communication\Plugin\DataImport\ShipmentTypeStoreDataImportPlugin;
use Spryker\Zed\ShipmentTypeServicePointDataImport\Communication\Plugin\DataImport\ShipmentTypeServiceTypeDataImportPlugin;
use Spryker\Zed\ShoppingListDataImport\Communication\Plugin\ShoppingListCompanyBusinessUnitDataImportPlugin;
use Spryker\Zed\ShoppingListDataImport\Communication\Plugin\ShoppingListCompanyUserDataImportPlugin;
use Spryker\Zed\ShoppingListDataImport\Communication\Plugin\ShoppingListDataImportPlugin;
use Spryker\Zed\ShoppingListDataImport\Communication\Plugin\ShoppingListItemDataImportPlugin;
use Spryker\Zed\StockAddressDataImport\Communication\Plugin\DataImport\StockAddressDataImportPlugin;
use Spryker\Zed\StockDataImport\Communication\Plugin\StockDataImportPlugin;
use Spryker\Zed\StockDataImport\Communication\Plugin\StockStoreDataImportPlugin;
use Spryker\Zed\StoreContextDataImport\Communication\Plugin\DataImport\StoreContextDataImportPlugin;
use Spryker\Zed\StoreDataImport\Communication\Plugin\DataImport\StoreDataImportPlugin;

class DataImportDependencyProvider extends SprykerDataImportDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CATEGORY = 'category facade';

    /**
     * @var string
     */
    public const FACADE_STORE = 'store facade';

    /**
     * @var string
     */
    public const FACADE_CURRENCY = 'currency facade';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_BUNDLE = 'product bundle facade';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_RELATION = 'product relation facade';

    /**
     * @var string
     */
    public const FACADE_STOCK = 'stock facade';

    /**
     * @var string
     */
    public const FACADE_PRODUCT_SEARCH = 'product search facade';

    /**
     * @var string
     */
    public const FACADE_PRICE_PRODUCT = 'FACADE_PRICE_PRODUCT';

    /**
     * @var string
     */
    public const FACADE_MERCHANT_USER = 'FACADE_MERCHANT_USER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addStockFacade($container);
        $container = $this->addCurrencyFacade($container);
        $container = $this->addStoreFacade($container);
        $container = $this->addCategoryFacade($container);
        $container = $this->addProductBundleFacade($container);
        $container = $this->addProductRelationFacade($container);
        $container = $this->addProductSearchFacade($container);
        $container = $this->addPriceProductFacade($container);
        $container = $this->addMerchantUserFacade($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCurrencyFacade(Container $container): Container
    {
        $container->set(static::FACADE_CURRENCY, function (Container $container) {
            return $container->getLocator()->currency()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStoreFacade(Container $container): Container
    {
        $container->set(static::FACADE_STORE, function (Container $container) {
            return $container->getLocator()->store()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStockFacade(Container $container): Container
    {
        $container->set(static::FACADE_STOCK, function (Container $container) {
            return $container->getLocator()->stock()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCategoryFacade(Container $container): Container
    {
        $container->set(static::FACADE_CATEGORY, function (Container $container) {
            return $container->getLocator()->category()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductBundleFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_BUNDLE, function (Container $container) {
            return $container->getLocator()->productBundle()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductSearchFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_SEARCH, function (Container $container) {
            return $container->getLocator()->productSearch()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addProductRelationFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_RELATION, function (Container $container) {
            return $container->getLocator()->productRelation()->facade();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_USER, function (Container $container) {
            return $container->getLocator()->merchantUser()->facade();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\DataImport\Dependency\Plugin\DataImportPluginInterface>
     */
    protected function getDataImporterPlugins(): array
    {
        return [
            new StoreDataImportPlugin(),
            new CountryStoreDataImportPlugin(),
            new CurrencyStoreDataImportPlugin(),
            new LocaleStoreDataImportPlugin(),
            new DefaultLocaleStoreDataImportPlugin(),
            new StoreContextDataImportPlugin(),
            new CategoryDataImportPlugin(),
            new CmsPageDataImportPlugin(),
            new CmsPageStoreDataImportPlugin(),
            new CmsSlotTemplateDataImportPlugin(),
            new CmsSlotDataImportPlugin(),
            new CmsSlotBlockDataImportPlugin(),
            new CompanyDataImportPlugin(),
            new AclGroupDataImportPlugin(),
            new AclRoleDataImportPlugin(),
            new AclGroupRoleDataImportPlugin(),
            new AclEntityRuleDataImportPlugin(),
            new AclEntitySegmentDataImportPlugin(),
            new AclEntitySegmentConnectorDataImportPlugin(),
            new CompanyBusinessUnitDataImportPlugin(),
            new CompanyUnitAddressDataImportPlugin(),
            new CompanyUnitAddressLabelDataImportPlugin(),
            new CompanyUnitAddressLabelRelationDataImportPlugin(),
            new ProductDiscontinuedDataImportPlugin(), #ProductDiscontinuedFeature
            new ProductMeasurementUnitDataImportPlugin(),
            new ProductMeasurementBaseUnitDataImportPlugin(),
            new ProductMeasurementSalesUnitDataImportPlugin(),
            new ProductMeasurementSalesUnitStoreDataImportPlugin(),
            new ProductQuantityDataImportPlugin(),
            new ProductAlternativeDataImportPlugin(), #ProductAlternativeFeature
            new ProductPackagingUnitTypeDataImportPlugin(),
            new ProductPackagingUnitDataImportPlugin(),
            new BusinessOnBehalfCompanyUserDataImportPlugin(),
            new SalesOrderThresholdDataImportPlugin(),
            new MerchantDataImportPlugin(),
            new MerchantStoreDataImportPlugin(),
            new MerchantProductDataImportPlugin(),
            new MerchantProfileDataImportPlugin(),
            new MerchantProfileAddressDataImportPlugin(),
            new MerchantProductOfferDataImportPlugin(),
            new MerchantProductOfferStoreDataImportPlugin(),
            new MerchantProductOptionGroupDataImportPlugin(),
            new MerchantOpeningHoursWeekdayScheduleDataImportPlugin(),
            new MerchantOpeningHoursDateScheduleDataImportPlugin(),
            new PriceProductDataImportPlugin(),
            new ProductOfferValidityDataImportPlugin(),
            new PriceProductOfferDataImportPlugin(),
            new MerchantRelationshipDataImportPlugin(),
            new MerchantRelationshipSalesOrderThresholdDataImportPlugin(),
            new ProductListDataImportPlugin(),
            new ProductListCategoryDataImportPlugin(),
            new ProductListProductConcreteDataImportPlugin(),
            new MerchantRelationshipProductListDataImportPlugin(),
            new PriceProductMerchantRelationshipDataImportPlugin(),
            new FileManagerDataImportPlugin(),
            new CompanyUserDataImportPlugin(),
            new CompanyRoleDataImportPlugin(),
            new CompanyRolePermissionDataImportPlugin(),
            new CompanyUserRoleDataImportPlugin(),
            new CompanyBusinessUnitUserDataImportPlugin(),
            new CompanyBusinessUnitAddressDataImportPlugin(),
            new MultiCartDataImportPlugin(),
            new SharedCartDataImportPlugin(),
            new ShoppingListDataImportPlugin(),
            new ShoppingListItemDataImportPlugin(),
            new ProductOfferShoppingListItemDataImportPlugin(),
            new ShoppingListCompanyUserDataImportPlugin(),
            new ShoppingListCompanyBusinessUnitDataImportPlugin(),
            new QuoteRequestDataImportPlugin(),
            new QuoteRequestVersionDataImportPlugin(),
            new ConfigurableBundleTemplateDataImportPlugin(),
            new ConfigurableBundleTemplateSlotDataImportPlugin(),
            new ConfigurableBundleTemplateImageDataImportPlugin(),
            new PriceProductScheduleDataImportPlugin(),
            new ContentBannerDataImportPlugin(),
            new ContentProductAbstractListDataImportPlugin(),
            new ContentProductSetDataImportPlugin(),
            new CommentDataImportPlugin(),
            new ShipmentDataImportPlugin(),
            new ShipmentMethodPriceDataImportPlugin(),
            new ShipmentMethodStoreDataImportPlugin(),
            new PaymentMethodDataImportPlugin(),
            new PaymentMethodStoreDataImportPlugin(),
            new StockDataImportPlugin(),
            new StockStoreDataImportPlugin(),
            new ProductOfferStockDataImportPlugin(),
            new ProductRelationDataImportPlugin(),
            new ProductRelationStoreDataImportPlugin(),
            new MerchantStockDataImportPlugin(),
            new MerchantOmsProcessDataImportPlugin(),
            new ProductLabelDataImportPlugin(),
            new ProductLabelStoreDataImportPlugin(),
            new ReturnReasonDataImportPlugin(),
            new ContentNavigationDataImportPlugin(),
            new MerchantCategoryDataImportPlugin(),
            new ProductConfigurationDataImportPlugin(),
            new CategoryStoreDataImportPlugin(),
            new CombinedMerchantProductOfferDataImportPlugin(),
            new CombinedMerchantProductOfferStoreDataImportPlugin(),
            new CombinedPriceProductOfferDataImportPlugin(),
            new CombinedProductOfferValidityDataImportPlugin(),
            new CombinedProductOfferStockDataImportPlugin(),
            new StockAddressDataImportPlugin(),
            new MerchantProductApprovalStatusDefaultDataImportPlugin(),
            new ProductAbstractApprovalStatusDataImportPlugin(),
            new ServicePointDataImportPlugin(),
            new ServicePointStoreDataImportPlugin(),
            new ServicePointAddressDataImportPlugin(),
            new ServiceTypeDataImportPlugin(),
            new ServiceDataImportPlugin(),
            new ShipmentTypeDataImportPlugin(),
            new ShipmentTypeStoreDataImportPlugin(),
            new ShipmentMethodShipmentTypeDataImportPlugin(),
            new ShipmentTypeServiceTypeDataImportPlugin(),
            new ProductOfferShipmentTypeDataImportPlugin(),
            new ProductOfferServiceDataImportPlugin(),
            new MerchantCommissionGroupDataImportPlugin(),
            new MerchantCommissionDataImportPlugin(),
            new MerchantCommissionAmountDataImportPlugin(),
            new MerchantCommissionStoreDataImportPlugin(),
            new MerchantCommissionMerchantDataImportPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\DataImport\Business\Model\DataImporterPluginCollectionInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface|\Spryker\Zed\DataImport\Dependency\Plugin\DataImportBeforeImportHookInterface|\Spryker\Zed\DataImport\Dependency\Plugin\DataImportAfterImportHookInterface>
     */
    protected function getDataImportBeforeImportHookPlugins(): array
    {
        return [
            new DataImportEventBehaviorPlugin(),
        ];
    }

    /**
     * @return array<\Spryker\Zed\DataImport\Business\Model\DataImporterPluginCollectionInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterCollectionInterface|\Spryker\Zed\DataImport\Dependency\Plugin\DataImportBeforeImportHookInterface|\Spryker\Zed\DataImport\Dependency\Plugin\DataImportAfterImportHookInterface>
     */
    protected function getDataImportAfterImportHookPlugins(): array
    {
        return [
            new DataImportEventBehaviorPlugin(),
            new DataImportPublisherPlugin(),
        ];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPriceProductFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRICE_PRODUCT, function (Container $container) {
            return $container->getLocator()->priceProduct()->facade();
        });

        return $container;
    }
}
