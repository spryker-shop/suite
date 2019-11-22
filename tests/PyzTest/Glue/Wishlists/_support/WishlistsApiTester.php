<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Wishlists;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;
use SprykerTest\Glue\Testify\Tester\ApiEndToEndTester;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class WishlistsApiTester extends ApiEndToEndTester
{
    use _generated\WishlistsApiTesterActions;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function requestCustomerLogin(CustomerTransfer $customerTransfer): void
    {
        $token = $this->haveAuthorizationToGlue($customerTransfer)->getAccessToken();
        $this->amBearerAuthenticated($token);
    }

    /**
     * @param string[] $includes
     *
     * @return string
     */
    public function formatQueryInclude(array $includes = []): string
    {
        if (!$includes) {
            return '';
        }

        return sprintf('?%s=%s', RequestConstantsInterface::QUERY_INCLUDE, implode(',', $includes));
    }

    /**
     * @param string $productConcreteSku
     * @param string[] $includes
     *
     * @return string
     */
    public function buildProductConcreteUrl(string $productConcreteSku, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceConcreteProducts}/{productConcreteSku}' . $this->formatQueryInclude($includes),
            [
                'resourceConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                'productConcreteSku' => $productConcreteSku,
            ]
        );
    }

    /**
     * @param int $idProductLabel
     * @param string[] $includes
     *
     * @return string
     */
    public function buildProductLabelUrl(int $idProductLabel, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceProductLabels}/{idProductLabel}' . $this->formatQueryInclude($includes),
            [
                'resourceProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                'idProductLabel' => $idProductLabel,
            ]
        );
    }

    /**
     * @param string $wishlistUuid
     * @param string[] $includes
     *
     * @return string
     */
    public function buildWishlistUrl(string $wishlistUuid, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceWishlists}/{wishlistUuid}' . $this->formatQueryInclude($includes),
            [
                'resourceWishlists' => WishlistsRestApiConfig::RESOURCE_WISHLISTS,
                'wishlistUuid' => $wishlistUuid,
            ]
        );
    }

    /**
     * @param string[] $includes
     *
     * @return string
     */
    public function buildWishlistsUrl(array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceWishlists}' . $this->formatQueryInclude($includes),
            [
                'resourceWishlists' => WishlistsRestApiConfig::RESOURCE_WISHLISTS,
            ]
        );
    }

    /**
     * @param string $wishlistUuid
     * @param string $productConcreteSku
     * @param string[] $includes
     *
     * @return string
     */
    public function buildWishlistItemUrl(string $wishlistUuid, string $productConcreteSku, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceWishlists}/{wishlistUuid}/{resourceWishlistItems}/{productConcreteSku}' . $this->formatQueryInclude($includes),
            [
                'resourceWishlists' => WishlistsRestApiConfig::RESOURCE_WISHLISTS,
                'wishlistUuid' => $wishlistUuid,
                'resourceWishlistItems' => WishlistsRestApiConfig::RESOURCE_WISHLIST_ITEMS,
                'productConcreteSku' => $productConcreteSku,
            ]
        );
    }
}
