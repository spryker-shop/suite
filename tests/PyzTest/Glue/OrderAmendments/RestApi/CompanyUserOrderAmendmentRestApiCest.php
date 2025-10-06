<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Glue\OrderAmendments\RestApi;

use Codeception\Util\HttpCode;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use PyzTest\Glue\OrderAmendments\OrderAmendmentsApiTester;
use PyzTest\Glue\OrderAmendments\RestApi\Fixtures\CompanyUserOrderAmendmentRestApiFixtures;
use Spryker\Glue\CartReorderRestApi\CartReorderRestApiConfig;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Glue
 * @group OrderAmendments
 * @group RestApi
 * @group CompanyUserOrderAmendmentRestApiCest
 * Add your own group annotations below this line
 */
class CompanyUserOrderAmendmentRestApiCest
{
    /**
     * @uses \Spryker\Glue\CartReorderRestApi\CartReorderRestApiConfig::ERROR_CODE_ORDER_NOT_FOUND
     */
    protected const RESPONSE_CODE_ORDER_NOT_FOUND = '5801';

    protected const RESPONSE_DETAIL_ORDER_NOT_FOUND = 'Order not found.';

    protected CompanyUserOrderAmendmentRestApiFixtures $fixtures;

    public function loadFixtures(OrderAmendmentsApiTester $I): void
    {
        /** @var \PyzTest\Glue\OrderAmendments\RestApi\Fixtures\CompanyUserOrderAmendmentRestApiFixtures $fixtures */
        $fixtures = $I->loadFixtures(CompanyUserOrderAmendmentRestApiFixtures::class);
        $this->fixtures = $fixtures;
    }

    public function requestCreateOrderAmendmentWithoutPermission(OrderAmendmentsApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransferWithoutEditOrderPermission());

        $requestPayload = [
            'data' => [
                'type' => CartReorderRestApiConfig::RESOURCE_CART_REORDER,
                'attributes' => [
                    'orderReference' => $this->fixtures->getReadyForAmendmentOrderTransfer()->getOrderReferenceOrFail(),
                    'isAmendment' => true,
                ],
            ],
        ];

        // Act
        $I->sendPost($I->getCartReorderUrl(), $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::UNPROCESSABLE_ENTITY);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $errors = $I->getDataFromResponseByJsonPath('$.errors[0]');
        $I->assertEquals($errors[RestErrorMessageTransfer::CODE], static::RESPONSE_CODE_ORDER_NOT_FOUND);
        $I->assertEquals($errors[RestErrorMessageTransfer::STATUS], HttpCode::UNPROCESSABLE_ENTITY);
        $I->assertEquals($errors[RestErrorMessageTransfer::DETAIL], static::RESPONSE_DETAIL_ORDER_NOT_FOUND);
    }

    public function requestCreateOrderAmendmentWithPermission(OrderAmendmentsApiTester $I): void
    {
        // Arrange
        $I->authorizeCustomerToGlue($this->fixtures->getCustomerTransferWithEditOrderPermission());

        $requestPayload = [
            'data' => [
                'type' => CartReorderRestApiConfig::RESOURCE_CART_REORDER,
                'attributes' => [
                    'orderReference' => $this->fixtures->getReadyForAmendmentOrderTransfer()->getOrderReferenceOrFail(),
                    'isAmendment' => true,
                ],
            ],
        ];

        // Act
        $I->sendPost($I->getCartReorderUrl(), $requestPayload);

        // Assert
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesOpenApiSchema();

        $I->amSure('The returned resource is of correct type')
            ->whenI()
            ->seeResponseDataContainsSingleResourceOfType('carts');

        $I->amSure('The returned response data contains amendment order reference')
            ->whenI()
            ->assertResponseContainsAmendmentOrderReference($this->fixtures->getReadyForAmendmentOrderTransfer()->getOrderReferenceOrFail());

        $I->amSure('The returned response data contains correct cart name')
            ->whenI()
            ->assertResponseContainsCorrectCartName(
                sprintf('Editing Order %s', $this->fixtures->getReadyForAmendmentOrderTransfer()->getOrderReferenceOrFail()),
            );
    }
}
