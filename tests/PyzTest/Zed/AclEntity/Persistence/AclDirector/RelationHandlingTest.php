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
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyAclEntitySegmentMerchantTableMap;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use PyzTest\Zed\AclEntity\AclQueryDirectorTester;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group AclEntity
 * @group Persistence
 * @group AclDirector
 * @group RelationHandlingTest
 * Add your own group annotations below this line
 */
class RelationHandlingTest extends Unit
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

        $this->tester->deleteTestData();
    }

    /**
     * @group AclEntityCreate
     *
     * @return void
     */
    public function testInspectCreateWithCreatePermissionForRelation(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ]
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ]
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $productEntity = new SpyProduct();
        $productEntity->setSpyProductAbstract(new SpyProductAbstract());

        // Act, Assert
        $aclQueryDirector->inspectCreate($productEntity);
    }

    /**
     * @group AclEntityCreate
     *
     * @return void
     */
    public function testInspectCreateWithNoCreatePermissionForRelation(): void
    {
        // Assert
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage(
            'Operation "create" is restricted for Orm\Zed\Product\Persistence\SpyProductAbstract'
        );

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ
                    | AclEntityConstants::OPERATION_MASK_CREATE,
            ]
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ]
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $productEntity = new SpyProduct();
        $productEntity->setSpyProductAbstract(new SpyProductAbstract());

        // Act
        $aclQueryDirector->inspectCreate($productEntity);
    }

    /**
     * @group AclEntityCreate
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectCreateWithUpdatePermissionForRelation(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $productAbstractTransfer = $this->tester->haveProductAbstract();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ]
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE,
            ]
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $productAbstractEntity = $this->tester->findProductAbstractByIdProductAbstract(
            $productAbstractTransfer->getIdProductAbstractOrFail()
        );
        $productAbstractEntity->setSku($productAbstractEntity->getSku() . time());
        $newProductConcreteEntity = new SpyProduct();
        $newProductConcreteEntity->setSpyProductAbstract($productAbstractEntity);

        // Act, Assert
        $aclQueryDirector->inspectCreate($newProductConcreteEntity);
    }

    /**
     * @group AclEntityCreate
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectCreateWithNoUpdatePermissionForRelation(): void
    {
        // Assert
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage(
            'Operation "update" is restricted for Orm\Zed\Product\Persistence\SpyProductAbstract'
        );

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $productAbstractTransfer = $this->tester->haveProductAbstract();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ]
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ]
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $productAbstractEntity = $this->tester->findProductAbstractByIdProductAbstract(
            $productAbstractTransfer->getIdProductAbstractOrFail()
        );
        $productAbstractEntity->setSku($productAbstractEntity->getSku() . time());
        $newProductConcreteEntity = new SpyProduct();
        $newProductConcreteEntity->setSpyProductAbstract($productAbstractEntity);

        // Act
        $aclQueryDirector->inspectCreate($newProductConcreteEntity);
    }

    /**
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithUpdatePermissionForRelation(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $productTransfer = $this->tester->haveProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE,
            ]
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE,
            ]
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $productEntity = $this->tester->findProductConcreteByIdProduct($productTransfer->getIdProductConcreteOrFail());
        $productEntity->setSku($productEntity->getSku() . time());
        $productAbstractEntity = $this->tester->findProductAbstractByIdProductAbstract(
            $productTransfer->getFkProductAbstractOrFail()
        );
        $productAbstractEntity->setSku($productAbstractEntity->getSku() . time());

        // Act, Assert
        $aclQueryDirector->inspectUpdate($productEntity);
    }

    /**
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithNoUpdatePermissionForRelation(): void
    {
        // Assert
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage(
            'Operation "update" is restricted for Orm\Zed\Product\Persistence\SpyProductAbstract'
        );

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $productTransfer = $this->tester->haveProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE,
            ]
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ]
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $productEntity = $this->tester->findProductConcreteByIdProduct($productTransfer->getIdProductConcreteOrFail());
        $productEntity->setSku($productEntity->getSku() . time());

        $productAbstractEntity = $this->tester->findProductAbstractByIdProductAbstract(
            $productTransfer->getFkProductAbstractOrFail()
        );
        $productAbstractEntity->setSku($productAbstractEntity->getSku() . time());

        // Act
        $aclQueryDirector->inspectUpdate($productEntity);
    }

    /**
     * @group AclEntityCreate
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithCreatePermissionForRelation(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $productAbstractTransfer = $this->tester->haveProductAbstract();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE,
            ]
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ]
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $productAbstractEntity = $this->tester->findProductAbstractByIdProductAbstract(
            $productAbstractTransfer->getIdProductAbstract()
        );
        $productAbstractEntity->setSku($productAbstractEntity->getSku() . time());
        $productAbstractEntity->addSpyProduct(new SpyProduct());

        // Act, Assert
        $aclQueryDirector->inspectUpdate($productAbstractEntity);
    }

    /**
     * @group AclEntityCreate
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithNoCreatePermissionForRelation(): void
    {
        // Assert
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage(
            'Operation "create" is restricted for Orm\Zed\Product\Persistence\SpyProduct'
        );

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $productAbstractTransfer = $this->tester->haveProductAbstract();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE,
            ]
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ]
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $productAbstractEntity = $this->tester->findProductAbstractByIdProductAbstract(
            $productAbstractTransfer->getIdProductAbstract()
        );
        $productAbstractEntity->setSku($productAbstractEntity->getSku() . time());
        $productAbstractEntity->addSpyProduct(new SpyProduct());

        // Act, Assert
        $aclQueryDirector->inspectUpdate($productAbstractEntity);
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesOnSelectQueryWithReadPermissionForJoinsInGlobalScope(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ]
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ]
        );
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $query = SpyProductQuery::create()->joinWithSpyProductAbstract();

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        $this->assertSame(1, count($query->getWith()));
        $this->assertStringContainsString(
            'join ' . SpyProductAbstractTableMap::TABLE_NAME,
            $this->tester->purify($query->toString())
        );
        $this->assertStringContainsString(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, $query->toString());
        $this->assertStringContainsString(SpyProductAbstractTableMap::COL_FK_TAX_SET, $query->toString());
        $this->assertStringContainsString(SpyProductAbstractTableMap::COL_ATTRIBUTES, $query->toString());
        $this->assertStringContainsString(SpyProductAbstractTableMap::COL_COLOR_CODE, $query->toString());
        $this->assertStringContainsString(SpyProductAbstractTableMap::COL_NEW_FROM, $query->toString());
        $this->assertStringContainsString(SpyProductAbstractTableMap::COL_NEW_TO, $query->toString());
        $this->assertStringContainsString(SpyProductAbstractTableMap::COL_SKU, $query->toString());
        $this->assertStringContainsString(SpyProductAbstractTableMap::COL_CREATED_AT, $query->toString());
        $this->assertStringContainsString(SpyProductAbstractTableMap::COL_UPDATED_AT, $query->toString());
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesOnSelectQueryWithNoReadPermissionForJoinsInGlobalScope(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $this->tester->haveProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ]
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => 0,
            ]
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $query = SpyProductQuery::create()->leftJoinWithSpyProductAbstract();

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        $this->assertEmpty(count($query->getWith()));
        $this->assertStringContainsString(
            'join ' . SpyProductAbstractTableMap::TABLE_NAME,
            $this->tester->purify($query->toString())
        );
        $this->assertStringNotContainsString(SpyProductAbstractTableMap::COL_FK_TAX_SET, $query->toString());
        $this->assertStringNotContainsString(SpyProductAbstractTableMap::COL_ATTRIBUTES, $query->toString());
        $this->assertStringNotContainsString(SpyProductAbstractTableMap::COL_COLOR_CODE, $query->toString());
        $this->assertStringNotContainsString(SpyProductAbstractTableMap::COL_NEW_FROM, $query->toString());
        $this->assertStringNotContainsString(SpyProductAbstractTableMap::COL_NEW_TO, $query->toString());
        $this->assertStringNotContainsString(SpyProductAbstractTableMap::COL_SKU, $query->toString());
        $this->assertStringNotContainsString(SpyProductAbstractTableMap::COL_CREATED_AT, $query->toString());
        $this->assertStringNotContainsString(SpyProductAbstractTableMap::COL_UPDATED_AT, $query->toString());
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesOnSelectQueryWithReadPermissionForJoinsInSegmentScope(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $merchantTransfer = $this->tester->haveMerchant();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchantProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ]
        );
        $segmentTransfer = $this->tester->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::NAME => AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_NAME,
                AclEntitySegmentRequestTransfer::REFERENCE => AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_REFERENCE,
                AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantTransfer->getIdMerchantOrFail()],
            ]
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $segmentTransfer->getIdAclEntitySegmentOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ]
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $query = SpyMerchantProductAbstractQuery::create()->leftJoinWithMerchant();

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);
        $sql = $this->tester->purify($query->toString());

        // Assert
        $this->assertSame(1, count($query->getWith()));
        $this->assertStringContainsString(SpyMerchantTableMap::COL_ID_MERCHANT, $sql);
        $this->assertStringContainsString(SpyMerchantTableMap::COL_MERCHANT_REFERENCE, $sql);
        $this->assertStringContainsString(SpyMerchantTableMap::COL_EMAIL, $sql);
        $this->assertStringContainsString('join ' . SpyAclEntitySegmentMerchantTableMap::TABLE_NAME, $sql);
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesOnSelectQueryWithNoReadPermissionForJoin(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchantProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ]
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            (new AclEntityMetadataCollectionTransfer())
                ->addAclEntityMetadata(
                    SpyMerchant::class,
                    (new AclEntityMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                        ->setDefaultGlobalOperationMask(0)
                )
        );

        $query = SpyMerchantProductAbstractQuery::create()->leftJoinWithMerchant();

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);
        $sql = $this->tester->purify($query->toString());

        // Assert
        $this->assertEmpty(count($query->getWith()));
        $this->assertStringNotContainsString(SpyMerchantTableMap::COL_MERCHANT_REFERENCE, $sql);
        $this->assertStringNotContainsString(SpyMerchantTableMap::COL_EMAIL, $sql);
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesOnSelectQueryWithReadPermissionForJoinsInDefaultScope(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchantProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ]
        );

        $aclEntityMetadataCollectionTransfer = (new AclEntityMetadataCollectionTransfer())->addAclEntityMetadata(
            SpyMerchant::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchant::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ)
        );
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, $aclEntityMetadataCollectionTransfer);

        $query = SpyMerchantProductAbstractQuery::create()->joinWithMerchant();

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);
        $sql = $this->tester->purify($query->toString());

        // Assert
        $this->assertSame(1, count($query->getWith()));
        $this->assertStringContainsString(SpyMerchantTableMap::COL_ID_MERCHANT, $sql);
        $this->assertStringContainsString(SpyMerchantTableMap::COL_EMAIL, $sql);
        $this->assertStringContainsString(SpyMerchantTableMap::COL_NAME, $sql);
    }
}
