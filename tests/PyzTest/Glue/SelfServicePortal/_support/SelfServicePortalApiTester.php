<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Glue\SelfServicePortal;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerTest\Glue\Testify\Tester\ApiEndToEndTester;

/**
 * Inherited Methods
 *
 * @method void wantTo($text)
 * @method void wantToTest($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause($vars = [])
 *
 * @SuppressWarnings(PHPMD)
 */
class SelfServicePortalApiTester extends ApiEndToEndTester
{
    use _generated\SelfServicePortalApiTesterActions;

    public function authorizeCustomerToGlue(CustomerTransfer $customerTransfer): void
    {
        $oauthResponseTransfer = $this->haveAuthorizationToGlue($customerTransfer);
        $this->amBearerAuthenticated($oauthResponseTransfer->getAccessToken());
    }

    public function getSspAssetsUrl(): string
    {
        return $this->formatUrl('ssp-assets');
    }

    public function getSspAssetUrl(string $assetId): string
    {
        return $this->formatUrl('ssp-assets/{assetId}', ['assetId' => $assetId]);
    }

    public function assertResponseMatchAssetReference(string $assetReference): void
    {
        $attributes = $this->getDataFromResponseByJsonPath('$.data[0].attributes');

        $this->assertArrayHasKey('reference', $attributes);
        $this->assertSame($assetReference, $attributes['reference']);
    }
}
