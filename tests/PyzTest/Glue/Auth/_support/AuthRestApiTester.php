<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Glue\Auth;

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
 * @SuppressWarnings(\PyzTest\Glue\Auth\PHPMD)
 */
class AuthRestApiTester extends ApiEndToEndTester
{
    use _generated\AuthRestApiTesterActions;

    /**
     * @var string
     */
    private const ACCESS_TOKEN_JSON_PATH = '$.data.attributes.accessToken';

    /**
     * @var string
     */
    private const REFRESH_TOKEN_JSON_PATH = '$.data.attributes.refreshToken';

    /**
     * @return void
     */
    public function seeResponseHasAccessToken(): void
    {
        $this->assertNotEmpty($this->getDataFromResponseByJsonPath(self::ACCESS_TOKEN_JSON_PATH));
    }

    /**
     * @return void
     */
    public function seeResponseHasRefreshToken(): void
    {
        $this->assertNotEmpty($this->getDataFromResponseByJsonPath(self::REFRESH_TOKEN_JSON_PATH));
    }

    /**
     * @return void
     */
    public function dontSeeResponseHasAccessToken(): void
    {
        $this->assertFalse($this->getDataFromResponseByJsonPath(self::ACCESS_TOKEN_JSON_PATH));
    }

    /**
     * @return void
     */
    public function dontSeeResponseHasRefreshToken(): void
    {
        $this->assertFalse($this->getDataFromResponseByJsonPath(self::REFRESH_TOKEN_JSON_PATH));
    }

    /**
     * @param string $expectedIdCompanyUser
     *
     * @return void
     */
    public function seeIdCompanyUserEquals(string $expectedIdCompanyUser): void
    {
        $idCompanyUser = $this->grabIdCompanyUser();
        $this->assertNotNull($idCompanyUser);
        $this->assertEquals($idCompanyUser, $expectedIdCompanyUser);
    }

    /**
     * @return void
     */
    public function seeIdCompanyUserIsNull(): void
    {
        $this->assertNull($this->grabIdCompanyUser());
    }

    /**
     * @return string|null
     */
    private function grabIdCompanyUser(): ?string
    {
        return $this->getDataFromResponseByJsonPath('$.data.attributes.idCompanyUser');
    }
}
