<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Glue\Checkout\RestApi\Fixtures;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\PermissionCollectionTransfer;
use Generated\Shared\Transfer\ShipmentMethodTransfer;
use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use PyzTest\Glue\Checkout\CheckoutApiTester;
use SprykerTest\Shared\Shipment\Helper\ShipmentMethodDataHelper;
use SprykerTest\Shared\Testify\Fixtures\FixturesBuilderInterface;
use SprykerTest\Shared\Testify\Fixtures\FixturesContainerInterface;

class CompanyUserOrderAmendmentCheckoutRestApiFixtures implements FixturesBuilderInterface, FixturesContainerInterface
{
    protected const TEST_USERNAME_1 = 'OrderAmendmentRestApiFixtures1';

    protected const TEST_USERNAME_2 = 'OrderAmendmentRestApiFixtures2';

    protected const TEST_PASSWORD = 'change123';

    protected const STATE_MACHINE_NAME = 'DummyPayment01';

    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @uses \Spryker\Zed\CompanySalesConnector\Communication\Plugin\Permission\EditCompanyOrdersPermissionPlugin::KEY
     */
    protected const PERMISSION_KEY_EDIT_COMPANY_ORDERS = 'EditCompanyOrdersPermissionPlugin';

    protected CustomerTransfer $customerTransfer;

    protected CustomerTransfer $customerTransferWithEditOrderPermission;

    protected AddressTransfer $customerAddress;

    /**
     * @var list<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    protected array $productConcreteTransfers;

    protected ShipmentMethodTransfer $shipmentMethodTransfer;

    public function getCustomerAddress(): AddressTransfer
    {
        return $this->customerAddress;
    }

    /**
     * @return list<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function getProductConcreteTransfers(): array
    {
        return $this->productConcreteTransfers;
    }

    public function getShipmentMethodTransfer(): ShipmentMethodTransfer
    {
        return $this->shipmentMethodTransfer;
    }

    public function getCustomerTransferWithEditOrderPermission(): CustomerTransfer
    {
        return $this->customerTransferWithEditOrderPermission;
    }

    public function getCustomerTransfer(): CustomerTransfer
    {
        return $this->customerTransfer;
    }

    public function buildFixtures(CheckoutApiTester $I): FixturesContainerInterface
    {
        $I->truncateSalesOrderThresholds();
        $I->ensureSalesOrderAmendmentTableIsEmpty();

        $I->haveCompanyMailConnectorToMailDependency();

        $this->customerTransferWithEditOrderPermission = $this->createCustomerTransfer($I, static::TEST_USERNAME_1);
        $this->customerTransfer = $this->createCustomerTransfer($I, static::TEST_USERNAME_2);
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
        $this->createCompanyUser($I, $this->customerTransfer, $companyTransfer);

        $this->productConcreteTransfers = [
            $I->haveProductWithStock(),
            $I->haveProductWithStock(),
        ];

        $shipmentTypeTransfer = $I->haveShipmentType([
            ShipmentTypeTransfer::IS_ACTIVE => true,
            ShipmentTypeTransfer::STORE_RELATION => (new StoreRelationTransfer())->addStores($I->getStoreFacade()->getCurrentStore()),
        ]);
        $this->shipmentMethodTransfer = $I->haveShipmentMethod(
            [
                ShipmentMethodTransfer::CARRIER_NAME => 'Spryker Dummy Shipment',
                ShipmentMethodTransfer::NAME => 'Standard',
            ],
            [],
            ShipmentMethodDataHelper::DEFAULT_PRICE_LIST,
            [
                $I->getStoreFacade()->getCurrentStore()->getIdStore(),
            ],
        );
        $I->addShipmentTypeToShipmentMethod($this->shipmentMethodTransfer, $shipmentTypeTransfer);

        $this->customerAddress = $I->haveCustomerAddress([
            AddressTransfer::FK_CUSTOMER => $this->customerTransfer->getIdCustomer(),
            AddressTransfer::FK_COUNTRY => $I->haveCountry()->getIdCountry(),
        ]);

        return $this;
    }

    protected function createCompanyUser(
        CheckoutApiTester $I,
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

    protected function createCustomerTransfer(CheckoutApiTester $I, string $userName): CustomerTransfer
    {
        $customerTransfer = $I->haveCustomer([
            CustomerTransfer::USERNAME => $userName,
            CustomerTransfer::PASSWORD => static::TEST_PASSWORD,
            CustomerTransfer::NEW_PASSWORD => static::TEST_PASSWORD,
        ]);

        return $I->confirmCustomer($customerTransfer);
    }
}
