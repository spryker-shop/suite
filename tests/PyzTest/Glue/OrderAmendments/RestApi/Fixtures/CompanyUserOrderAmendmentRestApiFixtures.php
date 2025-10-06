<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Glue\OrderAmendments\RestApi\Fixtures;

use ArrayObject;
use Generated\Shared\DataBuilder\QuoteBuilder;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use PyzTest\Glue\OrderAmendments\OrderAmendmentsApiTester;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class CompanyUserOrderAmendmentRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    protected const TEST_USERNAME_1 = 'OrderAmendmentRestApiFixtures1';

    protected const TEST_USERNAME_2 = 'OrderAmendmentRestApiFixtures2';

    protected const TEST_USERNAME_3 = 'OrderAmendmentRestApiFixtures3';

    protected const TEST_PASSWORD = 'change123';

    protected const STATE_MACHINE_NAME = 'DummyPayment01';

    protected const ORDER_ITEM_STATE_GRACE_PERIOD_STARTED = 'grace period started';

    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    protected const PERMISSION_KEY_EDIT_COMPANY_ORDERS = 'EditCompanyOrdersPermissionPlugin';

    protected CustomerTransfer $customerTransfer;

    protected CustomerTransfer $customerTransferWithEditOrderPermission;

    protected CustomerTransfer $customerTransferWithoutEditOrderPermission;

    protected SaveOrderTransfer $readyForAmendmentOrderTransfer;

    public function getCustomerTransferWithEditOrderPermission(): CustomerTransfer
    {
        return $this->customerTransferWithEditOrderPermission;
    }

    public function getCustomerTransferWithoutEditOrderPermission(): CustomerTransfer
    {
        return $this->customerTransferWithoutEditOrderPermission;
    }

    public function getReadyForAmendmentOrderTransfer(): SaveOrderTransfer
    {
        return $this->readyForAmendmentOrderTransfer;
    }

    public function buildFixtures(OrderAmendmentsApiTester $I): FixturesContainerInterface
    {
        $I->haveCompanyMailConnectorToMailDependency();

        $this->configureStateMachine($I);

        $this->customerTransferWithEditOrderPermission = $this->createCustomerTransfer($I, static::TEST_USERNAME_1);
        $this->customerTransferWithoutEditOrderPermission = $this->createCustomerTransfer($I, static::TEST_USERNAME_2);
        $this->customerTransfer = $this->createCustomerTransfer($I, static::TEST_USERNAME_3);
        $companyTransfer = $I->haveCompany([
            CompanyTransfer::STATUS => 'approved',
            CompanyTransfer::IS_ACTIVE => true,
        ]);
        $this->createCompanyUser(
            $I,
            $this->customerTransferWithEditOrderPermission,
            $companyTransfer,
            static::PERMISSION_KEY_EDIT_COMPANY_ORDERS,
        );
        $this->createCompanyUser(
            $I,
            $this->customerTransferWithoutEditOrderPermission,
            $companyTransfer,
        );
        $companyUserTransfer = $this->createCompanyUser($I, $this->customerTransfer, $companyTransfer);

        $this->readyForAmendmentOrderTransfer = $this->createOrderWithProductConcretes($I, $this->customerTransfer, $companyUserTransfer);
        $this->setOrderItemsState(
            $I,
            $this->readyForAmendmentOrderTransfer->getOrderItems(),
            static::ORDER_ITEM_STATE_GRACE_PERIOD_STARTED,
        );

        return $this;
    }

    protected function createOrderWithProductConcretes(
        OrderAmendmentsApiTester $I,
        CustomerTransfer $customerTransfer,
        CompanyUserTransfer $companyUserTransfer,
    ): SaveOrderTransfer {
        $product1Transfer = $I->haveProductWithPriceAndStock();
        $product2Transfer = $I->haveProductWithPriceAndStock();
        $quoteTransfer = (new QuoteBuilder())
            ->withCustomer([CustomerTransfer::CUSTOMER_REFERENCE => $customerTransfer->getCustomerReference()])
            ->withItem([
                ItemTransfer::SKU => $product1Transfer->getSkuOrFail(),
                ItemTransfer::QUANTITY => 1,
            ])
            ->withItem([
                ItemTransfer::SKU => $product2Transfer->getSkuOrFail(),
                ItemTransfer::QUANTITY => 2,
            ])
            ->withTotals()
            ->withShippingAddress()
            ->withBillingAddress()
            ->withCurrency()
            ->withPayment()
            ->build();

        $quoteTransfer->setPriceMode(static::PRICE_MODE_GROSS);

        $saveOrderTransfer = $I->haveOrderFromQuote($quoteTransfer, static::STATE_MACHINE_NAME);
        $this->updateOrderCompanyUuid($saveOrderTransfer->getOrderReference(), $companyUserTransfer->getCompany()->getUuid());

        return $saveOrderTransfer;
    }

    /**
     * @param \PyzTest\Glue\OrderAmendments\OrderAmendmentsApiTester $I
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param string $stateName
     *
     * @return void
     */
    protected function setOrderItemsState(OrderAmendmentsApiTester $I, ArrayObject $itemTransfers, string $stateName): void
    {
        foreach ($itemTransfers as $itemTransfer) {
            $I->setItemState($itemTransfer->getIdSalesOrderItemOrFail(), $stateName);
        }
    }

    protected function configureStateMachine(OrderAmendmentsApiTester $I): void
    {
        $I->configureTestStateMachine([static::STATE_MACHINE_NAME]);
    }

    protected function createCompanyUser(
        OrderAmendmentsApiTester $I,
        CustomerTransfer $customerTransfer,
        CompanyTransfer $companyTransfer,
        ?string $permissionKey = null,
    ): CompanyUserTransfer {
        $permissionCollectionTransfer = $permissionKey ? (new PermissionCollectionTransfer())->addPermission(
            $I->getLocator()->permission()->facade()->findPermissionByKey($permissionKey),
        ) : new PermissionCollectionTransfer();

        $companyRoleTransfer = $I->haveCompanyRole([
            CompanyRoleTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyRoleTransfer::PERMISSION_COLLECTION => $permissionCollectionTransfer,
        ]);

        $companyUserTransfer = $I->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => $customerTransfer,
            CompanyUserTransfer::FK_COMPANY => $companyTransfer->getIdCompany(),
            CompanyUserTransfer::COMPANY_ROLE_COLLECTION => (new CompanyRoleCollectionTransfer())->addRole($companyRoleTransfer),
        ]);

        $I->assignCompanyRolesToCompanyUser($companyUserTransfer);

        return $companyUserTransfer
            ->setCompany($companyTransfer);
    }

    protected function createCustomerTransfer(OrderAmendmentsApiTester $I, string $userName): CustomerTransfer
    {
        $customerTransfer = $I->haveCustomer([
            CustomerTransfer::USERNAME => $userName,
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);

        return $I->confirmCustomer($customerTransfer);
    }

    protected function updateOrderCompanyUuid(string $orderReference, string $companyUuid): void
    {
        $salesOrderEntity = $this->getSalesOrderQuery()->filterByOrderReference($orderReference)->findOne();
        $salesOrderEntity->setCompanyUuid($companyUuid);
        $salesOrderEntity->save();
    }

    protected function getSalesOrderQuery(): SpySalesOrderQuery
    {
        return SpySalesOrderQuery::create();
    }
}
