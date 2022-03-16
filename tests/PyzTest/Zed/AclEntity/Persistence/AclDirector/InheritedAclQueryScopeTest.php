<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\AclEntity\Persistence\AclDirector;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\AclEntitySegmentCriteriaTransfer;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Pyz\Zed\Merchant\MerchantDependencyProvider;
use Pyz\Zed\ProductOffer\ProductOfferDependencyProvider;
use PyzTest\Zed\AclEntity\AclQueryDirectorTester;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\AclEntityDependencyProvider;
use Spryker\Zed\AclMerchantPortal\Communication\Plugin\AclEntity\MerchantPortalAclEntityMetadataConfigExpanderPlugin;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group AclEntity
 * @group Persistence
 * @group AclDirector
 * @group InheritedAclQueryScopeTest
 * Add your own group annotations below this line
 */
class InheritedAclQueryScopeTest extends Unit
{
    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_MERCHANT_1_NAME = 'segment merchant 1';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_MERCHANT_1_REFERENCE = 'segment_1_reference';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_MERCHANT_2_NAME = 'segment merchant 2';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_MERCHANT_2_REFERENCE = 'segment_2_reference';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_MERCHANT_3_NAME = 'segment merchant 3';

    /**
     * @var string
     */
    protected const ACL_ENTITY_SEGMENT_MERCHANT_3_REFERENCE = 'segment_3_reference';

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
        $this->tester->setDependency(ProductOfferDependencyProvider::PLUGINS_PRODUCT_OFFER_POST_CREATE, []);
        $this->tester->setDependency(
            AclEntityDependencyProvider::PLUGINS_ACL_ENTITY_METADATA_COLLECTION_EXPANDER,
            [new MerchantPortalAclEntityMetadataConfigExpanderPlugin()],
        );

        $this->tester->deleteTestData();
        $this->tester->deleteAclEntitySegments(
            (new AclEntitySegmentCriteriaTransfer())
                ->setReferences(
                    [
                        static::ACL_ENTITY_SEGMENT_MERCHANT_1_REFERENCE,
                        static::ACL_ENTITY_SEGMENT_MERCHANT_2_REFERENCE,
                        static::ACL_ENTITY_SEGMENT_MERCHANT_3_REFERENCE,
                    ],
                ),
        );
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
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
                AclEntityRuleTransfer::ENTITY => SpyMerchantProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ
                    | AclEntityConstants::OPERATION_MASK_CREATE,
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

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclModelDirector = $this->tester->createAclModelDirector(
            $rolesTransfer,
            $this->tester->createProductAbstractMerchantMetadataHierarchy(),
        );

        // Act, Assert
        $aclModelDirector->inspectCreate(new SpyProductAbstract());
    }

    /**
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithUpdatePermissionAndSegmentScopeRuleForRootEntity(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $merchantTransfer = $this->tester->haveMerchant();
        $this->tester->haveMerchantProduct(
            [
                MerchantProductTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchantOrFail(),
                MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstractOrFail(),
            ],
        );

        $aclEntitySegmentTransfer = $this->tester->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::NAME => static::ACL_ENTITY_SEGMENT_MERCHANT_1_NAME,
                AclEntitySegmentRequestTransfer::REFERENCE => static::ACL_ENTITY_SEGMENT_MERCHANT_1_REFERENCE,
                AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantTransfer->getIdMerchantOrFail()],
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE,
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
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $aclEntitySegmentTransfer->getIdAclEntitySegment(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclModelDirector = $this->tester->createAclModelDirector(
            $rolesTransfer,
            $this->tester->createProductAbstractMerchantMetadataHierarchy(),
        );

        // Act, Assert
        $aclModelDirector->inspectUpdate(
            $this->tester->findProductAbstractByIdProductAbstract(
                $productAbstractTransfer->getIdProductAbstractOrFail(),
            ),
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

        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $merchantTransfer = $this->tester->haveMerchant();
        $this->tester->haveMerchantProduct(
            [
                MerchantProductTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchantOrFail(),
                MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstractOrFail(),
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
                AclEntityRuleTransfer::ENTITY => SpyProductAbstract::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_DELETE,
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
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclModelDirector = $this->tester->createAclModelDirector(
            $rolesTransfer,
            $this->tester->createProductAbstractMerchantMetadataHierarchy(),
        );

        // Act, Assert
        $aclModelDirector->inspectDelete(
            $this->tester->findProductAbstractByIdProductAbstract(
                $productAbstractTransfer->getIdProductAbstractOrFail(),
            ),
        );
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

        $productAbstractTransfer = $this->tester->haveProductAbstract();
        $merchantTransfer = $this->tester->haveMerchant();
        $this->tester->haveMerchantProduct(
            [
                MerchantProductTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchantOrFail(),
                MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productAbstractTransfer->getIdProductAbstractOrFail(),
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
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
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_GLOBAL,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            $this->tester->createProductAbstractMerchantMetadataHierarchy(),
        );
        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery(SpyProductAbstractQuery::create());

        // Assert
        $this->assertNotEmpty($query->count());
    }

    /**
     * @group AclEntityCreate
     *
     * @return void
     */
    public function testInspectCreateWithCreatePermissionAndReferenceBasedRelation(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
                AclEntityRuleTransfer::ENTITY => SpyProductOffer::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CREATE,
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

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclModelDirector = $this->tester->createAclModelDirector(
            $rolesTransfer,
            $this->tester->createProductOfferMetadataHierarchy(),
        );

        // Act, Assert
        $aclModelDirector->inspectCreate(new SpyProductOffer());
    }

    /**
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithUpdatePermissionAndReferenceBasedRelation(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $merchantTransfer = $this->tester->haveMerchant();
        $productOfferTransfer = $this->tester->haveProductOffer(
            [ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference()],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
                AclEntityRuleTransfer::ENTITY => SpyProductOffer::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE,
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

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclModelDirector = $this->tester->createAclModelDirector(
            $rolesTransfer,
            $this->tester->createProductOfferMetadataHierarchy(),
        );

        // Act, Assert
        $aclModelDirector->inspectUpdate(
            $this->tester->findProductOfferByIdProductOffer($productOfferTransfer->getIdProductOfferOrFail()),
        );
    }

    /**
     * @group AclEntityDelete
     *
     * @return void
     */
    public function testInspectDeleteWithDeletePermissionAndReferenceBasedRelation(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $merchantTransfer = $this->tester->haveMerchant();
        $productOfferTransfer = $this->tester->haveProductOffer(
            [ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference()],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
                AclEntityRuleTransfer::ENTITY => SpyProductOffer::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_DELETE,
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

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclModelDirector = $this->tester->createAclModelDirector(
            $rolesTransfer,
            $this->tester->createProductOfferMetadataHierarchy(),
        );

        // Act, Assert
        $aclModelDirector->inspectDelete(
            $this->tester->findProductOfferByIdProductOffer($productOfferTransfer->getIdProductOfferOrFail()),
        );
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRuleOnSelectQueryWithReadPermissionAndReferenceBasedRelation(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $merchantTransfer = $this->tester->haveMerchant();
        $this->tester->haveProductOffer(
            [ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference()],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
                AclEntityRuleTransfer::ENTITY => SpyProductOffer::class,
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

        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            $this->tester->createProductOfferMetadataHierarchy(),
        );

        $query = SpyProductOfferQuery::create()->filterByCreatedAt(time(), Criteria::LESS_THAN);

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        $this->assertNotEmpty($query->count());
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRuleRuleWithReadPermissionAndMultipleRoles(): void
    {
        $merchant1RoleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $merchant2RoleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_2_NAME]);
        $merchant3RoleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_3_NAME]);

        $merchant1Transfer = $this->tester->haveMerchant();
        $merchant2Transfer = $this->tester->haveMerchant();
        $merchant3Transfer = $this->tester->haveMerchant();

        $productOfferMerchant1 = $this->tester->haveProductOffer(
            [
                ProductOfferTransfer::MERCHANT_REFERENCE => $merchant1Transfer->getMerchantReference(),
            ],
        );
        $productOfferMerchant2 = $this->tester->haveProductOffer(
            [ProductOfferTransfer::MERCHANT_REFERENCE => $merchant2Transfer->getMerchantReference()],
        );
        $productOfferMerchant3 = $this->tester->haveProductOffer(
            [ProductOfferTransfer::MERCHANT_REFERENCE => $merchant3Transfer->getMerchantReference()],
        );

        $segmentMerchant1Transfer = $this->tester->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::NAME => static::ACL_ENTITY_SEGMENT_MERCHANT_1_NAME,
                AclEntitySegmentRequestTransfer::REFERENCE => static::ACL_ENTITY_SEGMENT_MERCHANT_1_REFERENCE,
                AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchant1Transfer->getIdMerchantOrFail()],
            ],
        );
        $segmentMerchant2Transfer = $this->tester->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::NAME => static::ACL_ENTITY_SEGMENT_MERCHANT_2_NAME,
                AclEntitySegmentRequestTransfer::REFERENCE => static::ACL_ENTITY_SEGMENT_MERCHANT_2_REFERENCE,
                AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchant2Transfer->getIdMerchantOrFail()],
            ],
        );
        $segmentMerchant3Transfer = $this->tester->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::NAME => static::ACL_ENTITY_SEGMENT_MERCHANT_3_NAME,
                AclEntitySegmentRequestTransfer::REFERENCE => static::ACL_ENTITY_SEGMENT_MERCHANT_3_REFERENCE,
                AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchant3Transfer->getIdMerchantOrFail()],
            ],
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ID_ACL_ROLE => $merchant1RoleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::ENTITY => SpyProductOffer::class,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CRUD,
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ID_ACL_ROLE => $merchant1RoleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $segmentMerchant1Transfer->getIdAclEntitySegment(),
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ID_ACL_ROLE => $merchant1RoleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $segmentMerchant2Transfer->getIdAclEntitySegment(),
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ID_ACL_ROLE => $merchant1RoleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $segmentMerchant3Transfer->getIdAclEntitySegment(),
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );

        $rolesTransfer = (new RolesTransfer())
            ->addRole($merchant1RoleTransfer)
            ->addRole($merchant2RoleTransfer)
            ->addRole($merchant3RoleTransfer);
        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            $this->tester->createProductOfferMetadataHierarchy(),
        );

        $query = SpyProductOfferQuery::create()->orderByIdProductOffer();

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        $this->assertSame(3, $query->count());
    }
}
