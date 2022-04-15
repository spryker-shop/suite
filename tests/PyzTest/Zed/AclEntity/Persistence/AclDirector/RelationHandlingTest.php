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
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Pyz\Zed\Merchant\MerchantDependencyProvider;
use PyzTest\Zed\AclEntity\AclQueryDirectorTester;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\AclEntityDependencyProvider;
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

        $this->tester->setDependency(MerchantDependencyProvider::PLUGINS_MERCHANT_POST_CREATE, []);

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
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ],
        );

        $aclModelDirector = $this->tester->createAclModelDirector($rolesTransfer);

        $productEntity = new SpyProduct();
        $productEntity->setSpyProductAbstract(new SpyProductAbstract());

        // Act, Assert
        $aclModelDirector->inspectCreate($productEntity);
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
            'Operation "create" is restricted for Orm\Zed\Product\Persistence\SpyProductAbstract',
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
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $aclModelDirector = $this->tester->createAclModelDirector($rolesTransfer);

        $productEntity = new SpyProduct();
        $productEntity->setSpyProductAbstract(new SpyProductAbstract());

        // Act
        $aclModelDirector->inspectCreate($productEntity);
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
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE,
            ],
        );

        $aclModelDirector = $this->tester->createAclModelDirector($rolesTransfer);

        $productAbstractEntity = $this->tester->findProductAbstractByIdProductAbstract(
            $productAbstractTransfer->getIdProductAbstractOrFail(),
        );
        $productAbstractEntity->setSku($productAbstractEntity->getSku() . time());
        $newProductConcreteEntity = new SpyProduct();
        $newProductConcreteEntity->setSpyProductAbstract($productAbstractEntity);

        // Act, Assert
        $aclModelDirector->inspectCreate($newProductConcreteEntity);
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
            'Operation "update" is restricted for Orm\Zed\Product\Persistence\SpyProductAbstract',
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
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ],
        );

        $aclModelDirector = $this->tester->createAclModelDirector($rolesTransfer);

        $productAbstractEntity = $this->tester->findProductAbstractByIdProductAbstract(
            $productAbstractTransfer->getIdProductAbstractOrFail(),
        );
        $productAbstractEntity->setSku($productAbstractEntity->getSku() . time());
        $newProductConcreteEntity = new SpyProduct();
        $newProductConcreteEntity->setSpyProductAbstract($productAbstractEntity);

        // Act
        $aclModelDirector->inspectCreate($newProductConcreteEntity);
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
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE,
            ],
        );

        $aclModelDirector = $this->tester->createAclModelDirector($rolesTransfer);

        $productEntity = $this->tester->findProductConcreteByIdProduct($productTransfer->getIdProductConcreteOrFail());
        $productEntity->setSku($productEntity->getSku() . time());
        $productAbstractEntity = $this->tester->findProductAbstractByIdProductAbstract(
            $productTransfer->getFkProductAbstractOrFail(),
        );
        $productAbstractEntity->setSku($productAbstractEntity->getSku() . time());

        // Act, Assert
        $aclModelDirector->inspectUpdate($productEntity);
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
            'Operation "update" is restricted for Orm\Zed\Product\Persistence\SpyProductAbstract',
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
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $aclModelDirector = $this->tester->createAclModelDirector($rolesTransfer);

        $productEntity = $this->tester->findProductConcreteByIdProduct($productTransfer->getIdProductConcreteOrFail());
        $productEntity->setSku($productEntity->getSku() . time());

        $productAbstractEntity = $this->tester->findProductAbstractByIdProductAbstract(
            $productTransfer->getFkProductAbstractOrFail(),
        );
        $productAbstractEntity->setSku($productAbstractEntity->getSku() . time());

        // Act
        $aclModelDirector->inspectUpdate($productEntity);
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
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ],
        );

        $aclModelDirector = $this->tester->createAclModelDirector($rolesTransfer);

        $productAbstractEntity = $this->tester->findProductAbstractByIdProductAbstract(
            $productAbstractTransfer->getIdProductAbstract(),
        );
        $productAbstractEntity->setSku($productAbstractEntity->getSku() . time());
        $productAbstractEntity->addSpyProduct(new SpyProduct());

        // Act, Assert
        $aclModelDirector->inspectUpdate($productAbstractEntity);
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
            'Operation "create" is restricted for Orm\Zed\Product\Persistence\SpyProduct',
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
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $aclModelDirector = $this->tester->createAclModelDirector($rolesTransfer);

        $productAbstractEntity = $this->tester->findProductAbstractByIdProductAbstract(
            $productAbstractTransfer->getIdProductAbstract(),
        );
        $productAbstractEntity->setSku($productAbstractEntity->getSku() . time());
        $productAbstractEntity->addSpyProduct(new SpyProduct());

        // Act, Assert
        $aclModelDirector->inspectUpdate($productAbstractEntity);
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

        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $query = SpyProductQuery::create()->joinWithSpyProductAbstract()->filterBySku(
            $productConcreteTransfer->getSkuOrFail(),
        );

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        /** @var \Orm\Zed\Product\Persistence\SpyProduct $productConcreteEntity */
        foreach ($query->find() as $productConcreteEntity) {
            $this->assertNotEmpty($productConcreteEntity->getSpyProductAbstract());
        }
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

        $productTransfer = $this->tester->haveProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => 0,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $query = SpyProductQuery::create()->leftJoinWithSpyProductAbstract()->filterBySku(
            $productTransfer->getSkuOrFail(),
        );

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        $this->assertNotEmpty($query->count());
        foreach ($query->find()->toArray() as $item) {
            $this->assertArrayNotHasKey('SpyProductAbstract', $item);
        }
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
        $productConcreteTransfer = $this->tester->haveProduct();
        $merchantProductAbstractTransfer = $this->tester->haveMerchantProduct(
            [
                MerchantProductTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchantOrFail(),
                MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstractOrFail(),
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchantProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $segmentTransfer = $this->tester->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::NAME => AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_NAME,
                AclEntitySegmentRequestTransfer::REFERENCE => AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_REFERENCE,
                AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantTransfer->getIdMerchantOrFail()],
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $segmentTransfer->getIdAclEntitySegmentOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $query = SpyMerchantProductAbstractQuery::create()->leftJoinWithMerchant()->filterByFkMerchant(
            $merchantTransfer->getIdMerchantOrFail(),
        );

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        /** @var \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract $merchantProductEntity */
        foreach ($query->find() as $merchantProductEntity) {
            $this->assertNotEmpty($merchantProductEntity->getMerchant());
        }
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
            ],
        );

        $merchantTransfer = $this->tester->haveMerchant();
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveMerchantProduct(
            [
                MerchantProductTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchantOrFail(),
                MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstractOrFail(),
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            (new AclEntityMetadataCollectionTransfer())
                ->addAclEntityMetadata(
                    SpyMerchant::class,
                    (new AclEntityMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                        ->setDefaultGlobalOperationMask(0),
                ),
        );

        $query = SpyMerchantProductAbstractQuery::create()->leftJoinWithMerchant()->filterByFkMerchant(
            $merchantTransfer->getIdMerchantOrFail(),
        );

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        foreach ($query->find()->toArray() as $item) {
            $this->assertArrayNotHasKey('Merchant', $item);
        }
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

        $merchantTransfer = $this->tester->haveMerchant();
        $productConcreteTransfer = $this->tester->haveProduct();
        $this->tester->haveMerchantProduct(
            [
                MerchantProductTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchantOrFail(),
                MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstractOrFail(),
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchantProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $aclEntityMetadataCollectionTransfer = (new AclEntityMetadataCollectionTransfer())->addAclEntityMetadata(
            SpyMerchant::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchant::class)
                ->setDefaultGlobalOperationMask(AclEntityConstants::OPERATION_MASK_READ),
        );
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, $aclEntityMetadataCollectionTransfer);

        $query = SpyMerchantProductAbstractQuery::create()->joinWithMerchant()->filterByFkMerchant(
            $merchantTransfer->getIdMerchantOrFail(),
        );

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        /** @var \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract $merchantProductAbstractEntity */
        foreach ($query->find() as $merchantProductAbstractEntity) {
            $this->assertNotEmpty($merchantProductAbstractEntity->getMerchant());
        }
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesOnSelectQueryWithReadPermissionForLeftJoin(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $merchantProductTransfer = $this->tester->createMerchantProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchantProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $query = SpyMerchantProductAbstractQuery::create()->leftJoinMerchant()->filterByFkMerchant(
            $merchantProductTransfer->getIdMerchantOrFail(),
        );

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        /** @var \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract $merchantProductAbstractEntity */
        foreach ($query->find() as $merchantProductAbstractEntity) {
            $this->assertNotEmpty($merchantProductAbstractEntity->getMerchant());
        }
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesOnSelectQueryWithNoReadPermissionForLeftJoin(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $merchantProductTransfer = $this->tester->createMerchantProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchantProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $query = SpyMerchantProductAbstractQuery::create()->leftJoinMerchant()->filterByFkMerchant(
            $merchantProductTransfer->getIdMerchantOrFail(),
        );

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        foreach ($query->find()->toArray() as $merchantProductAbstract) {
            $this->assertArrayNotHasKey('Merchant', $merchantProductAbstract);
        }
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesOnSelectQueryWithReadPermissionForInnerJoin(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $merchantProductTransfer = $this->tester->createMerchantProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchantProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $query = SpyMerchantProductAbstractQuery::create()->innerJoinMerchant()->filterByFkMerchant(
            $merchantProductTransfer->getIdMerchantOrFail(),
        );

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        /** @var \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract $merchantProductAbstractEntity */
        foreach ($query->find() as $merchantProductAbstractEntity) {
            $this->assertNotEmpty($merchantProductAbstractEntity->getMerchant());
        }
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesOnSelectQueryWithNoReadPermissionForInnerJoin(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $merchantProductTransfer = $this->tester->createMerchantProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchantProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $query = SpyMerchantProductAbstractQuery::create()->innerJoinMerchant()->filterByFkMerchant(
            $merchantProductTransfer->getIdMerchantOrFail(),
        );

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        foreach ($query->find()->toArray() as $merchantProductAbstract) {
            $this->assertArrayNotHasKey('Merchant', $merchantProductAbstract);
        }
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesOnSelectQueryWithReadPermissionForRightJoin(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $merchantProductTransfer = $this->tester->createMerchantProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchantProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $query = SpyMerchantProductAbstractQuery::create()->innerJoinMerchant()->filterByFkMerchant(
            $merchantProductTransfer->getIdMerchantOrFail(),
        );

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        /** @var \Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract $merchantProductAbstractEntity */
        foreach ($query->find() as $merchantProductAbstractEntity) {
            $this->assertNotEmpty($merchantProductAbstractEntity->getMerchant());
        }
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesOnSelectQueryWithNoReadPermissionForRightJoin(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $merchantProductTransfer = $this->tester->createMerchantProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchantProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $query = SpyMerchantProductAbstractQuery::create()->rightJoinMerchant()->filterByFkMerchant(
            $merchantProductTransfer->getIdMerchantOrFail(),
        );

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        foreach ($query->find()->toArray() as $merchantProductAbstract) {
            $this->assertArrayNotHasKey('Merchant', $merchantProductAbstract);
        }
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesOnSelectQueryWithReadPermissionForOptionalLeftJoinRelation(): void
    {
        // Arrange
        $this->tester->setDependency(
            AclEntityDependencyProvider::PLUGINS_ACL_ENTITY_METADATA_COLLECTION_EXPANDER,
            [
                $this->tester->getProductAbstractMerchantAclEntityMetadataConfigExpanderPlugin(),
            ],
        );

        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $merchantTransfer = $this->tester->haveMerchant();
        $segmentTransfer = $this->tester->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::NAME => AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_NAME,
                AclEntitySegmentRequestTransfer::REFERENCE => AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_REFERENCE,
                AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantTransfer->getIdMerchantOrFail()],
            ],
        );
        $productAbstract1Transfer = $this->tester->haveProductAbstract();
        $productAbstract2Transfer = $this->tester->haveProductAbstract();
        $this->tester->haveMerchantProduct(
            [
                MerchantProductTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchantOrFail(),
                MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstract1Transfer->getIdProductAbstractOrFail(),
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
                AclEntityRuleTransfer::ENTITY => SpyMerchantProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $segmentTransfer->getIdAclEntitySegmentOrFail(),
            ],
        );
        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            $this->tester->createProductAbstractMerchantMetadataHierarchy(),
        );
        $query = SpyProductAbstractQuery::create()
            ->leftJoinSpyMerchantProductAbstract()
            ->filterByIdProductAbstract_In(
                [
                    $productAbstract1Transfer->getIdProductAbstractOrFail(),
                    $productAbstract2Transfer->getIdProductAbstractOrFail(),
                ],
            )
            ->orderByIdProductAbstract();

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);
        $productAbstractCollection = $query->find();

        // Assert
        $this->assertCount(2, $productAbstractCollection);
    }
}
