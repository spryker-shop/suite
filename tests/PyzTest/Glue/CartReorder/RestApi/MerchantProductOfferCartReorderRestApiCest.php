<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\CartReorder\RestApi;

use Codeception\Util\HttpCode;
use PyzTest\Glue\CartReorder\CartReorderApiTester;
use PyzTest\Glue\CartReorder\RestApi\Fixtures\MerchantProductOfferCartReorderRestApiFixtures;
use Spryker\Glue\CartReorderRestApi\CartReorderRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group CartReorder
 * @group RestApi
 * @group MerchantProductOfferCartReorderRestApiCest
 * Add your own group annotations below this line
 */
class MerchantProductOfferCartReorderRestApiCest
{
    /**
     * @var \PyzTest\Glue\CartReorder\RestApi\Fixtures\MerchantProductOfferCartReorderRestApiFixtures
     */
    protected MerchantProductOfferCartReorderRestApiFixtures $fixtures;

    /**
     * @param \PyzTest\Glue\CartReorder\CartReorderApiTester $I
     *
     * @return void
     */
    public function loadFixtures(CartReorderApiTester $I): void
    {
        /** @var \PyzTest\Glue\CartReorder\RestApi\Fixtures\MerchantProductOfferCartReorderRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(MerchantProductOfferCartReorderRestApiFixtures::class);

        $this->fixtures = $fixtures;
    }

    /**
     * @param \PyzTest\Glue\CartReorder\CartReorderApiTester $I
     *
     * @return void
     */
    public function requestCreateCartReorder(CartReorderApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransfer());

        $saveOrderTransfer = $this->fixtures->getOrderWithMerchantProductOffer();
        $requestPayload = [
            'data' => [
                'type' => CartReorderRestApiConfig::RESOURCE_CART_REORDER,
                'attributes' => [
                    'orderReference' => $saveOrderTransfer->getOrderReferenceOrFail(),
                ],
            ],
        ];

        // Act
        $I->sendPost($I->getCartReorderUrl(), $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource is of correct type.')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType(CartReorderApiTester::RESOURCE_CARTS);

        $I->amSure('The returned response data contains correct cart name.')
            ->whenI()
            ->assertResponseContainsCorrectCartName(
                sprintf('Reorder from Order %s', $saveOrderTransfer->getOrderReferenceOrFail()),
            );

        $I->amSure('The returned response includes first item.')
            ->whenI()
            ->assertResponseContainsItemBySku($this->fixtures->getProductConcreteTransfer()->getSkuOrFail());
        $I->amSure('The first item has correct quantity.')
            ->whenI()
            ->assertItemHasCorrectQuantity($this->fixtures->getProductConcreteTransfer()->getSkuOrFail(), 1);

        $I->amSure('The returned response includes second item.')
            ->whenI()
            ->assertResponseContainsItemBySku($this->fixtures->getProductConcreteTransferWithMerchantProductOffer()->getSkuOrFail());
        $I->amSure('The second item has correct quantity.')
            ->whenI()
            ->assertItemHasCorrectQuantity(
                $this->fixtures->getProductConcreteTransferWithMerchantProductOffer()->getSkuOrFail(),
                2,
            );
        $I->amSure('The second item has correct merchant reference.')
            ->whenI()
            ->assertItemHasMerchantReference(
                $this->fixtures->getProductConcreteTransferWithMerchantProductOffer()->getSkuOrFail(),
                $this->fixtures->getMerchantTransfer()->getMerchantReferenceOrFail(),
            );
        $I->amSure('The second item has correct product offer reference.')
            ->whenI()
            ->assertItemHasProductOfferReference(
                $this->fixtures->getProductConcreteTransferWithMerchantProductOffer()->getSkuOrFail(),
                $this->fixtures->getProductOfferTransfer()->getProductOfferReferenceOrFail(),
            );
    }
}
