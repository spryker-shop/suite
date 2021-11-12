<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\AclEntity\Persistence\AclDirector;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\ProductImageSetTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use PyzTest\Zed\AclEntity\AclQueryDirectorTester;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Persistence\Exception\MissingRootMetadataException;
use Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group AclEntity
 * @group Persistence
 * @group AclDirector
 * @group CompositeEntityTest
 * Add your own group annotations below this line
 */
class CompositeEntityTest extends Unit
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
    public function testInspectCreateWithCreatePermissionInRootEntity(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            $this->tester->createProductImageProductCompositeEntityMetadataHierarchy(),
        );

        // Act, Assert
        $aclQueryDirector->inspectCreate(new SpyProductImage());
    }

    /**
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithUpdatePermissionInRootEntity(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $productTransfer = $this->tester->haveProduct();
        $productImageSetTransfer = $this->tester->haveProductImageSet(
            [
                ProductImageSetTransfer::ID_PRODUCT => $productTransfer->getIdProductConcreteOrFail(),
                ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productTransfer->getFkProductAbstractOrFail(),
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ
                    | AclEntityConstants::OPERATION_MASK_UPDATE,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            $this->tester->createProductImageProductCompositeEntityMetadataHierarchy(),
        );

        // Act, Assert
        foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $aclQueryDirector->inspectUpdate(
                $this->tester->findProductImageByIdProductImage($productImageTransfer->getIdProductImageOrFail()),
            );
        }
    }

    /**
     * @group AclEntityDelete
     *
     * @return void
     */
    public function testInspectDeleteWithDeletePermissionInRootEntity(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $productTransfer = $this->tester->haveProduct();
        $productImageSetTransfer = $this->tester->haveProductImageSet(
            [
                ProductImageSetTransfer::ID_PRODUCT => $productTransfer->getIdProductConcreteOrFail(),
                ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productTransfer->getFkProductAbstractOrFail(),
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ
                    | AclEntityConstants::OPERATION_MASK_DELETE,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            $this->tester->createProductImageProductCompositeEntityMetadataHierarchy(),
        );

        // Act, Assert
        foreach ($productImageSetTransfer->getProductImages() as $productImageTransfer) {
            $aclQueryDirector->inspectDelete(
                $this->tester->findProductImageByIdProductImage($productImageTransfer->getIdProductImageOrFail()),
            );
        }
    }

    /**
     * @group AclEntityCreate
     *
     * @return void
     */
    public function testInspectCreateWithNoCreatePermissionInRootEntity(): void
    {
        // Assert
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage(
            'Operation "create" is restricted for Orm\Zed\ProductImage\Persistence\SpyProductImage',
        );

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            $this->tester->createProductImageProductCompositeEntityMetadataHierarchy(),
        );

        // Act
        $aclQueryDirector->inspectCreate(new SpyProductImage());
    }

    /**
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithNoUpdatePermissionInRootEntity(): void
    {
        // Assert
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage(
            'Operation "update" is restricted for Orm\Zed\ProductImage\Persistence\SpyProductImage',
        );

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $productTransfer = $this->tester->haveProduct();
        $productImageSetTransfer = $this->tester->haveProductImageSet(
            [
                ProductImageSetTransfer::ID_PRODUCT => $productTransfer->getIdProductConcreteOrFail(),
                ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productTransfer->getFkProductAbstractOrFail(),
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            $this->tester->createProductImageProductCompositeEntityMetadataHierarchy(),
        );
        $productImageTransfer = $productImageSetTransfer->getProductImages()->offsetGet(0);

        // Act
        $aclQueryDirector->inspectUpdate(
            $this->tester->findProductImageByIdProductImage($productImageTransfer->getIdProductImageOrFail()),
        );
    }

    /**
     * @group AclEntityDelete
     *
     * @return void
     */
    public function testInspectDeleteWithNoDeletePermissionInRootEntity(): void
    {
        // Assert
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage(
            'Operation "delete" is restricted for Orm\Zed\ProductImage\Persistence\SpyProductImage',
        );

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $productTransfer = $this->tester->haveProduct();
        $productImageSetTransfer = $this->tester->haveProductImageSet(
            [
                ProductImageSetTransfer::ID_PRODUCT => $productTransfer->getIdProductConcreteOrFail(),
                ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productTransfer->getFkProductAbstractOrFail(),
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ
                    | AclEntityConstants::OPERATION_MASK_CREATE,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            $this->tester->createProductImageProductCompositeEntityMetadataHierarchy(),
        );
        $productImageTransfer = $productImageSetTransfer->getProductImages()->offsetGet(0);

        // Act
        $aclQueryDirector->inspectDelete(
            $this->tester->findProductImageByIdProductImage($productImageTransfer->getIdProductImageOrFail()),
        );
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesWithReadPermissionInRootEntity(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $productTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet(
            [
                ProductImageSetTransfer::ID_PRODUCT => $productTransfer->getIdProductConcreteOrFail(),
                ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productTransfer->getFkProductAbstractOrFail(),
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            $this->tester->createProductImageProductCompositeEntityMetadataHierarchy(),
        );
        $query = SpyProductImageQuery::create()->orderByIdProductImage();

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        $this->assertStringNotContainsString(
            'id_product is null',
            $this->tester->purify($query->toString()),
        );
        $this->assertStringContainsString('join spy_product_image_set_to_product_image', strtolower($query->toString()));
        $this->assertStringContainsString('join spy_product_image_set', strtolower($query->toString()));
        $this->assertStringContainsString('join spy_product', strtolower($query->toString()));

        $this->assertGreaterThan(0, $query->count());
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRulesWithNoReadPermissionInRootEntity(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $productTransfer = $this->tester->haveProduct();
        $this->tester->haveProductImageSet(
            [
                ProductImageSetTransfer::ID_PRODUCT => $productTransfer->getIdProductConcreteOrFail(),
                ProductImageSetTransfer::ID_PRODUCT_ABSTRACT => $productTransfer->getFkProductAbstractOrFail(),
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyProduct::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE
                    | AclEntityConstants::OPERATION_MASK_DELETE,
            ],
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            $this->tester->createProductImageProductCompositeEntityMetadataHierarchy(),
        );
        $query = SpyProductImageQuery::create()->orderByIdProductImage();

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        $this->assertStringContainsString(
            'id_product is null',
            $this->tester->purify($query->toString()),
        );

        $this->assertEmpty($query->count());
    }

    /**
     * @group AclEntityCreate
     *
     * @return void
     */
    public function testInspectCreateWithNoRootParentDefinitionInConfig(): void
    {
        // Assert
        $this->expectException(MissingRootMetadataException::class);
        $this->expectExceptionMessage(
            'No root metadata definition found for Orm\Zed\ProductImage\Persistence\SpyProductImage',
        );

        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $aclEntityMetadataCollectionTransfer = (new AclEntityMetadataCollectionTransfer())
            ->addAclEntityMetadata(
                SpyProductImage::class,
                (new AclEntityMetadataTransfer())
                    ->setEntityName(SpyProductImage::class)
                    ->setIsSubEntity(true)
                    ->setParent(
                        (new AclEntityParentMetadataTransfer())
                            ->setEntityName(SpyProductImageSet::class),
                    ),
            )
            ->addAclEntityMetadata(
                SpyProductImageSet::class,
                (new AclEntityMetadataTransfer())->setEntityName(SpyProductImageSet::class)->setIsSubEntity(true),
            );
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer, $aclEntityMetadataCollectionTransfer);

        // Act
        $aclQueryDirector->inspectCreate(new SpyProductImage());
    }
}
