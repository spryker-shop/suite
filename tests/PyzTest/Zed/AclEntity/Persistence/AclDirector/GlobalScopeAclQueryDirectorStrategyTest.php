<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\AclEntity\Persistence\AclDirector;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use PyzTest\Zed\AclEntity\AclQueryDirectorTester;
use Spryker\Shared\AclEntity\AclEntityConstants;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group AclEntity
 * @group Persistence
 * @group AclDirector
 * @group GlobalScopeAclQueryDirectorStrategyTest
 * Add your own group annotations below this line
 */
class GlobalScopeAclQueryDirectorStrategyTest extends Unit
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
    public function testGlobalScopePrecedenceDefaultScope(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ],
        );
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act, Assert
        $aclQueryDirector->inspectCreate(new SpyProduct());
    }

    /**
     * @group AclEntityCreate
     *
     * @return void
     */
    public function testInspectCreateWithCreatePermission(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act, Assert
        $aclQueryDirector->inspectCreate(new SpyProduct());
    }

    /**
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithUpdatePermission(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE,
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act, Assert
        $aclQueryDirector->inspectUpdate(
            $this->tester->findProductConcreteByIdProduct($productConcreteTransfer->getIdProductConcreteOrFail()),
        );
    }

    /**
     * @group AclEntityDelete
     *
     * @return void
     */
    public function testInspectDeleteWithDeletePermission(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_DELETE,
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act, Assert
        $aclQueryDirector->inspectDelete(
            $this->tester->findProductConcreteByIdProduct($productConcreteTransfer->getIdProductConcreteOrFail()),
        );
    }

    /**
     * @group AclEntityCreate
     *
     * @return void
     */
    public function testInspectCreateWithMixedPermissions(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE
                    | AclEntityConstants::OPERATION_MASK_CREATE,
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act, Assert
        $aclQueryDirector->inspectCreate(new SpyProduct());
    }

    /**
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithMixedPermissions(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE
                    | AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act, Assert
        $aclQueryDirector->inspectUpdate(
            $this->tester->findProductConcreteByIdProduct($productConcreteTransfer->getIdProductConcreteOrFail()),
        );
    }

    /**
     * @group AclEntityDelete
     *
     * @return void
     */
    public function testInspectDeleteWithMixedPermissions(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_DELETE
                    | AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act
        $aclQueryDirector->inspectDelete(
            $this->tester->findProductConcreteByIdProduct($productConcreteTransfer->getIdProductConcreteOrFail()),
        );
    }

    /**
     * @group AclEntityCreate
     * @group AclEntityUpdate
     * @group AclEntityDelete
     *
     * @return void
     */
    public function testInspectCudWithCudPermissions(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE
                    | AclEntityConstants::OPERATION_MASK_READ
                    | AclEntityConstants::OPERATION_MASK_UPDATE
                    | AclEntityConstants::OPERATION_MASK_DELETE,
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);
        $productConcreteEntity = $this->tester->findProductConcreteByIdProduct(
            $productConcreteTransfer->getIdProductConcreteOrFail(),
        );

        // Act, Assert
        $aclQueryDirector->inspectCreate($productConcreteEntity);
        $aclQueryDirector->inspectUpdate($productConcreteEntity);
        $aclQueryDirector->inspectDelete($productConcreteEntity);
    }

    /**
     * @group AclEntityCreate
     * @group AclEntityUpdate
     * @group AclEntityDelete
     *
     * @return void
     */
    public function testInspectCreateWithMultipleRules(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

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
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CRUD,
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $productEntity = $this->tester->findProductConcreteByIdProduct(
            $productConcreteTransfer->getIdProductConcreteOrFail(),
        );

        // Act, Assert
        $aclQueryDirector->inspectCreate(new SpyProduct());
        $aclQueryDirector->inspectUpdate($productEntity);
        $aclQueryDirector->inspectDelete($productEntity);
    }

    /**
     * @group AclEntityCreate
     * @group AclEntityUpdate
     * @group AclEntityDelete
     *
     * @return void
     */
    public function testInspectCreateWithMultipleRoles(): void
    {
        // Arrange
        $role1Transfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $role2Transfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_2_NAME]);

        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $role1Transfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $role2Transfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CRUD,
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($role1Transfer)->addRole($role2Transfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $productEntity = $this->tester->findProductConcreteByIdProduct(
            $productConcreteTransfer->getIdProductConcreteOrFail(),
        );

        // Act, Assert
        $aclQueryDirector->inspectCreate(new SpyProduct());
        $aclQueryDirector->inspectUpdate($productEntity);
        $aclQueryDirector->inspectDelete($productEntity);
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRuleOnSelectQueryWithReadPermission(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ
                    | AclEntityConstants::OPERATION_MASK_UPDATE,
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);
        $productConcreteQuery = SpyProductQuery::create();

        // Act
        $aclQueryDirector->applyAclRuleOnSelectQuery($productConcreteQuery);

        // Assert
        $this->assertNotEmpty($productConcreteQuery->count());

        $this->assertStringNotContainsString(
            'id_product is null',
            $this->tester->purify($productConcreteQuery->toString()),
        );
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRuleOnUpdateQueryWithUpdatePermission(): void
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
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ
                    | AclEntityConstants::OPERATION_MASK_UPDATE,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);
        $productConcreteQuery = SpyProductQuery::create()->filterBySku($productConcreteTransfer->getSkuOrFail());

        // Act
        $productConcreteQuery = $aclQueryDirector->applyAclRuleOnUpdateQuery($productConcreteQuery);
        $updateCount = $productConcreteQuery->update([ucfirst(ProductConcreteTransfer::SKU) => time()]);

        // Assert
        $this->assertStringNotContainsString(
            SpyProductTableMap::COL_ID_PRODUCT . ' is null',
            str_replace('`', '', strtolower($productConcreteQuery->toString())),
        );
        $this->assertNotEmpty($updateCount);
    }

    /**
     * @group AclEntityCreate
     * @group AclEntityUpdate
     * @group AclEntityDelete
     *
     * @return void
     */
    public function testInspectWhildcardEntityWithFullPermissionsForGlobalScope(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $productConcreteTransfer = $this->tester->haveProduct();

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => AclEntityConstants::WHILDCARD_ENTITY,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CRUD,
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        $productEntity = $this->tester->findProductConcreteByIdProduct(
            $productConcreteTransfer->getIdProductConcreteOrFail(),
        );

        // Act, Assert
        $aclQueryDirector->inspectCreate(new SpyProduct());
        $aclQueryDirector->inspectUpdate($productEntity);
        $aclQueryDirector->inspectDelete($productEntity);
    }
}
