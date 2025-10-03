<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Yves\SelfServicePortal\Functional;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CompanyRoleCollectionBuilder;
use Generated\Shared\DataBuilder\PermissionCollectionBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SspAssetSearchCollectionTransfer;
use Generated\Shared\Transfer\SspAssetSearchCriteriaTransfer;
use Generated\Shared\Transfer\SspAssetTransfer;
use Generated\Shared\Transfer\SspModelTransfer;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetToCompanyBusinessUnit;
use Pyz\Client\SelfServicePortal\SelfServicePortalDependencyProvider;
use Pyz\Shared\SelfServicePortal\SelfServicePortalConfig;
use PyzTest\Yves\SelfServicePortal\SelfServicePortalFunctionalTester;
use Spryker\Client\CompanyRole\CompanyRoleDependencyProvider;
use Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToCustomerClientInterface;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Client\CustomerAccessPermission\CustomerAccessPermissionDependencyProvider;
use Spryker\Client\CustomerAccessPermission\Dependency\Client\CustomerAccessPermissionToCustomerClientInterface;
use Spryker\Client\Permission\PermissionClientInterface;
use Spryker\Zed\CompanyMailConnector\CompanyMailConnectorDependencyProvider;
use Spryker\Zed\CompanyMailConnector\Dependency\Facade\CompanyMailConnectorToMailFacadeInterface;
use Spryker\Zed\CompanyRole\CompanyRoleDependencyProvider as ZedCompanyRoleDependencyProvider;
use Spryker\Zed\CompanyRole\Dependency\Facade\CompanyRoleToPermissionFacadeInterface;
use Spryker\Zed\Queue\Communication\Console\QueueWorkerConsole;
use SprykerFeature\Client\SelfServicePortal\Plugin\Elasticsearch\Query\SspAssetSearchQueryExpanderPlugin;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\Publisher\SspAssetPublisherTriggerPlugin;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Yves
 * @group SelfServicePortal
 * @group Functional
 * @group AssetSearchFilteringTest
 * Add your own group annotations below this line
 */
class AssetSearchFilteringTest extends Unit
{
    use LocatorHelperTrait;

    /**
     * @var \PyzTest\Yves\SelfServicePortal\SelfServicePortalFunctionalTester
     */
    protected SelfServicePortalFunctionalTester $tester;

    /**
     * @var \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClientInterface
     */
    protected SelfServicePortalClientInterface $selfServicePortalClient;

    /**
     * @var \Spryker\Client\CompanyUser\CompanyUserClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $companyUserClientMock;

    /**
     * @var \Spryker\Client\CompanyRole\Dependency\Client\CompanyRoleToCustomerClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $companyRoleCustomerClientMock;

    /**
     * @var \Spryker\Client\CustomerAccessPermission\Dependency\Client\CustomerAccessPermissionToCustomerClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $customerAccessPermissionCustomerClientMock;

    /**
     * @var \Spryker\Client\Permission\PermissionClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $permissionClientMock;

    /**
     * @var \Spryker\Zed\CompanyRole\Dependency\Facade\CompanyRoleToPermissionFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected CompanyRoleToPermissionFacadeInterface $permissionFacadeMock;

    protected static ?CompanyTransfer $companyA = null;

    protected static ?CompanyTransfer $companyX = null;

    protected static ?CompanyBusinessUnitTransfer $businessUnitA = null;

    protected static ?CompanyBusinessUnitTransfer $businessUnitB = null;

    protected static ?CompanyBusinessUnitTransfer $businessUnitX = null;

    protected static ?CustomerTransfer $customerA = null;

    protected static ?CustomerTransfer $customerB = null;

    protected static ?CustomerTransfer $customerC = null;

    protected static ?CompanyUserTransfer $companyUserA = null;

    protected static ?CompanyUserTransfer $companyUserB = null;

    protected static ?CompanyUserTransfer $companyUserC = null;

    protected static ?SspModelTransfer $model1 = null;

    protected static ?SspModelTransfer $model2 = null;

    protected static ?SspAssetTransfer $assetA1 = null;

    protected static ?SspAssetTransfer $assetA2 = null;

    protected static ?SspAssetTransfer $assetB1 = null;

    protected static ?SspAssetTransfer $assetX1 = null;

    protected static ?SelfServicePortalFunctionalTester $staticTester = null;

    protected function setUp(): void
    {
        parent::setUp();

        if (self::$staticTester === null) {
            self::$staticTester = $this->tester;
            $this->createTestDataStatic();
        }

        $this->companyUserClientMock = $this->createMock(CompanyUserClientInterface::class);
        $this->companyRoleCustomerClientMock = $this->createMock(CompanyRoleToCustomerClientInterface::class);
        $this->customerAccessPermissionCustomerClientMock = $this->createMock(CustomerAccessPermissionToCustomerClientInterface::class);
        $this->permissionClientMock = $this->createMock(PermissionClientInterface::class);

        $this->companyRoleCustomerClientMock->method('getCustomer')->willReturn(null);
        $this->customerAccessPermissionCustomerClientMock->method('isLoggedIn')->willReturn(false);

        $this->permissionClientMock = $this->createMock(PermissionClientInterface::class);

        $emptyPermissionCollection = (new PermissionCollectionBuilder())->build();
        $this->permissionFacadeMock = $this->createMock(CompanyRoleToPermissionFacadeInterface::class);
        $this->permissionFacadeMock->method('findMergedRegisteredNonInfrastructuralPermissions')->willReturn($emptyPermissionCollection);

        $this->tester->setDependency(SelfServicePortalDependencyProvider::CLIENT_COMPANY_USER, $this->companyUserClientMock);
        $this->tester->setDependency(SelfServicePortalDependencyProvider::CLIENT_PERMISSION, $this->permissionClientMock);
        $this->tester->setDependency(CompanyRoleDependencyProvider::CLIENT_CUSTOMER, $this->companyRoleCustomerClientMock);
        $this->tester->setDependency(CustomerAccessPermissionDependencyProvider::CLIENT_CUSTOMER, $this->customerAccessPermissionCustomerClientMock);
        $this->tester->setDependency(ZedCompanyRoleDependencyProvider::FACADE_PERMISSION, $this->permissionFacadeMock);

        $this->tester->setDependency(CompanyMailConnectorDependencyProvider::FACADE_MAIL, $this->createMock(CompanyMailConnectorToMailFacadeInterface::class));

        $this->tester->setDependency(SelfServicePortalDependencyProvider::PLUGINS_SSP_ASSET_SEARCH_QUERY_EXPANDER, [
            new SspAssetSearchQueryExpanderPlugin(),
        ]);

        $this->selfServicePortalClient = $this->tester->getLocator()->selfServicePortal()->client();
    }

    public function testGetSspAssetSearchCollectionReturnsAssetsWhenUserHasBusinessUnitPermissions(): void
    {
        // Arrange
        $this->setCurrentUser(self::$companyUserA);
        $searchCriteria = (new SspAssetSearchCriteriaTransfer())->setSearchString('Truck');

        // Act
        $searchResult = $this->selfServicePortalClient->getSspAssetSearchCollection($searchCriteria);

        // Assert
        $this->assertSearchResults($searchResult, ['Truck A1', 'Truck A2']);
    }

    public function testGetSspAssetSearchCollectionReturnsAssetsWhenSearchingByModelName(): void
    {
        // Arrange
        $this->setCurrentUser(self::$companyUserA);
        $searchCriteria = (new SspAssetSearchCriteriaTransfer())
            ->setSearchString('TRUCK_MODEL_ALPHA');

        // Act
        $searchResult = $this->selfServicePortalClient->getSspAssetSearchCollection($searchCriteria);

        // Assert
        $this->assertSearchResults($searchResult, ['Truck A1']);
    }

    public function testGetSspAssetSearchCollectionReturnsAssetsWhenSearchingBySerialNumber(): void
    {
        // Arrange
        $this->setCurrentUser(self::$companyUserA);
        $searchCriteria = (new SspAssetSearchCriteriaTransfer())
            ->setSearchString('12345');

        // Act
        $searchResult = $this->selfServicePortalClient->getSspAssetSearchCollection($searchCriteria);

        // Assert
        $this->assertSearchResults($searchResult, ['Truck A1']);
    }

    public function testGetSspAssetSearchCollectionReturnsEmptyWhenUserHasNoPermissions(): void
    {
        // Arrange
        $this->setCurrentUser(self::$companyUserB);
        $searchCriteria = (new SspAssetSearchCriteriaTransfer())
            ->setSearchString('Truck');

        // Act
        $searchResult = $this->selfServicePortalClient->getSspAssetSearchCollection($searchCriteria);

        // Assert
        $this->assertSearchResults($searchResult, []);
    }

    public function testGetSspAssetSearchCollectionReturnsAllCompanyAssetsWhenUserHasCompanyPermissions(): void
    {
        // Arrange
        $this->setCurrentUser(self::$companyUserC);
        $searchCriteria = (new SspAssetSearchCriteriaTransfer())
            ->setSearchString('Truck');

        // Act
        $searchResult = $this->selfServicePortalClient->getSspAssetSearchCollection($searchCriteria);

        // Assert
        $this->assertSearchResults($searchResult, ['Truck A1', 'Truck A2', 'Truck B1']);
    }

    protected function createTestDataStatic(): void
    {
        if (self::$companyA !== null) {
            return;
        }

        self::$companyA = self::$staticTester->haveCompany([
            CompanyTransfer::NAME => 'CompanyA',
            CompanyTransfer::STATUS => 'approved',
            CompanyTransfer::IS_ACTIVE => true,
        ]);

        self::$companyX = self::$staticTester->haveCompany([
            CompanyTransfer::NAME => 'CompanyX',
            CompanyTransfer::STATUS => 'approved',
            CompanyTransfer::IS_ACTIVE => true,
        ]);

        self::$businessUnitA = self::$staticTester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::NAME => 'BU-A',
            CompanyBusinessUnitTransfer::FK_COMPANY => self::$companyA->getIdCompany(),
        ]);

        self::$businessUnitB = self::$staticTester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::NAME => 'BU-B',
            CompanyBusinessUnitTransfer::FK_COMPANY => self::$companyA->getIdCompany(),
        ]);

        self::$businessUnitX = self::$staticTester->haveCompanyBusinessUnit([
            CompanyBusinessUnitTransfer::NAME => 'BU-X',
            CompanyBusinessUnitTransfer::FK_COMPANY => self::$companyX->getIdCompany(),
        ]);

        self::$customerA = self::$staticTester->haveCustomer([
            CustomerTransfer::EMAIL => 'customer_A@example.com',
            CustomerTransfer::FIRST_NAME => 'Customer',
            CustomerTransfer::LAST_NAME => 'A',
        ]);

        self::$customerB = self::$staticTester->haveCustomer([
            CustomerTransfer::EMAIL => 'customer_B@example.com',
            CustomerTransfer::FIRST_NAME => 'Customer',
            CustomerTransfer::LAST_NAME => 'B',
        ]);

        self::$customerC = self::$staticTester->haveCustomer([
            CustomerTransfer::EMAIL => 'customer_C@example.com',
            CustomerTransfer::FIRST_NAME => 'Customer',
            CustomerTransfer::LAST_NAME => 'C',
        ]);

        static::createCompanyUsersStatic();

        self::$model1 = self::$staticTester->haveSspModel([
            SspModelTransfer::NAME => 'TRUCK_MODEL_ALPHA',
            SspModelTransfer::REFERENCE => 'MODEL1_REF',
        ]);

        self::$model2 = self::$staticTester->haveSspModel([
            SspModelTransfer::NAME => 'TRUCK_MODEL_BETA',
            SspModelTransfer::REFERENCE => 'MODEL2_REF',
        ]);

        static::createAssetsStatic();
        static::addPermissionsToCompanyUsersStatic();
    }

    protected static function createAssetsStatic(): void
    {
        self::$assetA1 = self::$staticTester->haveAsset([
            SspAssetTransfer::NAME => 'Truck A1',
            SspAssetTransfer::REFERENCE => 'TRUCK_A1_REF',
            SspAssetTransfer::SERIAL_NUMBER => '12345',
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => self::$businessUnitA,
            SspAssetTransfer::SSP_MODELS => [self::$model1->modifiedToArray(false, true)],
        ]);

        self::$assetA2 = self::$staticTester->haveAsset([
            SspAssetTransfer::NAME => 'Truck A2',
            SspAssetTransfer::REFERENCE => 'TRUCK_A2_REF',
            SspAssetTransfer::SERIAL_NUMBER => '67890',
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => self::$businessUnitA,
            SspAssetTransfer::SSP_MODELS => [self::$model2->modifiedToArray(false, true)],
        ]);

        self::$assetB1 = self::$staticTester->haveAsset([
            SspAssetTransfer::NAME => 'Truck B1',
            SspAssetTransfer::REFERENCE => 'TRUCK_B1_REF',
            SspAssetTransfer::SERIAL_NUMBER => 'TRUCK_B1_SERIAL',
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => self::$businessUnitB,
            SspAssetTransfer::SSP_MODELS => [self::$model1->modifiedToArray(false, true)],
        ]);

        self::$assetX1 = self::$staticTester->haveAsset([
            SspAssetTransfer::NAME => 'Truck X1',
            SspAssetTransfer::REFERENCE => 'TRUCK_X1_REF',
            SspAssetTransfer::SERIAL_NUMBER => 'TRUCK_X1_SERIAL',
            SspAssetTransfer::COMPANY_BUSINESS_UNIT => self::$businessUnitX,
        ]);

        static::createAssetBusinessUnitAssignmentsStatic();

        static::publishAssetsToSearchStatic([self::$assetA1, self::$assetA2, self::$assetB1, self::$assetX1]);
    }

    protected static function createCompanyUsersStatic(): void
    {
        self::$companyUserA = self::$staticTester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => self::$customerA,
            CompanyUserTransfer::FK_COMPANY => self::$companyA->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => self::$businessUnitA->getIdCompanyBusinessUnit(),
        ]);

        self::$companyUserB = self::$staticTester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => self::$customerB,
            CompanyUserTransfer::FK_COMPANY => self::$companyA->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => self::$businessUnitB->getIdCompanyBusinessUnit(),
        ]);

        self::$companyUserC = self::$staticTester->haveCompanyUser([
            CompanyUserTransfer::CUSTOMER => self::$customerC,
            CompanyUserTransfer::FK_COMPANY => self::$companyA->getIdCompany(),
            CompanyUserTransfer::FK_COMPANY_BUSINESS_UNIT => self::$businessUnitB->getIdCompanyBusinessUnit(),
        ]);
    }

    protected static function addPermissionsToCompanyUsersStatic(): void
    {
        $businessUnitRole = self::$staticTester->haveCompanyRole([
            CompanyRoleTransfer::NAME => 'Business Unit Role',
            CompanyRoleTransfer::FK_COMPANY => self::$companyA->getIdCompany(),
        ]);

        $companyRole = self::$staticTester->haveCompanyRole([
            CompanyRoleTransfer::NAME => 'Company Role',
            CompanyRoleTransfer::FK_COMPANY => self::$companyA->getIdCompany(),
        ]);

        $businessUnitRoleCollection = (new CompanyRoleCollectionBuilder())
            ->build()
            ->addRole($businessUnitRole);

        $companyRoleCollection = (new CompanyRoleCollectionBuilder())
            ->build()
            ->addRole($companyRole);

        self::$companyUserA->setCompanyRoleCollection($businessUnitRoleCollection);
        self::$companyUserC->setCompanyRoleCollection($companyRoleCollection);

        self::$staticTester->assignCompanyRolesToCompanyUser(self::$companyUserA);
        self::$staticTester->assignCompanyRolesToCompanyUser(self::$companyUserC);
    }

    protected static function createAssetBusinessUnitAssignmentsStatic(): void
    {
        $assignments = [
            [self::$assetA1->getIdSspAsset(), self::$businessUnitA->getIdCompanyBusinessUnit()],
            [self::$assetA2->getIdSspAsset(), self::$businessUnitA->getIdCompanyBusinessUnit()],
            [self::$assetB1->getIdSspAsset(), self::$businessUnitB->getIdCompanyBusinessUnit()],
            [self::$assetX1->getIdSspAsset(), self::$businessUnitX->getIdCompanyBusinessUnit()],
        ];

        foreach ($assignments as [$assetId, $businessUnitId]) {
            $assignment = new SpySspAssetToCompanyBusinessUnit();
            $assignment->setFkSspAsset($assetId);
            $assignment->setFkCompanyBusinessUnit($businessUnitId);
            $assignment->save();
        }
    }

    protected static function publishAssetsToSearchStatic(array $assets): void
    {
        $assetIds = array_map(function ($asset) {
            return $asset->getIdSspAsset();
        }, $assets);

        self::$staticTester->getLocator()->eventBehavior()->facade()->executeResolvedPluginsBySources(
            [SelfServicePortalConfig::SSP_ASSET_RESOURCE_NAME],
            $assetIds,
            [new SspAssetPublisherTriggerPlugin()],
        );

        $queueFacade = self::$staticTester->getLocator()->queue()->facade();

        $queueFacade->startWorker(
            QueueWorkerConsole::QUEUE_RUNNER_COMMAND,
            new NullOutput(),
            [QueueWorkerConsole::OPTION_STOP_WHEN_EMPTY => true],
        );
    }

    protected function setCurrentUser(CompanyUserTransfer $companyUserTransfer): void
    {
        $this->populateCompanyUserBusinessUnit($companyUserTransfer);
        $this->populateCompanyUserCompany($companyUserTransfer);
        $this->setupPermissionsForUser($companyUserTransfer);

        $this->companyUserClientMock
            ->method('findCompanyUser')
            ->willReturn($companyUserTransfer);
    }

    protected function setupPermissionsForUser(CompanyUserTransfer $companyUserTransfer): void
    {
        $userLastName = $companyUserTransfer->getCustomer()->getLastName();
        $permissionCallback = $this->createPermissionCallback($userLastName);
        $this->injectPermissionClient($permissionCallback);
    }

    protected function createPermissionCallback(string $userLastName): callable
    {
        return function ($permissionKey) use ($userLastName) {
            if ($permissionKey === 'ViewCompanySspAssetPermissionPlugin') {
                return $userLastName === 'C';
            }
            if ($permissionKey === 'ViewBusinessUnitSspAssetPermissionPlugin') {
                return $userLastName === 'A' || $userLastName === 'C';
            }

            return true;
        };
    }

    protected function injectPermissionClient(callable $permissionCallback): void
    {
        $this->permissionClientMock = $this->createMock(PermissionClientInterface::class);
        $this->permissionClientMock->method('can')->willReturnCallback($permissionCallback);

        $this->tester->setDependency(SelfServicePortalDependencyProvider::CLIENT_PERMISSION, $this->permissionClientMock);

        $this->selfServicePortalClient = $this->tester->getLocator()->selfServicePortal()->client();
    }

    protected function populateCompanyUserBusinessUnit(CompanyUserTransfer $companyUserTransfer): void
    {
        $businessUnitId = $companyUserTransfer->getFkCompanyBusinessUnit();
        if (!$businessUnitId) {
            return;
        }

        $businessUnitMap = [
            self::$businessUnitA->getIdCompanyBusinessUnit() => self::$businessUnitA,
            self::$businessUnitB->getIdCompanyBusinessUnit() => self::$businessUnitB,
            self::$businessUnitX->getIdCompanyBusinessUnit() => self::$businessUnitX,
        ];

        if (!isset($businessUnitMap[$businessUnitId])) {
            return;
        }

        $companyUserTransfer->setCompanyBusinessUnit($businessUnitMap[$businessUnitId]);
    }

    protected function populateCompanyUserCompany(CompanyUserTransfer $companyUserTransfer): void
    {
        $companyId = $companyUserTransfer->getFkCompany();
        if (!$companyId) {
            return;
        }

        $companyMap = [
            self::$companyA->getIdCompany() => self::$companyA,
            self::$companyX->getIdCompany() => self::$companyX,
        ];

        if (!isset($companyMap[$companyId])) {
            return;
        }

        $companyUserTransfer->setCompany($companyMap[$companyId]);
    }

    protected function assertSearchResults(SspAssetSearchCollectionTransfer $searchResult, array $expectedAssetNames): void
    {
        $this->assertNotNull($searchResult, 'Search result should not be null');

        $actualAssetNames = [];
        foreach ($searchResult->getSspAssets() as $asset) {
            $actualAssetNames[] = $asset->getName();
        }

        sort($expectedAssetNames);
        sort($actualAssetNames);

        $this->assertEquals(
            $expectedAssetNames,
            $actualAssetNames,
            sprintf(
                'Expected assets: [%s], but got: [%s]',
                implode(', ', $expectedAssetNames),
                implode(', ', $actualAssetNames),
            ),
        );

        $this->assertEquals(
            count($expectedAssetNames),
            $searchResult->getNbResults(),
            'Result count should match expected count',
        );
    }
}
