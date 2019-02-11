<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\Carts\RestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use PyzTest\Glue\Carts\CartsApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group Carts
 * @group RestApi
 * @group CartsRestApiFixtures
 * Add your own group annotations below this line
 */
class CartsRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customer;

    /**
     * @var array
     */
    protected $glueToken;

    /**
     * @var string
     */
    protected $cartId;

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(CartsApiTester $I): FixturesContainerInterface
    {
        $this->createUsers($I);
        $this->createCart($I);

        return $this;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getCustomer(): CustomerTransfer
    {
        return $this->customer;
    }

    /**
     * @return array
     */
    public function getGlueToken(): array
    {
        return $this->glueToken;
    }

    /**
     * @return string
     */
    public function getCartId(): string
    {
        return $this->cartId;
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function createUsers(CartsApiTester $I): void
    {
        $this->customer = $I->amUser();
        $this->glueToken = $I->haveAuthorizationToGlue($this->customer);
    }

    /**
     * @param \PyzTest\Glue\Carts\CartsApiTester $I
     *
     * @return void
     */
    protected function createCart(CartsApiTester $I): void
    {
        $I->amAuthorizedGlueUser(
            $this->getGlueToken(),
            $this->getCustomer()
        );
        $this->cartId = $I->haveCart();
    }
}
