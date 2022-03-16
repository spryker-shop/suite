<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\AclEntity\Persistence\AclDirector;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantCategoryTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\MerchantCategory\Persistence\Map\SpyMerchantCategoryTableMap;
use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategoryQuery;
use Orm\Zed\ProductOffer\Persistence\Map\SpyProductOfferTableMap;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\ActiveQuery\ModelJoin;
use Pyz\Zed\AclEntity\AclEntityDependencyProvider;
use PyzTest\Zed\AclEntity\AclQueryDirectorTester;
use Spryker\Zed\AclEntity\Persistence\Exception\OperationNotAuthorizedException;
use Spryker\Zed\AclMerchantPortal\Communication\Plugin\AclEntity\MerchantPortalAclEntityMetadataConfigExpanderPlugin;
use Spryker\Zed\Merchant\MerchantDependencyProvider;
use Spryker\Zed\ProductOffer\ProductOfferDependencyProvider;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group AclEntity
 * @group Persistence
 * @group AclDirector
 * @group AclQueryDirectorTest
 * Add your own group annotations below this line
 */
class AclQueryDirectorTest extends Unit
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

        $this->tester->setDependency(
            AclEntityDependencyProvider::PLUGINS_ACL_ENTITY_METADATA_COLLECTION_EXPANDER,
            [new MerchantPortalAclEntityMetadataConfigExpanderPlugin()],
        );

        $this->tester->setDependency(
            MerchantDependencyProvider::PLUGINS_MERCHANT_POST_CREATE,
            [],
        );

        $this->tester->setDependency(
            ProductOfferDependencyProvider::PLUGINS_PRODUCT_OFFER_POST_CREATE,
            [],
        );

        $this->tester->deleteTestData();
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testForeignKeyQueryJoinDoesntInfluenceAclEntityJoins(): void
    {
        // Arrange
        $roleMerchantManagerTransfer = $this->tester->haveRole(
            [
                RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME,
            ],
        );
        $merchantTransfer = $this->tester->haveMerchant();
        $this->tester->createMerchantCategoryRules($merchantTransfer, $roleMerchantManagerTransfer);

        $category1Transfer = $this->tester->createCategory(AclQueryDirectorTester::CATEGORY_1_KEY);
        $this->tester->haveMerchantCategory(
            [
                MerchantCategoryTransfer::FK_MERCHANT => $merchantTransfer->getIdMerchantOrFail(),
                MerchantCategoryTransfer::FK_CATEGORY => $category1Transfer->getIdCategoryOrFail(),
            ],
        );

        $category2Transfer = $this->tester->createCategory(AclQueryDirectorTester::CATEGORY_2_KEY);
        $this->tester->haveMerchantCategory(
            [
                MerchantCategoryTransfer::FK_CATEGORY => $category2Transfer->getIdCategoryOrFail(),
            ],
        );

        $query = SpyMerchantCategoryQuery::create()->distinct();
        $join = new ModelJoin();
        $join->setTableMap(SpyMerchantQuery::create()->getTableMap());
        $join->setLeftTableName(SpyMerchantCategoryTableMap::TABLE_NAME);
        $join->setRightTableName(SpyMerchantTableMap::TABLE_NAME);
        $join->setJoinCondition(
            (new Criteria())->getNewCriterion(
                SpyMerchantTableMap::COL_ID_MERCHANT,
                0,
                Criteria::GREATER_THAN,
            ),
        );
        $query->addJoinObject($join, 'SpyMerchant');

        $aclQueryDirector = $this->tester->createAclQueryDirector(
            (new RolesTransfer())->addRole($roleMerchantManagerTransfer),
            $this->tester->createMerchantCategoryMerchantHierarchy(),
        );

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);
        $merchantCategoryCollection = $query->find();

        // Assert
        $this->assertTrue($query->hasJoin('SpyMerchant'));
        $this->assertSame(1, $merchantCategoryCollection->count());
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testReferenceColumnQueryJoinDoesntInfluenceAclEntityJoins(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $merchantTransfer = $this->tester->haveMerchant();

        $this->tester->createProductOfferRules($merchantTransfer, $roleTransfer);

        $this->tester->haveProductOffer(
            [
                ProductOfferTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReferenceOrFail(),
            ],
        );
        $this->tester->haveProductOffer();

        $query = SpyProductOfferQuery::create()->distinct();
        $join = new ModelJoin();
        $join->setLeftTableName(SpyProductOfferTableMap::TABLE_NAME);
        $join->setRightTableName(SpyMerchantTableMap::TABLE_NAME);
        $join->setTableMap(SpyMerchantQuery::create()->getTableMap());
        $join->setJoinCondition(
            (new Criteria())->getNewCriterion(
                SpyMerchantTableMap::COL_ID_MERCHANT,
                0,
                Criteria::GREATER_THAN,
            ),
        );
        $query->addJoinObject($join, 'SpyMerchant');

        $aclQueryDirector = $this->tester->createAclQueryDirector(
            (new RolesTransfer())->addRole($roleTransfer),
            $this->tester->createProductOfferMerchantHierarchy(),
        );

        // Act
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery($query);

        // Assert
        $this->assertTrue($query->hasJoin('SpyMerchant'));
        $this->assertSame(1, $query->count());
    }

    /**
     * @group AclEntityUpdate
     * @group AclEntityDelete
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testOneRoleRulesDontInfluenceOtherRole(): void
    {
        // Arrange
        $roleMerchant1ManagerTransfer = $this->tester->haveRole(
            [
                RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME,
            ],
        );
        $roleMerchant2ViewerTransfer = $this->tester->haveRole(
            [
                RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_2_NAME,
            ],
        );

        $merchant1Transfer = $this->tester->haveMerchant();
        $merchant2Transfer = $this->tester->haveMerchant();

        $this->tester->createProductOfferManagerRules($merchant1Transfer, $roleMerchant1ManagerTransfer);
        $this->tester->createProductOfferViewerRules($merchant2Transfer, $roleMerchant2ViewerTransfer);

        $merchant1ProductOfferTransfer = $this->tester->haveProductOffer(
            [
                ProductOfferTransfer::FK_MERCHANT => $merchant1Transfer->getIdMerchantOrFail(),
                ProductOfferTransfer::MERCHANT_REFERENCE => $merchant1Transfer->getMerchantReference(),
            ],
        );
        $merchant2ProductOfferTransfer = $this->tester->haveProductOffer(
            [
                ProductOfferTransfer::FK_MERCHANT => $merchant2Transfer->getIdMerchantOrFail(),
                ProductOfferTransfer::MERCHANT_REFERENCE => $merchant2Transfer->getMerchantReference(),
            ],
        );

        $rolesTransfer = (new RolesTransfer())->addRole($roleMerchant1ManagerTransfer)->addRole($roleMerchant2ViewerTransfer);

        $aclQueryDirector = $this->tester->createAclQueryDirector(
            $rolesTransfer,
            $this->tester->createProductOfferMetadataHierarchy(),
        );
        $aclModelDirector = $this->tester->createAclModelDirector(
            $rolesTransfer,
            $this->tester->createProductOfferMetadataHierarchy(),
        );

        $merchant1ProductOfferEntity = $this->tester->findProductOfferByIdProductOffer(
            $merchant1ProductOfferTransfer->getIdProductOfferOrFail(),
        );
        $merchant2ProductOfferEntity = $this->tester->findProductOfferByIdProductOffer(
            $merchant2ProductOfferTransfer->getIdProductOfferOrFail(),
        );

        // Act, Assert
        // User can manage merchant1 and view merchant2 ProductOffer
        $aclModelDirector->inspectUpdate($merchant1ProductOfferEntity);
        $aclModelDirector->inspectDelete($merchant1ProductOfferEntity);
        $query = $aclQueryDirector->applyAclRuleOnSelectQuery(
            SpyProductOfferQuery::create()->filterByIdProductOffer_In(
                [$merchant1ProductOfferTransfer->getIdProductOfferOrFail(), $merchant2ProductOfferTransfer->getIdProductOfferOrFail()],
            ),
        );
        $this->assertSame(2, $query->count());

        // User can't manage merchant2 ProductOffer
        $this->expectException(OperationNotAuthorizedException::class);
        $this->expectExceptionMessage(
            'Operation "update" is restricted for Orm\Zed\ProductOffer\Persistence\SpyProductOffer',
        );
        $aclModelDirector->inspectUpdate($merchant2ProductOfferEntity);
    }
}
