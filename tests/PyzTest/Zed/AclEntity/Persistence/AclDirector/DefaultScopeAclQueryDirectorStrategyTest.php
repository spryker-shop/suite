<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\AclEntity\Persistence\AclDirector;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\AclEntitySegmentCriteriaTransfer;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclRoleCriteriaTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\Store\Persistence\SpyStore;
use Pyz\Zed\Merchant\MerchantDependencyProvider;
use PyzTest\Zed\AclEntity\AclQueryDirectorTester;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\Strategy\AclQueryDirectorStrategyInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group AclEntity
 * @group Persistence
 * @group AclDirector
 * @group DefaultScopeAclQueryDirectorStrategyTest
 * Add your own group annotations below this line
 */
class DefaultScopeAclQueryDirectorStrategyTest extends Unit
{
    /**
     * @var \PyzTest\Zed\AclEntity\AclQueryDirectorTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(MerchantDependencyProvider::PLUGINS_MERCHANT_POST_CREATE, []);

        $this->tester->deleteRoles(
            (new AclRoleCriteriaTransfer())->setNames([AclQueryDirectorTester::ACL_ROLE_1_NAME])
        );
    }

    /**
     * @group AclEntitySegmentScope
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testSegmentScopePrecedenceDefaultScope(): void
    {
        // Arrange
        $this->tester->deleteAclEntitySegments(
            (new AclEntitySegmentCriteriaTransfer())
                ->setReferences([AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_REFERENCE])
        );

        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $merchantTransfer = $this->tester->haveMerchant();
        $aclEntitySegmentTransfer = $this->tester->haveAclEntitySegment([
            AclEntitySegmentRequestTransfer::NAME => AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_NAME,
            AclEntitySegmentRequestTransfer::REFERENCE => AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_REFERENCE,
            AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
            AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantTransfer->getIdMerchantOrFail()],
        ]);

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $aclEntitySegmentTransfer->getIdAclEntitySegment(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CRUD,
            ]
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act, Assert
        $aclQueryDirector->inspectUpdate(
            $this->tester->findMerchantByIdMerchant($merchantTransfer->getIdMerchantOrFail())
        );
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesWithReadPermissionOnEntityLevel(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $productTransfer = $this->tester->haveProduct();

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclEntityMetadataCollectionTransfer = (new AclEntityMetadataCollectionTransfer())
            ->addAclEntityMetadata(
                SpyProduct::class,
                (new AclEntityMetadataTransfer())
                    ->setEntityName(SpyProduct::class)
                    ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
            );
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, $aclEntityMetadataCollectionTransfer);
        $query = SpyProductQuery::create()->filterByIdProduct($productTransfer->getIdProductConcrete());

        // Act
        $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        $this->assertSame(1, $query->count());
        $this->assertStringNotContainsString(
            AclQueryDirectorStrategyInterface::CONDITION_EMPTY_COLLECTION,
            $query->toString()
        );
    }

    /**
     * @group AclEntityCreate
     *
     * @return void
     */
    public function testInspectCreateWithCreatePermissionInEntityLevel(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclEntityMetadataCollectionTransfer = (new AclEntityMetadataCollectionTransfer())
            ->addAclEntityMetadata(
                SpyProduct::class,
                (new AclEntityMetadataTransfer())
                    ->setEntityName(SpyProduct::class)
                    ->setDefaultGlobalOperationMask(
                        AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_CREATE
                    )
            );
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, $aclEntityMetadataCollectionTransfer);

        // Act, Assert
        $aclQueryDirector->inspectCreate(new SpyProduct());
    }

    /**
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithUpdatePermissionInEntityLevel(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $productTransfer = $this->tester->haveProduct();

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclEntityMetadataCollectionTransfer = (new AclEntityMetadataCollectionTransfer())
            ->addAclEntityMetadata(
                SpyProduct::class,
                (new AclEntityMetadataTransfer())
                    ->setEntityName(SpyProduct::class)
                    ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_UPDATE)
            );
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, $aclEntityMetadataCollectionTransfer);

        // Act, Assert
        $aclQueryDirector->inspectUpdate(
            $this->tester->findProductConcreteByIdProduct($productTransfer->getIdProductConcreteOrFail())
        );
    }

    /**
     * @group AclEntityDelete
     *
     * @return void
     */
    public function testInspectDeleteWithDeletePermissionInEntityLevel(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $productTransfer = $this->tester->haveProduct();

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclEntityMetadataCollectionTransfer = (new AclEntityMetadataCollectionTransfer())
            ->addAclEntityMetadata(
                SpyProduct::class,
                (new AclEntityMetadataTransfer())
                    ->setEntityName(SpyProduct::class)
                    ->setDefaultGlobalOperationMask(
                        AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_DELETE
                    )
            );
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, $aclEntityMetadataCollectionTransfer);

        // Act, Assert
        $aclQueryDirector->inspectDelete(
            $this->tester->findProductConcreteByIdProduct($productTransfer->getIdProductConcreteOrFail())
        );
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesWithReadPermissionInGlobalLevel(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $productTransfer = $this->tester->haveProduct();

        /** @var \Spryker\Zed\AclEntity\AclEntityConfig $aclEntityConfig */
        $aclEntityConfig = $this->tester->mockConfigMethod(
            'getDefaultGlobalOperationMask',
            AclEntityConstants::OPERATION_MASK_READ
        );
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, null, $aclEntityConfig);

        $query = SpyProductQuery::create()->filterByIdProduct($productTransfer->getIdProductConcrete());

        // Act
        $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        $this->assertSame(1, $query->count());
        $this->assertStringNotContainsString(
            AclQueryDirectorStrategyInterface::CONDITION_EMPTY_COLLECTION,
            $query->toString()
        );
    }

    /**
     * @group AclEntityCreate
     *
     * @return void
     */
    public function testInspectCreateWithCreatePermissionInGlobalLevel(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        /** @var \Spryker\Zed\AclEntity\AclEntityConfig $aclEntityConfig */
        $aclEntityConfig = $this->tester->mockConfigMethod(
            'getDefaultGlobalOperationMask',
            AclEntityConstants::OPERATION_MASK_CREATE
        );
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, null, $aclEntityConfig);

        // Act, Assert
        $aclQueryDirector->inspectCreate(new SpyProduct());
    }

    /**
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithUpdatePermissionInGlobalLevel(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $productTransfer = $this->tester->haveProduct();

        /** @var \Spryker\Zed\AclEntity\AclEntityConfig $aclEntityConfig */
        $aclEntityConfig = $this->tester->mockConfigMethod(
            'getDefaultGlobalOperationMask',
            AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_UPDATE
        );
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, null, $aclEntityConfig);

        // Act, Assert
        $aclQueryDirector->inspectUpdate(
            $this->tester->findProductConcreteByIdProduct($productTransfer->getIdProductConcreteOrFail())
        );
    }

    /**
     * @group AclEntityDelete
     *
     * @return void
     */
    public function testInspectDeleteWithDeletePermissionInGlobalLevel(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $productTransfer = $this->tester->haveProduct();

        /** @var \Spryker\Zed\AclEntity\AclEntityConfig $aclEntityConfig */
        $aclEntityConfig = $this->tester->mockConfigMethod(
            'getDefaultGlobalOperationMask',
            AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_DELETE
        );
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, null, $aclEntityConfig);

        // Act, Assert
        $aclQueryDirector->inspectDelete(
            $this->tester->findProductConcreteByIdProduct($productTransfer->getIdProductConcreteOrFail())
        );
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesWithNoReadPermissionInEntityLevel(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $productTransfer = $this->tester->haveProduct();

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclEntityMetadataCollectionTransfer = (new AclEntityMetadataCollectionTransfer())
            ->addAclEntityMetadata(
                SpyProduct::class,
                (new AclEntityMetadataTransfer())
                    ->setEntityName(SpyProduct::class)
                    ->setDefaultGlobalOperationMask(
                        AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_DELETE
                    )
            );
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, $aclEntityMetadataCollectionTransfer);
        $query = SpyProductQuery::create()->filterByIdProduct($productTransfer->getIdProductConcrete());

        // Act
        $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        $this->assertSame(0, $query->count());
        $this->assertStringContainsString(
            'id_product is null',
            $this->tester->purify($query->toString())
        );
    }

    /**
     * @group AclEntityCreate
     *
     * @return void
     */
    public function testInspectCreateWithNoCreatePermissionInEntityLevel(): void
    {
        // Assert
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage('Operation "create" is restricted for Orm\Zed\Product\Persistence\SpyProduct');

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclEntityMetadataCollectionTransfer = (new AclEntityMetadataCollectionTransfer())
            ->addAclEntityMetadata(
                SpyProduct::class,
                (new AclEntityMetadataTransfer())
                    ->setEntityName(SpyProduct::class)
                    ->setDefaultGlobalOperationMask(
                        AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_DELETE
                    )
            );
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, $aclEntityMetadataCollectionTransfer);

        // Act
        $aclQueryDirector->inspectCreate(new SpyProduct());
    }

    /**
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithNoUpdatePermissionInEntityLevel(): void
    {
        // Assert
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage('Operation "update" is restricted for Orm\Zed\Product\Persistence\SpyProduct');

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $productTransfer = $this->tester->haveProduct();

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclEntityMetadataCollectionTransfer = (new AclEntityMetadataCollectionTransfer())
            ->addAclEntityMetadata(
                SpyProduct::class,
                (new AclEntityMetadataTransfer())
                    ->setEntityName(SpyProduct::class)
                    ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
            );
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, $aclEntityMetadataCollectionTransfer);

        // Act
        $aclQueryDirector->inspectUpdate(
            $this->tester->findProductConcreteByIdProduct($productTransfer->getIdProductConcreteOrFail())
        );
    }

    /**
     * @group AclEntityDelete
     *
     * @return void
     */
    public function testInspectDeleteWithNoDeletePermissionInEntityLevel(): void
    {
        // Assert
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage('Operation "delete" is restricted for Orm\Zed\Product\Persistence\SpyProduct');

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $productTransfer = $this->tester->haveProduct();

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclEntityMetadataCollectionTransfer = (new AclEntityMetadataCollectionTransfer())
            ->addAclEntityMetadata(
                SpyProduct::class,
                (new AclEntityMetadataTransfer())
                    ->setEntityName(SpyProduct::class)
                    ->setDefaultGlobalOperationMask(
                        AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ
                    )
            );
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, $aclEntityMetadataCollectionTransfer);

        // Act
        $aclQueryDirector->inspectDelete(
            $this->tester->findProductConcreteByIdProduct($productTransfer->getIdProductConcreteOrFail())
        );
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesWithNoReadPermissionInGlobalLevel(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $productTransfer = $this->tester->haveProduct();

        /** @var \Spryker\Zed\AclEntity\AclEntityConfig $aclEntityConfig */
        $aclEntityConfig = $this->tester->mockConfigMethod(
            'getDefaultGlobalOperationMask',
            AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_DELETE
        );
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, null, $aclEntityConfig);

        $query = SpyProductQuery::create()->filterByIdProduct($productTransfer->getIdProductConcrete());

        // Act
        $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        $this->assertSame(0, $query->count());
        $this->assertStringContainsString(
            'id_product is null',
            $this->tester->purify($query->toString())
        );
    }

    /**
     * @group AclEntityCreate
     *
     * @return void
     */
    public function testInspectCreateWithNoCreatePermissionInGlobalLevel(): void
    {
        // Assert
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage('Operation "create" is restricted for Orm\Zed\Product\Persistence\SpyProduct');

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        /** @var \Spryker\Zed\AclEntity\AclEntityConfig $config */
        $config = $this->tester->mockConfigMethod(
            'getDefaultGlobalOperationMask',
            AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_DELETE
        );
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act
        $aclQueryDirector->inspectCreate(new SpyProduct());
    }

    /**
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithNoUpdatePermissionInGlobalLevel(): void
    {
        // Assert
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage('Operation "update" is restricted for Orm\Zed\Product\Persistence\SpyProduct');

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $productTransfer = $this->tester->haveProduct();

        /** @var \Spryker\Zed\AclEntity\AclEntityConfig $aclEntityConfig */
        $aclEntityConfig = $this->tester->mockConfigMethod(
            'getDefaultGlobalOperationMask',
            AclEntityConstants::OPERATION_MASK_READ
        );
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, null, $aclEntityConfig);

        // Act
        $aclQueryDirector->inspectUpdate(
            $this->tester->findProductConcreteByIdProduct($productTransfer->getIdProductConcreteOrFail())
        );
    }

    /**
     * @group AclEntityDelete
     *
     * @return void
     */
    public function testInspectDeleteWithNoDeletePermissionInGlobalLevel(): void
    {
        // Assert
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage('Operation "delete" is restricted for Orm\Zed\Product\Persistence\SpyProduct');

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $productTransfer = $this->tester->haveProduct();

        /** @var \Spryker\Zed\AclEntity\AclEntityConfig $config */
        $config = $this->tester->mockConfigMethod(
            'getDefaultGlobalOperationMask',
            AclEntityConstants::OPERATION_MASK_CREATE | AclEntityConstants::OPERATION_MASK_READ
        );
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act
        $aclQueryDirector->inspectDelete(
            $this->tester->findProductConcreteByIdProduct($productTransfer->getIdProductConcreteOrFail())
        );
    }

    /**
     * @group AclEntityCreate
     *
     * @return void
     */
    public function testOtherEntityGlobalScopeDefinitionDoesntInfluenceCurrentEntity(): void
    {
        // Assert
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage('Operation "create" is restricted for Orm\Zed\Product\Persistence\SpyProduct');

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyStore::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ]
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclEntityMetadataCollectionTransfer = (new AclEntityMetadataCollectionTransfer())
            ->addAclEntityMetadata(
                SpyProduct::class,
                (new AclEntityMetadataTransfer())
                    ->setEntityName(SpyProduct::class)
                    ->setDefaultGlobalOperationMask(
                        AclEntityConstants::OPERATION_MASK_READ | AclEntityConstants::OPERATION_MASK_DELETE
                    )
            );
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, $aclEntityMetadataCollectionTransfer);

        // Act, Assert
        $aclQueryDirector->inspectCreate(new SpyProduct());
    }
}
