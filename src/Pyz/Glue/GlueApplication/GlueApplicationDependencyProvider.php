<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\GlueApplication;

use Spryker\Glue\AgentAuthRestApi\Plugin\GlueApplication\AgentAccessTokenRestRequestValidatorPlugin;
use Spryker\Glue\AgentAuthRestApi\Plugin\GlueApplication\AgentAccessTokenRestUserFinderPlugin;
use Spryker\Glue\AgentAuthRestApi\Plugin\GlueApplication\AgentAccessTokensResourceRoutePlugin;
use Spryker\Glue\AgentAuthRestApi\Plugin\GlueApplication\AgentCustomerImpersonationAccessTokensResourceRoutePlugin;
use Spryker\Glue\AgentAuthRestApi\Plugin\GlueApplication\AgentCustomerSearchResourceRoutePlugin;
use Spryker\Glue\AgentAuthRestApi\Plugin\GlueApplication\AgentRestUserValidatorPlugin;
use Spryker\Glue\AlternativeProductsRestApi\Plugin\GlueApplication\AbstractAlternativeProductsResourceRoutePlugin;
use Spryker\Glue\AlternativeProductsRestApi\Plugin\GlueApplication\ConcreteAlternativeProductsResourceRoutePlugin;
use Spryker\Glue\AuthRestApi\Plugin\AccessTokensResourceRoutePlugin;
use Spryker\Glue\AuthRestApi\Plugin\FormatAuthenticationErrorResponseHeadersPlugin;
use Spryker\Glue\AuthRestApi\Plugin\GlueApplication\AccessTokenRestRequestValidatorPlugin;
use Spryker\Glue\AuthRestApi\Plugin\GlueApplication\SimultaneousAuthenticationRestRequestValidatorPlugin;
use Spryker\Glue\AuthRestApi\Plugin\RefreshTokensResourceRoutePlugin;
use Spryker\Glue\AuthRestApi\Plugin\RestUserFinderByAccessTokenPlugin;
use Spryker\Glue\CartCodesRestApi\Plugin\GlueApplication\CartCodesResourceRoutePlugin;
use Spryker\Glue\CartCodesRestApi\Plugin\GlueApplication\CartRuleByQuoteResourceRelationshipPlugin;
use Spryker\Glue\CartCodesRestApi\Plugin\GlueApplication\CartVouchersResourceRoutePlugin;
use Spryker\Glue\CartCodesRestApi\Plugin\GlueApplication\GuestCartCodesResourceRoutePlugin;
use Spryker\Glue\CartCodesRestApi\Plugin\GlueApplication\GuestCartVouchersResourceRoutePlugin;
use Spryker\Glue\CartCodesRestApi\Plugin\GlueApplication\VoucherByQuoteResourceRelationshipPlugin;
use Spryker\Glue\CartPermissionGroupsRestApi\Plugin\GlueApplication\CartPermissionGroupByQuoteResourceRelationshipPlugin;
use Spryker\Glue\CartPermissionGroupsRestApi\Plugin\GlueApplication\CartPermissionGroupByShareDetailResourceRelationshipPlugin;
use Spryker\Glue\CartPermissionGroupsRestApi\Plugin\GlueApplication\CartPermissionGroupsResourceRoutePlugin;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\CartsRestApi\Plugin\ControllerBeforeAction\SetAnonymousCustomerIdControllerBeforeActionPlugin;
use Spryker\Glue\CartsRestApi\Plugin\GlueApplication\CartItemsByQuoteResourceRelationshipPlugin;
use Spryker\Glue\CartsRestApi\Plugin\GlueApplication\GuestCartItemsByQuoteResourceRelationshipPlugin;
use Spryker\Glue\CartsRestApi\Plugin\ResourceRoute\CartItemsResourceRoutePlugin;
use Spryker\Glue\CartsRestApi\Plugin\ResourceRoute\CartsResourceRoutePlugin;
use Spryker\Glue\CartsRestApi\Plugin\ResourceRoute\GuestCartItemsResourceRoutePlugin;
use Spryker\Glue\CartsRestApi\Plugin\ResourceRoute\GuestCartsResourceRoutePlugin;
use Spryker\Glue\CartsRestApi\Plugin\Validator\AnonymousCustomerUniqueIdValidatorPlugin;
use Spryker\Glue\CatalogSearchProductsResourceRelationship\Plugin\CatalogSearchAbstractProductsResourceRelationshipPlugin;
use Spryker\Glue\CatalogSearchProductsResourceRelationship\Plugin\CatalogSearchSuggestionsAbstractProductsResourceRelationshipPlugin;
use Spryker\Glue\CatalogSearchRestApi\CatalogSearchRestApiConfig;
use Spryker\Glue\CatalogSearchRestApi\Plugin\CatalogSearchRequestParametersIntegerRestRequestValidatorPlugin;
use Spryker\Glue\CatalogSearchRestApi\Plugin\CatalogSearchResourceRoutePlugin;
use Spryker\Glue\CatalogSearchRestApi\Plugin\CatalogSearchSuggestionsResourceRoutePlugin;
use Spryker\Glue\CategoriesRestApi\Plugin\CategoriesResourceRoutePlugin;
use Spryker\Glue\CategoriesRestApi\Plugin\CategoryResourceRoutePlugin;
use Spryker\Glue\CheckoutRestApi\CheckoutRestApiConfig;
use Spryker\Glue\CheckoutRestApi\Plugin\GlueApplication\CheckoutDataResourcePlugin;
use Spryker\Glue\CheckoutRestApi\Plugin\GlueApplication\CheckoutResourcePlugin;
use Spryker\Glue\CmsPagesContentBannersResourceRelationship\Plugin\GlueApplication\ContentBannerByCmsPageResourceRelationshipPlugin;
use Spryker\Glue\CmsPagesContentProductAbstractListsResourceRelationship\Plugin\GlueApplication\ContentProductAbstractListByCmsPageResourceRelationshipPlugin;
use Spryker\Glue\CmsPagesRestApi\CmsPagesRestApiConfig;
use Spryker\Glue\CmsPagesRestApi\Plugin\GlueApplication\CmsPagesResourceRoutePlugin;
use Spryker\Glue\CompaniesRestApi\Plugin\GlueApplication\CompaniesResourcePlugin;
use Spryker\Glue\CompaniesRestApi\Plugin\GlueApplication\CompanyByCompanyBusinessUnitResourceRelationshipPlugin;
use Spryker\Glue\CompaniesRestApi\Plugin\GlueApplication\CompanyByCompanyRoleResourceRelationshipPlugin;
use Spryker\Glue\CompaniesRestApi\Plugin\GlueApplication\CompanyByCompanyUserResourceRelationshipPlugin;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Plugin\GlueApplication\CompanyBusinessUnitAddressesByCompanyBusinessUnitResourceRelationshipPlugin;
use Spryker\Glue\CompanyBusinessUnitAddressesRestApi\Plugin\GlueApplication\CompanyBusinessUnitAddressesResourcePlugin;
use Spryker\Glue\CompanyBusinessUnitsRestApi\CompanyBusinessUnitsRestApiConfig;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Plugin\GlueApplication\CompanyBusinessUnitByCompanyUserResourceRelationshipPlugin;
use Spryker\Glue\CompanyBusinessUnitsRestApi\Plugin\GlueApplication\CompanyBusinessUnitsResourcePlugin;
use Spryker\Glue\CompanyRolesRestApi\CompanyRolesRestApiConfig;
use Spryker\Glue\CompanyRolesRestApi\Plugin\GlueApplication\CompanyRoleByCompanyUserResourceRelationshipPlugin;
use Spryker\Glue\CompanyRolesRestApi\Plugin\GlueApplication\CompanyRolesResourcePlugin;
use Spryker\Glue\CompanyUserAuthRestApi\Plugin\GlueApplication\CompanyUserAccessTokensResourceRoutePlugin;
use Spryker\Glue\CompanyUsersRestApi\CompanyUsersRestApiConfig;
use Spryker\Glue\CompanyUsersRestApi\Plugin\GlueApplication\CompanyUserByShareDetailResourceRelationshipPlugin;
use Spryker\Glue\CompanyUsersRestApi\Plugin\GlueApplication\CompanyUserRestUserValidatorPlugin;
use Spryker\Glue\CompanyUsersRestApi\Plugin\GlueApplication\CompanyUsersResourceRoutePlugin;
use Spryker\Glue\ContentBannersRestApi\Plugin\ContentBannerResourceRoutePlugin;
use Spryker\Glue\ContentProductAbstractListsRestApi\ContentProductAbstractListsRestApiConfig;
use Spryker\Glue\ContentProductAbstractListsRestApi\Plugin\GlueApplication\AbstractProductsResourceRoutePlugin as ContentProductAbstractListAbstractProductsResourceRoutePlugin;
use Spryker\Glue\ContentProductAbstractListsRestApi\Plugin\GlueApplication\ContentProductAbstractListsResourceRoutePlugin;
use Spryker\Glue\ContentProductAbstractListsRestApi\Plugin\GlueApplication\ProductAbstractByContentProductAbstractListResourceRelationshipPlugin;
use Spryker\Glue\CustomerAccessRestApi\Plugin\GlueApplication\CustomerAccessFormatRequestPlugin;
use Spryker\Glue\CustomerAccessRestApi\Plugin\GlueApplication\CustomerAccessResourceRoutePlugin;
use Spryker\Glue\CustomersRestApi\CustomersRestApiConfig;
use Spryker\Glue\CustomersRestApi\Plugin\AddressesResourceRoutePlugin;
use Spryker\Glue\CustomersRestApi\Plugin\CustomerForgottenPasswordResourceRoutePlugin;
use Spryker\Glue\CustomersRestApi\Plugin\CustomerPasswordResourceRoutePlugin;
use Spryker\Glue\CustomersRestApi\Plugin\CustomerRestorePasswordResourceRoutePlugin;
use Spryker\Glue\CustomersRestApi\Plugin\CustomersResourceRoutePlugin;
use Spryker\Glue\CustomersRestApi\Plugin\CustomersToAddressesRelationshipPlugin;
use Spryker\Glue\CustomersRestApi\Plugin\GlueApplication\CustomerByCompanyUserResourceRelationshipPlugin;
use Spryker\Glue\CustomersRestApi\Plugin\SetCustomerBeforeActionPlugin;
use Spryker\Glue\DiscountPromotionsRestApi\DiscountPromotionsRestApiConfig;
use Spryker\Glue\DiscountPromotionsRestApi\Plugin\GlueApplication\PromotionItemByQuoteTransferResourceRelationshipPlugin;
use Spryker\Glue\EntityTagsRestApi\Plugin\GlueApplication\EntityTagFormatResponseHeadersPlugin;
use Spryker\Glue\EntityTagsRestApi\Plugin\GlueApplication\EntityTagRestRequestValidatorPlugin;
use Spryker\Glue\EventDispatcher\Plugin\Application\EventDispatcherApplicationPlugin;
use Spryker\Glue\GiftCardsRestApi\Plugin\GlueApplication\GiftCardByQuoteResourceRelationshipPlugin;
use Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider as SprykerGlueApplicationDependencyProvider;
use Spryker\Glue\GlueApplication\Plugin\Application\GlueApplicationApplicationPlugin;
use Spryker\Glue\GlueApplication\Plugin\GlueApplication\PaginationParametersValidateHttpRequestPlugin;
use Spryker\Glue\GlueApplication\Plugin\Rest\SetStoreCurrentLocaleBeforeActionPlugin;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface;
use Spryker\Glue\HealthCheck\Plugin\HealthCheckResourceRoutePlugin;
use Spryker\Glue\Http\Plugin\Application\HttpApplicationPlugin;
use Spryker\Glue\MerchantOpeningHoursRestApi\Plugin\GlueApplication\MerchantOpeningHoursByMerchantReferenceResourceRelationshipPlugin;
use Spryker\Glue\MerchantOpeningHoursRestApi\Plugin\GlueApplication\MerchantOpeningHoursResourceRoutePlugin;
use Spryker\Glue\MerchantProductOffersRestApi\MerchantProductOffersRestApiConfig;
use Spryker\Glue\MerchantProductOffersRestApi\Plugin\GlueApplication\ConcreteProductsProductOffersResourceRoutePlugin;
use Spryker\Glue\MerchantProductOffersRestApi\Plugin\GlueApplication\ProductOffersByProductConcreteSkuResourceRelationshipPlugin;
use Spryker\Glue\MerchantProductOffersRestApi\Plugin\GlueApplication\ProductOffersResourceRoutePlugin;
use Spryker\Glue\MerchantsRestApi\MerchantsRestApiConfig;
use Spryker\Glue\MerchantsRestApi\Plugin\GlueApplication\MerchantAddressByMerchantReferenceResourceRelationshipPlugin;
use Spryker\Glue\MerchantsRestApi\Plugin\GlueApplication\MerchantAddressesResourceRoutePlugin;
use Spryker\Glue\MerchantsRestApi\Plugin\GlueApplication\MerchantByMerchantReferenceResourceRelationshipPlugin;
use Spryker\Glue\MerchantsRestApi\Plugin\GlueApplication\MerchantsByOrderResourceRelationshipPlugin;
use Spryker\Glue\MerchantsRestApi\Plugin\GlueApplication\MerchantsResourceRoutePlugin;
use Spryker\Glue\NavigationsCategoryNodesResourceRelationship\Plugin\GlueApplication\CategoryNodeByResourceIdResourceRelationshipPlugin;
use Spryker\Glue\NavigationsRestApi\NavigationsRestApiConfig;
use Spryker\Glue\NavigationsRestApi\Plugin\ResourceRoute\NavigationsResourceRoutePlugin;
use Spryker\Glue\OrderPaymentsRestApi\Plugin\OrderPaymentsResourceRoutePlugin;
use Spryker\Glue\OrdersRestApi\OrdersRestApiConfig;
use Spryker\Glue\OrdersRestApi\Plugin\OrderItemByResourceIdResourceRelationshipPlugin;
use Spryker\Glue\OrdersRestApi\Plugin\OrderRelationshipByOrderReferencePlugin;
use Spryker\Glue\OrdersRestApi\Plugin\OrdersResourceRoutePlugin;
use Spryker\Glue\PaymentsRestApi\Plugin\GlueApplication\PaymentMethodsByCheckoutDataResourceRelationshipPlugin;
use Spryker\Glue\ProductAvailabilitiesRestApi\Plugin\AbstractProductAvailabilitiesRoutePlugin;
use Spryker\Glue\ProductAvailabilitiesRestApi\Plugin\ConcreteProductAvailabilitiesRoutePlugin;
use Spryker\Glue\ProductAvailabilitiesRestApi\Plugin\GlueApplication\AbstractProductAvailabilitiesByResourceIdResourceRelationshipPlugin;
use Spryker\Glue\ProductAvailabilitiesRestApi\Plugin\GlueApplication\ConcreteProductAvailabilitiesByResourceIdResourceRelationshipPlugin;
use Spryker\Glue\ProductBundleCartsRestApi\Plugin\GlueApplication\BundledItemByQuoteResourceRelationshipPlugin;
use Spryker\Glue\ProductBundleCartsRestApi\Plugin\GlueApplication\BundleItemByQuoteResourceRelationshipPlugin;
use Spryker\Glue\ProductBundleCartsRestApi\Plugin\GlueApplication\GuestBundleItemByQuoteResourceRelationshipPlugin;
use Spryker\Glue\ProductBundleCartsRestApi\ProductBundleCartsRestApiConfig;
use Spryker\Glue\ProductBundlesRestApi\Plugin\GlueApplication\BundledProductByProductConcreteSkuResourceRelationshipPlugin;
use Spryker\Glue\ProductBundlesRestApi\Plugin\GlueApplication\ConcreteProductsBundledProductsResourceRoutePlugin;
use Spryker\Glue\ProductBundlesRestApi\ProductBundlesRestApiConfig;
use Spryker\Glue\ProductImageSetsRestApi\Plugin\AbstractProductImageSetsRoutePlugin;
use Spryker\Glue\ProductImageSetsRestApi\Plugin\ConcreteProductImageSetsRoutePlugin;
use Spryker\Glue\ProductImageSetsRestApi\Plugin\Relationship\AbstractProductsProductImageSetsResourceRelationshipPlugin;
use Spryker\Glue\ProductImageSetsRestApi\Plugin\Relationship\ConcreteProductsProductImageSetsResourceRelationshipPlugin;
use Spryker\Glue\ProductLabelsRestApi\Plugin\GlueApplication\ProductLabelByProductConcreteSkuResourceRelationshipPlugin;
use Spryker\Glue\ProductLabelsRestApi\Plugin\GlueApplication\ProductLabelsRelationshipByResourceIdPlugin;
use Spryker\Glue\ProductLabelsRestApi\Plugin\GlueApplication\ProductLabelsResourceRoutePlugin;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Plugin\GlueApplication\ProductMeasurementUnitsByProductConcreteResourceRelationshipPlugin;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Plugin\GlueApplication\ProductMeasurementUnitsBySalesUnitResourceRelationshipPlugin;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Plugin\GlueApplication\ProductMeasurementUnitsResourceRoutePlugin;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Plugin\GlueApplication\SalesUnitsByCartItemResourceRelationshipPlugin;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Plugin\GlueApplication\SalesUnitsByProductConcreteResourceRelationshipPlugin;
use Spryker\Glue\ProductMeasurementUnitsRestApi\Plugin\GlueApplication\SalesUnitsResourceRoutePlugin;
use Spryker\Glue\ProductMeasurementUnitsRestApi\ProductMeasurementUnitsRestApiConfig;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Plugin\GlueApplication\ProductOfferAvailabilitiesByProductOfferReferenceResourceRelationshipPlugin;
use Spryker\Glue\ProductOfferAvailabilitiesRestApi\Plugin\GlueApplication\ProductOfferAvailabilitiesResourceRoutePlugin;
use Spryker\Glue\ProductOfferPricesRestApi\Plugin\GlueApplication\ProductOfferPriceByProductOfferReferenceResourceRelationshipPlugin;
use Spryker\Glue\ProductOfferPricesRestApi\Plugin\GlueApplication\ProductOfferPricesResourceRoutePlugin;
use Spryker\Glue\ProductOptionsRestApi\Plugin\GlueApplication\ProductOptionsByProductAbstractSkuResourceRelationshipPlugin;
use Spryker\Glue\ProductOptionsRestApi\Plugin\GlueApplication\ProductOptionsByProductConcreteSkuResourceRelationshipPlugin;
use Spryker\Glue\ProductPricesRestApi\Plugin\AbstractProductPricesRoutePlugin;
use Spryker\Glue\ProductPricesRestApi\Plugin\ConcreteProductPricesRoutePlugin;
use Spryker\Glue\ProductPricesRestApi\Plugin\GlueApplication\AbstractProductPricesByResourceIdResourceRelationshipPlugin;
use Spryker\Glue\ProductPricesRestApi\Plugin\GlueApplication\ConcreteProductPricesByResourceIdResourceRelationshipPlugin;
use Spryker\Glue\ProductPricesRestApi\Plugin\GlueApplication\CurrencyParameterValidatorPlugin;
use Spryker\Glue\ProductPricesRestApi\Plugin\GlueApplication\PriceModeParameterValidatorPlugin;
use Spryker\Glue\ProductPricesRestApi\Plugin\GlueApplication\SetCurrencyBeforeActionPlugin;
use Spryker\Glue\ProductPricesRestApi\Plugin\GlueApplication\SetPriceModeBeforeActionPlugin;
use Spryker\Glue\ProductReviewsRestApi\Plugin\GlueApplication\AbstractProductsProductReviewsResourceRoutePlugin;
use Spryker\Glue\ProductReviewsRestApi\Plugin\GlueApplication\ProductReviewsRelationshipByProductAbstractSkuPlugin;
use Spryker\Glue\ProductReviewsRestApi\Plugin\GlueApplication\ProductReviewsRelationshipByProductConcreteSkuPlugin;
use Spryker\Glue\ProductsCategoriesResourceRelationship\Plugin\AbstractProductsCategoriesResourceRelationshipPlugin;
use Spryker\Glue\ProductsRestApi\Plugin\AbstractProductsResourceRoutePlugin;
use Spryker\Glue\ProductsRestApi\Plugin\ConcreteProductsResourceRoutePlugin;
use Spryker\Glue\ProductsRestApi\Plugin\GlueApplication\ConcreteProductBySkuResourceRelationshipPlugin;
use Spryker\Glue\ProductsRestApi\Plugin\GlueApplication\ConcreteProductsByProductConcreteIdsResourceRelationshipPlugin;
use Spryker\Glue\ProductsRestApi\Plugin\GlueApplication\ProductAbstractByProductAbstractSkuResourceRelationshipPlugin;
use Spryker\Glue\ProductsRestApi\Plugin\GlueApplication\ProductAbstractBySkuResourceRelationshipPlugin;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Spryker\Glue\ProductTaxSetsRestApi\Plugin\GlueApplication\ProductTaxSetByProductAbstractSkuResourceRelationshipPlugin;
use Spryker\Glue\ProductTaxSetsRestApi\Plugin\GlueApplication\ProductTaxSetsResourceRoutePlugin;
use Spryker\Glue\RelatedProductsRestApi\Plugin\GlueApplication\RelatedProductsResourceRoutePlugin;
use Spryker\Glue\RestRequestValidator\Plugin\ValidateRestRequestAttributesPlugin;
use Spryker\Glue\Router\Plugin\Application\RouterApplicationPlugin;
use Spryker\Glue\SalesReturnsRestApi\Plugin\ReturnItemByReturnResourceRelationshipPlugin;
use Spryker\Glue\SalesReturnsRestApi\Plugin\ReturnReasonsResourceRoutePlugin;
use Spryker\Glue\SalesReturnsRestApi\Plugin\ReturnsResourceRoutePlugin;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;
use Spryker\Glue\Session\Plugin\Application\SessionApplicationPlugin;
use Spryker\Glue\SharedCartsRestApi\Plugin\GlueApplication\SharedCartByCartIdResourceRelationshipPlugin;
use Spryker\Glue\SharedCartsRestApi\Plugin\GlueApplication\SharedCartsResourceRoutePlugin;
use Spryker\Glue\SharedCartsRestApi\SharedCartsRestApiConfig;
use Spryker\Glue\ShipmentsRestApi\Plugin\GlueApplication\ShipmentMethodsByCheckoutDataResourceRelationshipPlugin;
use Spryker\Glue\ShoppingListsRestApi\Plugin\GlueApplication\ShoppingListItemByShoppingListResourceRelationshipPlugin;
use Spryker\Glue\ShoppingListsRestApi\Plugin\GlueApplication\ShoppingListItemsResourcePlugin;
use Spryker\Glue\ShoppingListsRestApi\Plugin\GlueApplication\ShoppingListsResourcePlugin;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;
use Spryker\Glue\StoresRestApi\Plugin\StoresResourceRoutePlugin;
use Spryker\Glue\UpSellingProductsRestApi\Plugin\GlueApplication\CartUpSellingProductsResourceRoutePlugin;
use Spryker\Glue\UpSellingProductsRestApi\Plugin\GlueApplication\GuestCartUpSellingProductsResourceRoutePlugin;
use Spryker\Glue\UrlsRestApi\Plugin\GlueApplication\UrlResolverResourceRoutePlugin;
use Spryker\Glue\WishlistsRestApi\Plugin\WishlistItemsResourceRoutePlugin;
use Spryker\Glue\WishlistsRestApi\Plugin\WishlistRelationshipByResourceIdPlugin;
use Spryker\Glue\WishlistsRestApi\Plugin\WishlistsResourceRoutePlugin;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
 */
class GlueApplicationDependencyProvider extends SprykerGlueApplicationDependencyProvider
{
    /**
     * {@inheritDoc}
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRoutePluginInterface[]
     */
    protected function getResourceRoutePlugins(): array
    {
        return [
            new AccessTokensResourceRoutePlugin(),
            new RefreshTokensResourceRoutePlugin(),
            new CatalogSearchResourceRoutePlugin(),
            new StoresResourceRoutePlugin(),
            new CatalogSearchSuggestionsResourceRoutePlugin(),
            new ConcreteProductAvailabilitiesRoutePlugin(),
            new AbstractProductAvailabilitiesRoutePlugin(),
            new CategoriesResourceRoutePlugin(),
            new CategoryResourceRoutePlugin(),
            new CustomersResourceRoutePlugin(),
            new CustomerForgottenPasswordResourceRoutePlugin(),
            new CustomerRestorePasswordResourceRoutePlugin(),
            new AbstractProductsResourceRoutePlugin(),
            new ConcreteProductsResourceRoutePlugin(),
            new AbstractProductPricesRoutePlugin(),
            new ConcreteProductPricesRoutePlugin(),
            new CartsResourceRoutePlugin(),
            new CartItemsResourceRoutePlugin(),
            new AbstractProductImageSetsRoutePlugin(),
            new ConcreteProductImageSetsRoutePlugin(),
            new OrdersResourceRoutePlugin(),
            new WishlistsResourceRoutePlugin(),
            new WishlistItemsResourceRoutePlugin(),
            new ProductTaxSetsResourceRoutePlugin(),
            new CustomerPasswordResourceRoutePlugin(),
            new AddressesResourceRoutePlugin(),
            new GuestCartsResourceRoutePlugin(),
            new GuestCartItemsResourceRoutePlugin(),
            new ProductLabelsResourceRoutePlugin(),
            new CheckoutDataResourcePlugin(),
            new CheckoutResourcePlugin(),
            new CompanyUsersResourceRoutePlugin(),
            new ConcreteAlternativeProductsResourceRoutePlugin(),
            new AbstractAlternativeProductsResourceRoutePlugin(),
            new CompanyUserAccessTokensResourceRoutePlugin(),
            new NavigationsResourceRoutePlugin(),
            new RelatedProductsResourceRoutePlugin(),
            new CartUpSellingProductsResourceRoutePlugin(),
            new GuestCartUpSellingProductsResourceRoutePlugin(),
            new ContentBannerResourceRoutePlugin(),
            new CartPermissionGroupsResourceRoutePlugin(),
            new ContentProductAbstractListAbstractProductsResourceRoutePlugin(),
            new OrderPaymentsResourceRoutePlugin(),
            new CompanyBusinessUnitAddressesResourcePlugin(),
            new CompanyRolesResourcePlugin(),
            new CompaniesResourcePlugin(),
            new CompanyBusinessUnitsResourcePlugin(),
            new SharedCartsResourceRoutePlugin(),
            new UrlResolverResourceRoutePlugin(),
            new CartVouchersResourceRoutePlugin(),
            new GuestCartVouchersResourceRoutePlugin(),
            new CartCodesResourceRoutePlugin(),
            new GuestCartCodesResourceRoutePlugin(),
            new CustomerAccessResourceRoutePlugin(),
            new AbstractProductsProductReviewsResourceRoutePlugin(),
            new HealthCheckResourceRoutePlugin(),
            new ReturnReasonsResourceRoutePlugin(),
            new ReturnsResourceRoutePlugin(),
            new ShoppingListsResourcePlugin(),
            new ShoppingListItemsResourcePlugin(),
            new ProductMeasurementUnitsResourceRoutePlugin(),
            new SalesUnitsResourceRoutePlugin(),
            new MerchantsResourceRoutePlugin(),
            new MerchantOpeningHoursResourceRoutePlugin(),
            new MerchantAddressesResourceRoutePlugin(),
            new ProductOffersResourceRoutePlugin(),
            new ConcreteProductsProductOffersResourceRoutePlugin(),
            new ProductOfferAvailabilitiesResourceRoutePlugin(),
            new ProductOfferPricesResourceRoutePlugin(),
            new CmsPagesResourceRoutePlugin(),
            new ContentProductAbstractListsResourceRoutePlugin(),
            new AgentAccessTokensResourceRoutePlugin(),
            new AgentCustomerImpersonationAccessTokensResourceRoutePlugin(),
            new AgentCustomerSearchResourceRoutePlugin(),
            new ConcreteProductsBundledProductsResourceRoutePlugin(),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateRestRequestPluginInterface[]
     */
    protected function getValidateRestRequestPlugins(): array
    {
        return [
            new AnonymousCustomerUniqueIdValidatorPlugin(),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestRequestValidatorPluginInterface[]
     */
    protected function getRestRequestValidatorPlugins(): array
    {
        return [
            new AccessTokenRestRequestValidatorPlugin(),
            new AgentAccessTokenRestRequestValidatorPlugin(),
            new SimultaneousAuthenticationRestRequestValidatorPlugin(),
            new ValidateRestRequestAttributesPlugin(),
            new CurrencyParameterValidatorPlugin(),
            new PriceModeParameterValidatorPlugin(),
            new EntityTagRestRequestValidatorPlugin(),
            new CatalogSearchRequestParametersIntegerRestRequestValidatorPlugin(),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserValidatorPluginInterface[]
     */
    protected function getRestUserValidatorPlugins(): array
    {
        return [
            new CompanyUserRestUserValidatorPlugin(),
            new AgentRestUserValidatorPlugin(),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ValidateHttpRequestPluginInterface[]
     */
    protected function getValidateHttpRequestPlugins(): array
    {
        return [
            new PaginationParametersValidateHttpRequestPlugin(),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatRequestPluginInterface[]
     */
    protected function getFormatRequestPlugins(): array
    {
        return [
            new CustomerAccessFormatRequestPlugin(),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseHeadersPluginInterface[]
     */
    protected function getFormatResponseHeadersPlugins(): array
    {
        return [
            new FormatAuthenticationErrorResponseHeadersPlugin(),
            new EntityTagFormatResponseHeadersPlugin(),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ControllerBeforeActionPluginInterface[]
     */
    protected function getControllerBeforeActionPlugins(): array
    {
        return [
            new SetStoreCurrentLocaleBeforeActionPlugin(),
            new SetAnonymousCustomerIdControllerBeforeActionPlugin(),
            new SetCustomerBeforeActionPlugin(),
            new SetCurrencyBeforeActionPlugin(),
            new SetPriceModeBeforeActionPlugin(),
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @param \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface $resourceRelationshipCollection
     *
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\ResourceRelationshipCollectionInterface
     */
    protected function getResourceRelationshipPlugins(
        ResourceRelationshipCollectionInterface $resourceRelationshipCollection
    ): ResourceRelationshipCollectionInterface {
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            new AbstractProductsProductImageSetsResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            new ConcreteProductsProductImageSetsResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_CART_ITEMS,
            new ConcreteProductBySkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            new ConcreteProductBySkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
            new ConcreteProductBySkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CatalogSearchRestApiConfig::RESOURCE_CATALOG_SEARCH,
            new CatalogSearchAbstractProductsResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CatalogSearchRestApiConfig::RESOURCE_CATALOG_SEARCH_SUGGESTIONS,
            new CatalogSearchSuggestionsAbstractProductsResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            new AbstractProductAvailabilitiesByResourceIdResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            new ConcreteProductAvailabilitiesByResourceIdResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            new AbstractProductPricesByResourceIdResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            new ConcreteProductPricesByResourceIdResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            new AbstractProductsCategoriesResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            new ProductTaxSetByProductAbstractSkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CustomersRestApiConfig::RESOURCE_CUSTOMERS,
            new CustomersToAddressesRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CustomersRestApiConfig::RESOURCE_CUSTOMERS,
            new WishlistRelationshipByResourceIdPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            new ProductLabelsRelationshipByResourceIdPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            new ProductLabelByProductConcreteSkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CheckoutRestApiConfig::RESOURCE_CHECKOUT,
            new OrderRelationshipByOrderReferencePlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CompanyUsersRestApiConfig::RESOURCE_COMPANY_USERS,
            new CompanyByCompanyUserResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CompanyUsersRestApiConfig::RESOURCE_COMPANY_USERS,
            new CompanyBusinessUnitByCompanyUserResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CompanyUsersRestApiConfig::RESOURCE_COMPANY_USERS,
            new CompanyRoleByCompanyUserResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            NavigationsRestApiConfig::RESOURCE_NAVIGATIONS,
            new CategoryNodeByResourceIdResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            new ConcreteProductsByProductConcreteIdsResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CompanyRolesRestApiConfig::RESOURCE_COMPANY_ROLES,
            new CompanyByCompanyRoleResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CompanyBusinessUnitsRestApiConfig::RESOURCE_COMPANY_BUSINESS_UNITS,
            new CompanyByCompanyBusinessUnitResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CompanyBusinessUnitsRestApiConfig::RESOURCE_COMPANY_BUSINESS_UNITS,
            new CompanyBusinessUnitAddressesByCompanyBusinessUnitResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CompanyUsersRestApiConfig::RESOURCE_COMPANY_USERS,
            new CustomerByCompanyUserResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_CARTS,
            new CartPermissionGroupByQuoteResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_CARTS,
            new CartItemsByQuoteResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_CARTS,
            new SharedCartByCartIdResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            SharedCartsRestApiConfig::RESOURCE_SHARED_CARTS,
            new CartPermissionGroupByShareDetailResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            SharedCartsRestApiConfig::RESOURCE_SHARED_CARTS,
            new CompanyUserByShareDetailResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_CARTS,
            new VoucherByQuoteResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS,
            new VoucherByQuoteResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_CARTS,
            new GiftCardByQuoteResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS,
            new GiftCardByQuoteResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_CARTS,
            new CartRuleByQuoteResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS,
            new CartRuleByQuoteResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            new ProductOptionsByProductAbstractSkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            new ProductOptionsByProductConcreteSkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
            new ProductReviewsRelationshipByProductAbstractSkuPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            new ProductReviewsRelationshipByProductConcreteSkuPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LIST_ITEMS,
            new ConcreteProductBySkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LISTS,
            new ShoppingListItemByShoppingListResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
            new ShipmentMethodsByCheckoutDataResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CheckoutRestApiConfig::RESOURCE_CHECKOUT_DATA,
            new PaymentMethodsByCheckoutDataResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_CARTS,
            new PromotionItemByQuoteTransferResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS,
            new PromotionItemByQuoteTransferResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            DiscountPromotionsRestApiConfig::RESOURCE_PROMOTIONAL_ITEMS,
            new ProductAbstractBySkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            new ProductMeasurementUnitsByProductConcreteResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            new SalesUnitsByProductConcreteResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductMeasurementUnitsRestApiConfig::RESOURCE_SALES_UNITS,
            new ProductMeasurementUnitsBySalesUnitResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_CART_ITEMS,
            new SalesUnitsByCartItemResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS_ITEMS,
            new SalesUnitsByCartItemResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            SalesReturnsRestApiConfig::RESOURCE_RETURNS,
            new ReturnItemByReturnResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            SalesReturnsRestApiConfig::RESOURCE_RETURN_ITEMS,
            new OrderItemByResourceIdResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            MerchantsRestApiConfig::RESOURCE_MERCHANTS,
            new MerchantOpeningHoursByMerchantReferenceResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            MerchantsRestApiConfig::RESOURCE_MERCHANTS,
            new MerchantAddressByMerchantReferenceResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            OrdersRestApiConfig::RESOURCE_ORDERS,
            new MerchantsByOrderResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            new ProductOffersByProductConcreteSkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            MerchantProductOffersRestApiConfig::RESOURCE_PRODUCT_OFFERS,
            new ProductOfferAvailabilitiesByProductOfferReferenceResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            MerchantProductOffersRestApiConfig::RESOURCE_PRODUCT_OFFERS,
            new ProductOfferPriceByProductOfferReferenceResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            MerchantProductOffersRestApiConfig::RESOURCE_PRODUCT_OFFERS,
            new MerchantByMerchantReferenceResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CmsPagesRestApiConfig::RESOURCE_CMS_PAGES,
            new ContentBannerByCmsPageResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CmsPagesRestApiConfig::RESOURCE_CMS_PAGES,
            new ContentProductAbstractListByCmsPageResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ContentProductAbstractListsRestApiConfig::RESOURCE_CONTENT_PRODUCT_ABSTRACT_LISTS,
            new ProductAbstractByContentProductAbstractListResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            new ProductAbstractByProductAbstractSkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductBundlesRestApiConfig::RESOURCE_BUNDLED_PRODUCTS,
            new ConcreteProductBySkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
            new BundledProductByProductConcreteSkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_CARTS,
            new BundleItemByQuoteResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS,
            new GuestBundleItemByQuoteResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductBundleCartsRestApiConfig::RESOURCE_BUNDLE_ITEMS,
            new BundledItemByQuoteResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductBundleCartsRestApiConfig::RESOURCE_BUNDLE_ITEMS,
            new ConcreteProductBySkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            ProductBundleCartsRestApiConfig::RESOURCE_BUNDLED_ITEMS,
            new ConcreteProductBySkuResourceRelationshipPlugin()
        );
        $resourceRelationshipCollection->addRelationship(
            CartsRestApiConfig::RESOURCE_GUEST_CARTS,
            new GuestCartItemsByQuoteResourceRelationshipPlugin()
        );

        return $resourceRelationshipCollection;
    }

    /**
     * @return \Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\RestUserFinderPluginInterface[]
     */
    protected function getRestUserFinderPlugins(): array
    {
        return [
            new RestUserFinderByAccessTokenPlugin(),
            new AgentAccessTokenRestUserFinderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface[]
     */
    protected function getApplicationPlugins(): array
    {
        return [
            new HttpApplicationPlugin(),
            new SessionApplicationPlugin(),
            new EventDispatcherApplicationPlugin(),
            new GlueApplicationApplicationPlugin(),
            new RouterApplicationPlugin(),
        ];
    }
}
