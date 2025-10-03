<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace PyzTest\Client\SelfServicePortal;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\ProductConcreteProductListStorageTransfer;
use PHPUnit\Framework\TestCase;
use Spryker\Client\CompanyUser\CompanyUserClientInterface;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\ProductListStorage\ProductListStorageClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Service\Synchronization\Dependency\Plugin\SynchronizationKeyGeneratorPluginInterface;
use Spryker\Service\Synchronization\SynchronizationServiceInterface;
use Spryker\Service\UtilEncoding\UtilEncodingServiceInterface;
use SprykerFeature\Client\SelfServicePortal\Asset\Compatibility\AssetProductCompatibilityChecker;
use SprykerFeature\Client\SelfServicePortal\Permission\SspAssetPermissionCheckerInterface;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalClient;
use SprykerFeature\Client\SelfServicePortal\SelfServicePortalFactory;
use SprykerFeature\Client\SelfServicePortal\Storage\Mapper\SspAssetStorageMapperInterface;
use SprykerFeature\Client\SelfServicePortal\Storage\Mapper\SspModelStorageMapperInterface;
use SprykerFeature\Client\SelfServicePortal\Storage\Reader\SspAssetStorageReader;
use SprykerFeature\Client\SelfServicePortal\Storage\Reader\SspModelStorageReader;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Client
 * @group SelfServicePortal
 * @group AssetProductCompatibilityMatrixTest
 * Add your own group annotations below this line
 */
class AssetProductCompatibilityMatrixTest extends TestCase
{
    protected const DEPENDENCY_PRODUCT_LIST_STORAGE_CLIENT = 'productListStorageClient';

    protected const DEPENDENCY_PRODUCT_STORAGE_CLIENT = 'productStorageClient';

    protected const DEPENDENCY_ASSET_PERMISSION_CHECKER = 'assetPermissionChecker';

    protected const DEPENDENCY_MODEL_STORAGE_MAPPER = 'modelStorageMapper';

    protected const METHOD_NAME_CREATE_SSP_ASSET_STORAGE_READER = 'createSspAssetStorageReader';

    protected const METHOD_NAME_CREATE_SSP_MODEL_STORAGE_READER = 'createSspModelStorageReader';

    protected const METHOD_NAME_GET_PRODUCT_LIST_STORAGE_CLIENT = 'getProductListStorageClient';

    protected const METHOD_NAME_GET_COMPANY_USER_CLIENT = 'getCompanyUserClient';

    protected const METHOD_NAME_GET_PRODUCT_STORAGE_CLIENT = 'getProductStorageClient';

    protected const METHOD_NAME_GET_LOCALE_CLIENT = 'getLocaleClient';

    protected const METHOD_NAME_CREATE_ASSET_PRODUCT_COMPATIBILITY_CHECKER = 'createAssetProductCompatibilityChecker';

    protected const METHOD_NAME_FIND_PRODUCT_CONCRETE_PRODUCT_LIST_STORAGE = 'findProductConcreteProductListStorage';

    protected const METHOD_NAME_FIND_PRODUCT_CONCRETE_STORAGE_DATA = 'findProductConcreteStorageData';

    protected const METHOD_NAME_FIND_COMPANY_USER = 'findCompanyUser';

    protected const METHOD_NAME_GET_CURRENT_LOCALE = 'getCurrentLocale';

    protected AssetProductCompatibilityMatrixTester $tester;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tester = new AssetProductCompatibilityMatrixTester();
    }

    public function testGetAssetProductCompatibilityMatrixReturnsCorrectCompatibilityBasedOnModelRelation(): void
    {
        // Arrange
        $assetReferences = [AssetProductCompatibilityMatrixTester::ASSET_A1_REFERENCE, AssetProductCompatibilityMatrixTester::ASSET_A2_REFERENCE];
        $skus = [AssetProductCompatibilityMatrixTester::PRODUCT_P1_SKU];

        $selfServicePortalClient = $this->createSelfServicePortalClient([
            self::DEPENDENCY_PRODUCT_LIST_STORAGE_CLIENT => function ($productListStorageClient): void {
                $productConcreteProductListStorage = new ProductConcreteProductListStorageTransfer();
                $productConcreteProductListStorage->setIdWhitelists([AssetProductCompatibilityMatrixTester::PRODUCT_LIST_ID]);
                $productListStorageClient->method(self::METHOD_NAME_FIND_PRODUCT_CONCRETE_PRODUCT_LIST_STORAGE)
                    ->willReturn($productConcreteProductListStorage);
            },
            self::DEPENDENCY_PRODUCT_STORAGE_CLIENT => function ($productStorageClient): void {
                $productStorageClient->method(self::METHOD_NAME_FIND_PRODUCT_CONCRETE_STORAGE_DATA)
                    ->willReturn(['id_product_concrete' => AssetProductCompatibilityMatrixTester::PRODUCT_P1_ID]);
            },
        ]);

        // Act
        $actualCompatibilityMatrix = $selfServicePortalClient->getAssetProductCompatibilityMatrix(
            $assetReferences,
            $skus,
        );

        // Assert
        $this->assertFalse(
            $actualCompatibilityMatrix[AssetProductCompatibilityMatrixTester::ASSET_A1_REFERENCE][AssetProductCompatibilityMatrixTester::PRODUCT_P1_SKU],
            'Asset A1 should not be compatible with Product P1 in this test scenario',
        );
        $this->assertFalse(
            $actualCompatibilityMatrix[AssetProductCompatibilityMatrixTester::ASSET_A2_REFERENCE][AssetProductCompatibilityMatrixTester::PRODUCT_P1_SKU],
            'Asset A2 should not be compatible with Product P1 due to different model',
        );
    }

    public function testGetAssetProductCompatibilityMatrixReturnsFalseCompatabilityForAssetWithoutModels(): void
    {
        // Arrange
        $assetReferences = [AssetProductCompatibilityMatrixTester::ASSET_A1_REFERENCE];
        $skus = [AssetProductCompatibilityMatrixTester::PRODUCT_P1_SKU];

        $selfServicePortalClient = $this->createSelfServicePortalClient([
            self::DEPENDENCY_MODEL_STORAGE_MAPPER => function () {
                return $this->createMock(SspModelStorageMapperInterface::class);
            },
        ]);

        // Act
        $actualCompatibilityMatrix = $selfServicePortalClient->getAssetProductCompatibilityMatrix(
            $assetReferences,
            $skus,
        );

        // Assert
        $this->assertFalse(
            $actualCompatibilityMatrix[AssetProductCompatibilityMatrixTester::ASSET_A1_REFERENCE][AssetProductCompatibilityMatrixTester::PRODUCT_P1_SKU],
            'Asset A1 should not be compatible when it has no models',
        );
    }

    public function testGetAssetProductCompatibilityMatrixReturnsFalseCompatabilityWhenProductNotInProductLists(): void
    {
        // Arrange
        $assetReferences = [AssetProductCompatibilityMatrixTester::ASSET_A1_REFERENCE];
        $skus = [AssetProductCompatibilityMatrixTester::PRODUCT_P1_SKU];

        $selfServicePortalClient = $this->createSelfServicePortalClient();

        // Act
        $actualCompatibilityMatrix = $selfServicePortalClient->getAssetProductCompatibilityMatrix(
            $assetReferences,
            $skus,
        );

        // Assert
        $this->assertFalse(
            $actualCompatibilityMatrix[AssetProductCompatibilityMatrixTester::ASSET_A1_REFERENCE][AssetProductCompatibilityMatrixTester::PRODUCT_P1_SKU],
            'Asset A1 should not be compatible when product is not in model product lists',
        );
    }

    public function testGetAssetProductCompatibilityMatrixReturnsEmptyForEmptyInputs(): void
    {
        // Arrange
        $assetReferences = [];
        $skus = [];

        $selfServicePortalClient = $this->createSelfServicePortalClient([
            self::DEPENDENCY_PRODUCT_LIST_STORAGE_CLIENT => function ($productListStorageClient): void {
                $this->tester->setupProductListStorageClientMock($productListStorageClient);
            },
        ]);

        // Act
        $actualCompatibilityMatrix = $selfServicePortalClient->getAssetProductCompatibilityMatrix(
            $assetReferences,
            $skus,
        );

        // Assert
        $this->assertEmpty($actualCompatibilityMatrix, 'Empty inputs should return empty compatibility matrix');
    }

    public function testGetAssetProductCompatibilityMatrixRespectsAccessControlAndFiltersInaccessibleAssets(): void
    {
        // Arrange
        $assetReferences = ['A1', 'B1'];
        $skus = [AssetProductCompatibilityMatrixTester::PRODUCT_P1_SKU];

        $selfServicePortalClient = $this->createSelfServicePortalClient([
            self::DEPENDENCY_ASSET_PERMISSION_CHECKER => function ($sspAssetPermissionChecker): void {
                $this->tester->setupAssetPermissionCheckerMock($sspAssetPermissionChecker, false);
            },
        ]);

        // Act
        $actualCompatibilityMatrix = $selfServicePortalClient
            ->getAssetProductCompatibilityMatrix($assetReferences, $skus);

        // Assert
        $this->assertArrayHasKey(AssetProductCompatibilityMatrixTester::ASSET_A1_REFERENCE, $actualCompatibilityMatrix);
        $this->assertArrayHasKey('B1', $actualCompatibilityMatrix);
        $this->assertFalse(
            $actualCompatibilityMatrix[AssetProductCompatibilityMatrixTester::ASSET_A1_REFERENCE][AssetProductCompatibilityMatrixTester::PRODUCT_P1_SKU],
            'Asset A1 (Model M1) should not be compatible with Product P1 (Model M2)',
        );
        $this->assertFalse(
            $actualCompatibilityMatrix['B1'][AssetProductCompatibilityMatrixTester::PRODUCT_P1_SKU],
            'Asset B1 should not be compatible due to access control restrictions',
        );
    }

    /**
     * @param array $customMockSetup Optional custom mock setup callbacks
     *
     * @return \SprykerFeature\Client\SelfServicePortal\SelfServicePortalClient
     */
    protected function createSelfServicePortalClient(array $customMockSetup = []): SelfServicePortalClient
    {
        $storageClient = $this->createMock(StorageClientInterface::class);
        $synchronizationService = $this->createMock(SynchronizationServiceInterface::class);
        $utilEncodingService = $this->createMock(UtilEncodingServiceInterface::class);
        $sspAssetStorageMapper = $this->createMock(SspAssetStorageMapperInterface::class);
        $sspModelStorageMapper = $this->createMock(SspModelStorageMapperInterface::class);
        $sspAssetPermissionChecker = $this->createMock(SspAssetPermissionCheckerInterface::class);
        $productListStorageClient = $this->createMock(ProductListStorageClientInterface::class);
        $companyUserClient = $this->createMock(CompanyUserClientInterface::class);
        $productStorageClient = $this->createMock(ProductStorageClientInterface::class);
        $localeClient = $this->createMock(LocaleClientInterface::class);
        $storageKeyBuilder = $this->createMock(SynchronizationKeyGeneratorPluginInterface::class);

        $this->tester->setupStorageKeyBuilderMock($synchronizationService, $storageKeyBuilder);
        $this->tester->setupUtilEncodingMock($utilEncodingService);
        $this->tester->setupAssetPermissionCheckerMock($sspAssetPermissionChecker);
        $this->tester->setupAssetStorageMapperMock($sspAssetStorageMapper);
        $this->tester->setupModelStorageMapperMock($sspModelStorageMapper);

        $storageData = $this->tester->createStorageData();
        $this->tester->setupStorageClientMock($storageClient, $storageData);

        $companyUserTransfer = new CompanyUserTransfer();
        $companyUserTransfer->setIdCompanyUser(1);
        $companyUserClient->method(self::METHOD_NAME_FIND_COMPANY_USER)->willReturn($companyUserTransfer);

        $localeClient->method(self::METHOD_NAME_GET_CURRENT_LOCALE)->willReturn(AssetProductCompatibilityMatrixTester::LOCALE);

        if (isset($customMockSetup[self::DEPENDENCY_PRODUCT_LIST_STORAGE_CLIENT])) {
            $customMockSetup[self::DEPENDENCY_PRODUCT_LIST_STORAGE_CLIENT]($productListStorageClient);
        }
        if (isset($customMockSetup[self::DEPENDENCY_PRODUCT_STORAGE_CLIENT])) {
            $customMockSetup[self::DEPENDENCY_PRODUCT_STORAGE_CLIENT]($productStorageClient);
        }
        if (isset($customMockSetup[self::DEPENDENCY_ASSET_PERMISSION_CHECKER])) {
            $customMockSetup[self::DEPENDENCY_ASSET_PERMISSION_CHECKER]($sspAssetPermissionChecker);
        }
        if (isset($customMockSetup[self::DEPENDENCY_MODEL_STORAGE_MAPPER])) {
            $sspModelStorageMapper = $customMockSetup[self::DEPENDENCY_MODEL_STORAGE_MAPPER]($sspModelStorageMapper);
        }

        $sspAssetStorageReader = new SspAssetStorageReader(
            $storageClient,
            $synchronizationService,
            $utilEncodingService,
            $sspAssetStorageMapper,
            $sspAssetPermissionChecker,
        );

        $sspModelStorageReader = new SspModelStorageReader(
            $storageClient,
            $synchronizationService,
            $utilEncodingService,
            $sspModelStorageMapper,
        );

        $factory = $this->createMock(SelfServicePortalFactory::class);
        $factory->method(self::METHOD_NAME_CREATE_SSP_ASSET_STORAGE_READER)->willReturn($sspAssetStorageReader);
        $factory->method(self::METHOD_NAME_CREATE_SSP_MODEL_STORAGE_READER)->willReturn($sspModelStorageReader);
        $factory->method(self::METHOD_NAME_GET_PRODUCT_LIST_STORAGE_CLIENT)->willReturn($productListStorageClient);
        $factory->method(self::METHOD_NAME_GET_COMPANY_USER_CLIENT)->willReturn($companyUserClient);
        $factory->method(self::METHOD_NAME_GET_PRODUCT_STORAGE_CLIENT)->willReturn($productStorageClient);
        $factory->method(self::METHOD_NAME_GET_LOCALE_CLIENT)->willReturn($localeClient);
        $factory->method(self::METHOD_NAME_CREATE_ASSET_PRODUCT_COMPATIBILITY_CHECKER)->willReturn(
            new AssetProductCompatibilityChecker(
                $sspAssetStorageReader,
                $sspModelStorageReader,
                $productListStorageClient,
                $companyUserClient,
                $productStorageClient,
                $localeClient,
            ),
        );

        $client = new SelfServicePortalClient();
        $client->setFactory($factory);

        return $client;
    }
}
