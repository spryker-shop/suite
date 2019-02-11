<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Glue\ShoppingListsRestApi\RestApi;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Generated\Shared\Transfer\StockProductTransfer;
use PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

/**
 * Auto-generated group annotations
 * @group PyzTest
 * @group Glue
 * @group ShoppingListsRestApi
 * @group RestApi
 * @group ShoppingListsApiFixtures
 * Add your own group annotations below this line
 */
class ShoppingListsApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $customer;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer
     */
    protected $foreignCustomer;

    /**
     * @var array
     */
    protected $glueToken;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $firstProduct;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $secondProduct;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $notActiveProduct;

    /**
     * @var \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected $notAvailableProduct;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected $firstShoppingList;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected $secondShoppingList;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected $thirdShoppingList;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListTransfer
     */
    protected $sharedShoppingList;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected $firstItemInSecondShoppingList;

    /**
     * @var \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    protected $secondItemInSecondShoppingList;

    /**
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return \SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface
     */
    public function buildFixtures(ShoppingListsApiTester $I): FixturesContainerInterface
    {
        $this->createUsers($I);
        $this->createProducts($I);
        $this->createShoppingLists($I);
        $this->createSharedShoppingLists($I);

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
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function getForeignCustomer(): CustomerTransfer
    {
        return $this->foreignCustomer;
    }

    /**
     * @return array
     */
    public function getGlueToken(): array
    {
        return $this->glueToken;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getFirstProduct(): ProductConcreteTransfer
    {
        return $this->firstProduct;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getSecondProduct(): ProductConcreteTransfer
    {
        return $this->secondProduct;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getNotActiveProduct(): ProductConcreteTransfer
    {
        return $this->notActiveProduct;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function getNotAvailableProduct(): ProductConcreteTransfer
    {
        return $this->notAvailableProduct;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function getFirstShoppingList(): ShoppingListTransfer
    {
        return $this->firstShoppingList;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function getSecondShoppingList(): ShoppingListTransfer
    {
        return $this->secondShoppingList;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function getThirdShoppingList(): ShoppingListTransfer
    {
        return $this->thirdShoppingList;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function getSharedShoppingList(): ShoppingListTransfer
    {
        return $this->sharedShoppingList;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function getFirstItemInSecondShoppingList(): ShoppingListItemTransfer
    {
        return $this->firstItemInSecondShoppingList;
    }

    /**
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function getSecondItemInSecondShoppingList(): ShoppingListItemTransfer
    {
        return $this->secondItemInSecondShoppingList;
    }

    /**
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    protected function createUsers(ShoppingListsApiTester $I): void
    {
        $this->customer = $I->amCompanyUser();
        $this->glueToken = $I->haveAuthorizationToGlue($this->customer);
        $this->foreignCustomer = $I->amCompanyUserInCompany(
            $this->customer->getCompanyUserTransfer()->getFkCompany()
        );
    }

    /**
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    protected function createProducts(ShoppingListsApiTester $I): void
    {
        $this->firstProduct = $I->haveRandomPublishedProduct();
        $this->secondProduct = $I->haveRandomPublishedProduct();
        $this->notActiveProduct = $I->haveRandomPublishedProduct([
            ProductConcreteTransfer::IS_ACTIVE => false,
        ]);
        $this->notAvailableProduct = $I->haveRandomPublishedProduct();

        $I->haveProductInStock([
            StockProductTransfer::SKU => $this->firstProduct->getSku(),
        ]);
        $I->haveProductInStock([
            StockProductTransfer::SKU => $this->secondProduct->getSku(),
        ]);
        $I->haveProductInStock([
            StockProductTransfer::SKU => $this->notActiveProduct->getSku(),
        ]);
    }

    /**
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    protected function createShoppingLists(ShoppingListsApiTester $I): void
    {
        $this->firstShoppingList = $I->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $this->customer->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $this->customer->getCompanyUserTransfer()->getIdCompanyUser(),
            ShoppingListTransfer::NAME => 'List 1',
        ]);

        $this->secondShoppingList = $I->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $this->customer->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $this->customer->getCompanyUserTransfer()->getIdCompanyUser(),
            ShoppingListTransfer::NAME => 'List 2',
        ]);

        $this->thirdShoppingList = $I->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $this->customer->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $this->customer->getCompanyUserTransfer()->getIdCompanyUser(),
            ShoppingListTransfer::NAME => 'List 3',
        ]);

        $this->firstItemInSecondShoppingList = $I->haveShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->customer->getCompanyUserTransfer()->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $this->secondShoppingList->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 1,
            ShoppingListItemTransfer::SKU => $this->firstProduct->getSku(),
        ]);

        $this->secondItemInSecondShoppingList = $I->haveShoppingListItem([
            ShoppingListItemTransfer::ID_COMPANY_USER => $this->customer->getCompanyUserTransfer()->getIdCompanyUser(),
            ShoppingListItemTransfer::FK_SHOPPING_LIST => $this->secondShoppingList->getIdShoppingList(),
            ShoppingListItemTransfer::QUANTITY => 2,
            ShoppingListItemTransfer::SKU => $this->secondProduct->getSku(),
        ]);
    }

    /**
     * @param \PyzTest\Glue\ShoppingListsRestApi\ShoppingListsApiTester $I
     *
     * @return void
     */
    protected function createSharedShoppingLists(ShoppingListsApiTester $I): void
    {
        $this->sharedShoppingList = $I->haveShoppingList([
            ShoppingListTransfer::CUSTOMER_REFERENCE => $this->foreignCustomer->getCustomerReference(),
            ShoppingListTransfer::ID_COMPANY_USER => $this->foreignCustomer->getCompanyUserTransfer()->getIdCompanyUser(),
            ShoppingListTransfer::NAME => 'Shared list',
        ]);
        $I->haveReadOnlyAccessToSharedShoppingList(
            $this->customer,
            $this->sharedShoppingList
        );
    }
}
