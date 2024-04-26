<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\AclMerchantPortal;

use Spryker\Zed\Acl\Communication\Plugin\AclMerchantPortal\AclEntityConfigurationExpanderPlugin;
use Spryker\Zed\AclEntity\Communication\Plugin\AclMerchantPortal\AclEntityAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\AclMerchantPortal\AclMerchantPortalDependencyProvider as SprykerAclMerchantPortalDependencyProvider;
use Spryker\Zed\Availability\Communication\Plugin\AclMerchantPortal\AvailabilityAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Category\Communication\Plugin\AclMerchantPortal\CategoryAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\CategoryImage\Communication\Plugin\AclMerchantPortal\CategoryImageAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\CmsBlock\Communication\Plugin\AclMerchantPortal\CmsBlockAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Comment\Communication\Plugin\AclMerchantPortal\CommentAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\CommentMerchantPortalGui\Communication\Plugin\AclMerchantPortal\CommentMerchantPortalGuiMerchantAclRuleExpanderPlugin;
use Spryker\Zed\CompanyBusinessUnit\Communication\Plugin\AclMerchantPortal\CompanyBusinessUnitAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\CompanyBusinessUnit\Communication\Plugin\AclMerchantPortal\CompanyBusinessUnitMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\CompanyUnitAddress\Communication\Plugin\AclMerchantPortal\CompanyUnitAddressAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\CompanyUnitAddress\Communication\Plugin\AclMerchantPortal\CompanyUnitAddressMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\Country\Communication\Plugin\AclMerchantPortal\CountryAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Country\Communication\Plugin\AclMerchantPortal\CountryMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\Country\Communication\Plugin\AclMerchantPortal\CountryStoreAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Currency\Communication\Plugin\AclMerchantPortal\CurrencyAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Currency\Communication\Plugin\AclMerchantPortal\CurrencyMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\Currency\Communication\Plugin\AclMerchantPortal\CurrencyStoreAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Customer\Communication\Plugin\AclMerchantPortal\CustomerMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\DashboardMerchantPortalGui\Communication\Plugin\AclMerchantPortal\DashboardMerchantPortalGuiMerchantAclRuleExpanderPlugin;
use Spryker\Zed\Discount\Communication\Plugin\AclMerchantPortal\DiscountAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Discount\Communication\Plugin\AclMerchantPortal\DiscountMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\DiscountPromotion\Communication\Plugin\AclMerchantPortal\DiscountPromotionAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\DiscountPromotion\Communication\Plugin\AclMerchantPortal\DiscountPromotionMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\DummyMerchantPortalGui\Communication\Plugin\AclMerchantPortal\DummyMerchantPortalGuiMerchantAclRuleExpanderPlugin;
use Spryker\Zed\GiftCard\Communication\Plugin\AclMerchantPortal\GiftCardAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Glossary\Communication\Plugin\AclMerchantPortal\GlossaryAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Locale\Communication\Plugin\AclMerchantPortal\LocaleAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Locale\Communication\Plugin\AclMerchantPortal\LocaleMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\Locale\Communication\Plugin\AclMerchantPortal\LocaleStoreAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Merchant\Communication\Plugin\AclMerchantPortal\MerchantAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Merchant\Communication\Plugin\AclMerchantPortal\MerchantMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\MerchantCategory\Communication\Plugin\AclMerchantPortal\MerchantCategoryAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\MerchantProduct\Communication\Plugin\AclMerchantPortal\MerchantProductAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\MerchantProduct\Communication\Plugin\AclMerchantPortal\MerchantProductMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\MerchantProductOffer\Communication\Plugin\AclMerchantPortal\MerchantProductOfferAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\MerchantProfile\Communication\Plugin\AclMerchantPortal\MerchantProfileAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\MerchantProfileMerchantPortalGui\Communication\Plugin\AclMerchantPortal\MerchantProfileMerchantPortalGuiMerchantAclRuleExpanderPlugin;
use Spryker\Zed\MerchantRelationRequest\Communication\Plugin\AclMerchantPortal\MerchantRelationRequestAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\MerchantRelationRequest\Communication\Plugin\AclMerchantPortal\MerchantRelationRequestMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\MerchantRelationRequestMerchantPortalGui\Communication\Plugin\AclMerchantPortal\MerchantRelationRequestMerchantPortalGuiMerchantAclRuleExpanderPlugin;
use Spryker\Zed\MerchantRelationship\Communication\Plugin\AclMerchantPortal\MerchantRelationshipMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\MerchantRelationship\Communication\Plugin\AclMerchantPortal\MerchantRelationshipModelAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\MerchantRelationshipMerchantPortalGui\Communication\Plugin\AclMerchantPortal\MerchantRelationshipMerchantPortalGuiMerchantAclRuleExpanderPlugin;
use Spryker\Zed\MerchantSalesOrder\Communication\Plugin\AclMerchantPortal\MerchantSalesOrderAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\MerchantSalesOrder\Communication\Plugin\AclMerchantPortal\MerchantSalesOrderMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\MerchantStock\Communication\Plugin\AclMerchantPortal\MerchantStockAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\MerchantUser\Communication\Plugin\AclMerchantPortal\MerchantUserAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\MerchantUser\Communication\Plugin\AclMerchantPortal\MerchantUserMerchantUserAclEntityRuleExpanderPlugin;
use Spryker\Zed\Oms\Communication\Plugin\AclMerchantPortal\OmsAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Oms\Communication\Plugin\AclMerchantPortal\OmsMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\OmsProductOfferReservation\Communication\Plugin\AclMerchantPortal\OmsProductOfferReservationAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\OmsProductOfferReservation\Communication\Plugin\AclMerchantPortal\OmsProductOfferReservationMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\Payment\Communication\Plugin\AclMerchantPortal\PaymentAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\PriceProduct\Communication\Plugin\AclMerchantPortal\PriceProductAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\PriceProduct\Communication\Plugin\AclMerchantPortal\PriceProductMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\PriceProductMerchantRelationship\Communication\Plugin\AclMerchantPortal\PriceProductMerchantRelationshipAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Plugin\AclMerchantPortal\PriceProductMerchantRelationshipMerchantPortalGuiMerchantAclRuleExpanderPlugin;
use Spryker\Zed\PriceProductOffer\Communication\Plugin\AclMerchantPortal\PriceProductOfferAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Product\Communication\Plugin\AclMerchantPortal\ProductAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Product\Communication\Plugin\AclMerchantPortal\ProductMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\ProductAttribute\Communication\Plugin\AclMerchantPortal\ProductAttributeAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\ProductCategory\Communication\Plugin\AclMerchantPortal\ProductCategoryAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\ProductImage\Communication\Plugin\AclMerchantPortal\ProductImageAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\ProductImage\Communication\Plugin\AclMerchantPortal\ProductImageMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Plugin\AclMerchantPortal\ProductMerchantPortalGuiMerchantAclRuleExpanderPlugin;
use Spryker\Zed\ProductOffer\Communication\Plugin\AclMerchantPortal\ProductOfferAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\ProductOffer\Communication\Plugin\AclMerchantPortal\ProductOfferMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Plugin\AclMerchantPortal\ProductOfferMerchantPortalGuiMerchantAclRuleExpanderPlugin;
use Spryker\Zed\ProductOfferServicePoint\Communication\Plugin\AclMerchantPortal\ProductOfferServicePointAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\ProductOfferServicePointMerchantPortalGui\Communication\Plugin\AclMerchantPortal\ProductOfferServicePointMerchantPortalGuiMerchantAclRuleExpanderPlugin;
use Spryker\Zed\ProductOfferShipmentType\Communication\Plugin\AclMerchantPortal\ProductOfferShipmentTypeAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\ProductOfferStock\Communication\Plugin\AclMerchantPortal\ProductOfferStockAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\ProductOfferValidity\Communication\Plugin\AclMerchantPortal\ProductOfferValidityAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\ProductOption\Communication\Plugin\AclMerchantPortal\ProductOptionAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\ProductSearch\Communication\Plugin\AclMerchantPortal\ProductSearchAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\ProductValidity\Communication\Plugin\AclMerchantPortal\ProductValidityAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Refund\Communication\Plugin\AclMerchantPortal\RefundAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Refund\Communication\Plugin\AclMerchantPortal\RefundMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\Sales\Communication\Plugin\AclMerchantPortal\SalesAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Sales\Communication\Plugin\AclMerchantPortal\SalesMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\SalesInvoice\Communication\Plugin\AclMerchantPortal\SalesInvoiceAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\SalesMerchantPortalGui\Communication\Plugin\AclMerchantPortal\SalesMerchantPortalGuiMerchantAclRuleExpanderPlugin;
use Spryker\Zed\SalesOrderThreshold\Communication\Plugin\AclMerchantPortal\SalesOrderThresholdAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\SecurityMerchantPortalGui\Communication\Plugin\AclMerchantPortal\SecurityMerchantPortalGuiMerchantUserAclRuleExpanderPlugin;
use Spryker\Zed\ServicePoint\Communication\Plugin\AclMerchantPortal\ServiceAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Shipment\Communication\Plugin\AclMerchantPortal\ShipmentAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\ShipmentType\Communication\Plugin\AclMerchantPortal\ShipmentTypeAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\StateMachine\Communication\Plugin\AclMerchantPortal\StateMachineAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Stock\Communication\Plugin\AclMerchantPortal\StockAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Store\Communication\Plugin\AclMerchantPortal\StoreAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Store\Communication\Plugin\AclMerchantPortal\StoreMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\Tax\Communication\Plugin\AclMerchantPortal\TaxAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Url\Communication\Plugin\AclMerchantPortal\UrlAclEntityConfigurationExpanderPlugin;
use Spryker\Zed\Url\Communication\Plugin\AclMerchantPortal\UrlMerchantAclEntityRuleExpanderPlugin;
use Spryker\Zed\UserMerchantPortalGui\Communication\Plugin\AclMerchantPortal\UserMerchantPortalGuiMerchantUserAclRuleExpanderPlugin;
use Spryker\Zed\UserPasswordReset\Communication\Plugin\AclMerchantPortal\UserPasswordResetAclEntityConfigurationExpanderPlugin;

class AclMerchantPortalDependencyProvider extends SprykerAclMerchantPortalDependencyProvider
{
    /**
     * @return list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclRuleExpanderPluginInterface>
     */
    protected function getMerchantAclRuleExpanderPlugins(): array
    {
        return [
            new DashboardMerchantPortalGuiMerchantAclRuleExpanderPlugin(),
            new MerchantProfileMerchantPortalGuiMerchantAclRuleExpanderPlugin(),
            new ProductOfferMerchantPortalGuiMerchantAclRuleExpanderPlugin(),
            new ProductMerchantPortalGuiMerchantAclRuleExpanderPlugin(),
            new SalesMerchantPortalGuiMerchantAclRuleExpanderPlugin(),
            new DummyMerchantPortalGuiMerchantAclRuleExpanderPlugin(),
            new PriceProductMerchantRelationshipMerchantPortalGuiMerchantAclRuleExpanderPlugin(),
            new ProductOfferServicePointMerchantPortalGuiMerchantAclRuleExpanderPlugin(),
            new MerchantRelationRequestMerchantPortalGuiMerchantAclRuleExpanderPlugin(),
            new CommentMerchantPortalGuiMerchantAclRuleExpanderPlugin(),
            new MerchantRelationshipMerchantPortalGuiMerchantAclRuleExpanderPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantAclEntityRuleExpanderPluginInterface>
     */
    protected function getMerchantAclEntityRuleExpanderPlugins(): array
    {
        return [
            new ProductOfferMerchantAclEntityRuleExpanderPlugin(),
            new ProductMerchantAclEntityRuleExpanderPlugin(),
            new MerchantMerchantAclEntityRuleExpanderPlugin(),
            new MerchantSalesOrderMerchantAclEntityRuleExpanderPlugin(),
            new MerchantProductMerchantAclEntityRuleExpanderPlugin(),
            new CurrencyMerchantAclEntityRuleExpanderPlugin(),
            new CountryMerchantAclEntityRuleExpanderPlugin(),
            new StoreMerchantAclEntityRuleExpanderPlugin(),
            new LocaleMerchantAclEntityRuleExpanderPlugin(),
            new SalesMerchantAclEntityRuleExpanderPlugin(),
            new PriceProductMerchantAclEntityRuleExpanderPlugin(),
            new ProductImageMerchantAclEntityRuleExpanderPlugin(),
            new OmsProductOfferReservationMerchantAclEntityRuleExpanderPlugin(),
            new OmsMerchantAclEntityRuleExpanderPlugin(),
            new UrlMerchantAclEntityRuleExpanderPlugin(),
            new RefundMerchantAclEntityRuleExpanderPlugin(),
            new CustomerMerchantAclEntityRuleExpanderPlugin(),
            new DiscountMerchantAclEntityRuleExpanderPlugin(),
            new DiscountPromotionMerchantAclEntityRuleExpanderPlugin(),
            new MerchantRelationRequestMerchantAclEntityRuleExpanderPlugin(),
            new MerchantRelationshipMerchantAclEntityRuleExpanderPlugin(),
            new CompanyUnitAddressMerchantAclEntityRuleExpanderPlugin(),
            new CompanyBusinessUnitMerchantAclEntityRuleExpanderPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclRuleExpanderPluginInterface>
     */
    protected function getMerchantUserAclRuleExpanderPlugins(): array
    {
        return [
            new SecurityMerchantPortalGuiMerchantUserAclRuleExpanderPlugin(),
            new UserMerchantPortalGuiMerchantUserAclRuleExpanderPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\MerchantUserAclEntityRuleExpanderPluginInterface>
     */
    protected function getMerchantUserAclEntityRuleExpanderPlugins(): array
    {
        return [
            new MerchantUserMerchantUserAclEntityRuleExpanderPlugin(),
        ];
    }

    /**
     * @return list<\Spryker\Zed\AclMerchantPortalExtension\Dependency\Plugin\AclEntityConfigurationExpanderPluginInterface>
     */
    protected function getAclEntityConfigurationExpanderPlugins(): array
    {
        return [
            new AclEntityAclEntityConfigurationExpanderPlugin(),
            new AclEntityConfigurationExpanderPlugin(),
            new AvailabilityAclEntityConfigurationExpanderPlugin(),
            new CategoryAclEntityConfigurationExpanderPlugin(),
            new CategoryImageAclEntityConfigurationExpanderPlugin(),
            new CmsBlockAclEntityConfigurationExpanderPlugin(),
            new CommentAclEntityConfigurationExpanderPlugin(),
            new CompanyBusinessUnitAclEntityConfigurationExpanderPlugin(),
            new CompanyUnitAddressAclEntityConfigurationExpanderPlugin(),
            new CountryAclEntityConfigurationExpanderPlugin(),
            new CountryStoreAclEntityConfigurationExpanderPlugin(),
            new CurrencyAclEntityConfigurationExpanderPlugin(),
            new CurrencyStoreAclEntityConfigurationExpanderPlugin(),
            new DiscountAclEntityConfigurationExpanderPlugin(),
            new DiscountPromotionAclEntityConfigurationExpanderPlugin(),
            new GiftCardAclEntityConfigurationExpanderPlugin(),
            new GlossaryAclEntityConfigurationExpanderPlugin(),
            new LocaleAclEntityConfigurationExpanderPlugin(),
            new LocaleStoreAclEntityConfigurationExpanderPlugin(),
            new MerchantAclEntityConfigurationExpanderPlugin(),
            new MerchantCategoryAclEntityConfigurationExpanderPlugin(),
            new MerchantProductAclEntityConfigurationExpanderPlugin(),
            new MerchantProductOfferAclEntityConfigurationExpanderPlugin(),
            new MerchantProfileAclEntityConfigurationExpanderPlugin(),
            new MerchantRelationshipModelAclEntityConfigurationExpanderPlugin(),
            new MerchantSalesOrderAclEntityConfigurationExpanderPlugin(),
            new MerchantStockAclEntityConfigurationExpanderPlugin(),
            new MerchantUserAclEntityConfigurationExpanderPlugin(),
            new OmsAclEntityConfigurationExpanderPlugin(),
            new OmsProductOfferReservationAclEntityConfigurationExpanderPlugin(),
            new PaymentAclEntityConfigurationExpanderPlugin(),
            new PriceProductAclEntityConfigurationExpanderPlugin(),
            new PriceProductMerchantRelationshipAclEntityConfigurationExpanderPlugin(),
            new PriceProductOfferAclEntityConfigurationExpanderPlugin(),
            new ProductAclEntityConfigurationExpanderPlugin(),
            new ProductAttributeAclEntityConfigurationExpanderPlugin(),
            new ProductCategoryAclEntityConfigurationExpanderPlugin(),
            new ProductImageAclEntityConfigurationExpanderPlugin(),
            new ProductOfferAclEntityConfigurationExpanderPlugin(),
            new ProductOfferStockAclEntityConfigurationExpanderPlugin(),
            new ProductOfferValidityAclEntityConfigurationExpanderPlugin(),
            new ProductOptionAclEntityConfigurationExpanderPlugin(),
            new ProductSearchAclEntityConfigurationExpanderPlugin(),
            new ProductValidityAclEntityConfigurationExpanderPlugin(),
            new RefundAclEntityConfigurationExpanderPlugin(),
            new SalesAclEntityConfigurationExpanderPlugin(),
            new SalesInvoiceAclEntityConfigurationExpanderPlugin(),
            new SalesOrderThresholdAclEntityConfigurationExpanderPlugin(),
            new ShipmentAclEntityConfigurationExpanderPlugin(),
            new StateMachineAclEntityConfigurationExpanderPlugin(),
            new StockAclEntityConfigurationExpanderPlugin(),
            new StoreAclEntityConfigurationExpanderPlugin(),
            new TaxAclEntityConfigurationExpanderPlugin(),
            new UrlAclEntityConfigurationExpanderPlugin(),
            new UserPasswordResetAclEntityConfigurationExpanderPlugin(),
            new ShipmentTypeAclEntityConfigurationExpanderPlugin(),
            new ProductOfferShipmentTypeAclEntityConfigurationExpanderPlugin(),
            new ServiceAclEntityConfigurationExpanderPlugin(),
            new ProductOfferServicePointAclEntityConfigurationExpanderPlugin(),
            new MerchantRelationRequestAclEntityConfigurationExpanderPlugin(),
        ];
    }
}
