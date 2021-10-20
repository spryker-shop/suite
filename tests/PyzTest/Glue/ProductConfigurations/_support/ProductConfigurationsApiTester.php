<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\ProductConfigurations;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConfigurationTransfer;
use Generated\Shared\Transfer\RestProductConfigurationInstanceAttributesTransfer;
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
class ProductConfigurationsApiTester extends ApiEndToEndTester
{
    use _generated\ProductConfigurationsApiTesterActions;

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    public function amAuthorizedCustomer(CustomerTransfer $customerTransfer): void
    {
        $token = $this->haveAuthorizationToGlue($customerTransfer)->getAccessToken();

        $this->amBearerAuthenticated($token);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationTransfer $productConfigurationTransfer
     *
     * @return void
     */
    public function seeProductConfigurationInstanceEqualToExpectedValue(ProductConfigurationTransfer $productConfigurationTransfer): void
    {
        $productConfigurationData = $this->grabProductConfigurationInstanceDataFromConcreteProductsResource();
        $restProductConfigurationInstanceAttributesTransfer = $this->mapProductConfigurationTransferToRestProductConfigurationInstanceAttributesTransfer(
            $productConfigurationTransfer,
            new RestProductConfigurationInstanceAttributesTransfer(),
        );

        $this->assertEqualsCanonicalizing(
            $productConfigurationData,
            $restProductConfigurationInstanceAttributesTransfer->toArray(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationTransfer $productConfigurationTransfer
     *
     * @return void
     */
    public function seeOrderItemContainProductConfigurationInstance(ProductConfigurationTransfer $productConfigurationTransfer): void
    {
        $productConfigurationData = $this->grabProductConfigurationInstanceDataFromOrdersResource();
        $restProductConfigurationInstanceAttributesTransfer = $this->mapProductConfigurationTransferToRestProductConfigurationInstanceAttributesTransfer(
            $productConfigurationTransfer,
            new RestProductConfigurationInstanceAttributesTransfer(),
        );

        $this->assertEqualsCanonicalizing(
            $productConfigurationData,
            $restProductConfigurationInstanceAttributesTransfer->toArray(),
        );
    }

    /**
     * @param string $resourceName
     * @param string $itemSku
     *
     * @return void
     */
    public function seeCartItemContainsProductConfigurationInstance(
        string $resourceName,
        string $itemSku
    ): void {
        $includedByTypeAndId = $this->grabIncludedByTypeAndId($resourceName, $itemSku);

        $this->assertArrayHasKey('productConfigurationInstance', $includedByTypeAndId);
    }

    /**
     * @return array
     */
    protected function grabProductConfigurationInstanceDataFromConcreteProductsResource(): array
    {
        return $this->grabDataFromResponseByJsonPath('$.data.attributes.productConfigurationInstance');
    }

    /**
     * @return array
     */
    protected function grabProductConfigurationInstanceDataFromOrdersResource(): array
    {
        return $this->grabDataFromResponseByJsonPath('$.data.attributes.items[0].productConfigurationInstance');
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfigurationTransfer $productConfigurationTransfer
     * @param \Generated\Shared\Transfer\RestProductConfigurationInstanceAttributesTransfer $restProductConfigurationInstanceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductConfigurationInstanceAttributesTransfer
     */
    protected function mapProductConfigurationTransferToRestProductConfigurationInstanceAttributesTransfer(
        ProductConfigurationTransfer $productConfigurationTransfer,
        RestProductConfigurationInstanceAttributesTransfer $restProductConfigurationInstanceAttributesTransfer
    ): RestProductConfigurationInstanceAttributesTransfer {
        return $restProductConfigurationInstanceAttributesTransfer->fromArray($productConfigurationTransfer->toArray(), true);
    }
}
