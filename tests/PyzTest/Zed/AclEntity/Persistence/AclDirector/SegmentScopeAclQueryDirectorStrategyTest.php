<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\AclEntity\Persistence\AclDirector;

use ArrayObject;
use Codeception\Test\Unit;
use DateTime;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Orm\Zed\Merchant\Persistence\Map\SpyMerchantTableMap;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Pyz\Zed\Merchant\MerchantDependencyProvider;
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
 * @group SegmentScopeAclQueryDirectorStrategyTest
 * Add your own group annotations below this line
 */
class SegmentScopeAclQueryDirectorStrategyTest extends Unit
{
    protected const MERCHANT_COL_UPDATED_AT = 'UpdatedAt';

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
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithUpdatePermission(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $merchantTransfer = $this->tester->haveMerchant();
        $aclEntitySegmentTransfer = $this->tester->haveAclEntitySegment(
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
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $aclEntitySegmentTransfer
                    ->getIdAclEntitySegmentOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE,
            ]
        );

        $rolesTransfer = (new RolesTransfer())->setRoles(new ArrayObject([$roleTransfer]));
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act, Assert
        $aclQueryDirector->inspectUpdate(
            $this->tester->findMerchantByIdMerchant($merchantTransfer->getIdMerchantOrFail())
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

        $merchantTransfer = $this->tester->haveMerchant();
        $aclEntitySegmentTransfer = $this->tester->haveAclEntitySegment(
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
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $aclEntitySegmentTransfer
                    ->getIdAclEntitySegmentOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_DELETE,
            ]
        );

        $rolesTransfer = (new RolesTransfer())->setRoles(new ArrayObject([$roleTransfer]));
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act, Assert
        $aclQueryDirector->inspectDelete(
            $this->tester->findMerchantByIdMerchant($merchantTransfer->getIdMerchantOrFail())
        );
    }

    /**
     * @group AclEntityUpdate
     *
     * @return void
     */
    public function testInspectUpdateWithCrudPermission(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $merchantTransfer = $this->tester->haveMerchant();
        $aclEntitySegmentTransfer = $this->tester->haveAclEntitySegment(
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
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $aclEntitySegmentTransfer
                    ->getIdAclEntitySegmentOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CRUD,
            ]
        );

        $rolesTransfer = (new RolesTransfer())->setRoles(new ArrayObject([$roleTransfer]));
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act, Assert
        $aclQueryDirector->inspectUpdate(
            $this->tester->findMerchantByIdMerchant($merchantTransfer->getIdMerchantOrFail())
        );
    }

    /**
     * @group AclEntityDelete
     *
     * @return void
     */
    public function testInspectDeleteWithCrudPermission(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);

        $merchantTransfer = $this->tester->haveMerchant();
        $aclEntitySegmentTransfer = $this->tester->haveAclEntitySegment(
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
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $aclEntitySegmentTransfer
                    ->getIdAclEntitySegmentOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CRUD,
            ]
        );

        $rolesTransfer = (new RolesTransfer())->setRoles(new ArrayObject([$roleTransfer]));
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act, Assert
        $aclQueryDirector->inspectDelete(
            $this->tester->findMerchantByIdMerchant($merchantTransfer->getIdMerchantOrFail())
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
        $merchantInSegmentTransfer = $this->tester->haveMerchant();
        $merchantOutOfSegmentTransfer = $this->tester->haveMerchant();

        $aclEntitySegmentTransfer = $this->tester->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::NAME => AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_NAME,
                AclEntitySegmentRequestTransfer::REFERENCE => AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_REFERENCE,
                AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantInSegmentTransfer->getIdMerchantOrFail()],
            ]
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $aclEntitySegmentTransfer
                    ->getIdAclEntitySegmentOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ]
        );

        $rolesTransfer = (new RolesTransfer())->setRoles(new ArrayObject([$roleTransfer]));
        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act
        $merchantCollection = $aclQueryDirector->applyAclRuleOnSelectQuery(SpyMerchantQuery::create())->find();

        // Assert
        $this->assertSame(1, $merchantCollection->count());
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

        $merchantInSegmentTransfer = $this->tester->haveMerchant();
        $merchantOutOfSegmentTransfer = $this->tester->haveMerchant();

        $aclEntitySegmentTransfer = $this->tester->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::NAME => AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_NAME,
                AclEntitySegmentRequestTransfer::REFERENCE => AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_REFERENCE,
                AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantInSegmentTransfer->getIdMerchantOrFail()],
            ]
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $aclEntitySegmentTransfer
                    ->getIdAclEntitySegmentOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_UPDATE,
            ]
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act
        $merchantQuery = SpyMerchantQuery::create()->filterByIdMerchant_In(
            [
                $merchantInSegmentTransfer->getIdMerchantOrFail(),
                $merchantOutOfSegmentTransfer->getIdMerchantOrFail(),
            ]
        );
        $merchantQuery = $aclQueryDirector->applyAclRuleOnUpdateQuery($merchantQuery);

        $updateCount = $merchantQuery->update(
            [self::MERCHANT_COL_UPDATED_AT => (new DateTime('-5 minutes'))->format('Y-m-d H:i:s')]
        );

        // Assert
        $this->assertEquals(1, $updateCount);
    }

    /**
     * @group AclEntityApplyAclRules
     *
     * @return void
     */
    public function testApplyAclRuleOnDeleteQueryWithDeletePermission(): void
    {
        // Arrange
        $roleTransfer = $this->tester->haveRole([RoleTransfer::NAME => AclQueryDirectorTester::ACL_ROLE_1_NAME]);
        $rolesTransfer = (new RolesTransfer())->addRole($roleTransfer);

        $merchantInSegmentTransfer = $this->tester->haveMerchant();
        $merchantOutOfSegmentTransfer = $this->tester->haveMerchant();

        $aclEntitySegmentTransfer = $this->tester->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::NAME => AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_NAME,
                AclEntitySegmentRequestTransfer::REFERENCE => AclQueryDirectorTester::ACL_ENTITY_SEGMENT_1_REFERENCE,
                AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantInSegmentTransfer->getIdMerchantOrFail()],
            ]
        );

        $this->tester->haveAclEntityRule(
            [
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRole(),
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $aclEntitySegmentTransfer
                    ->getIdAclEntitySegmentOrFail(),
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_DELETE,
            ]
        );

        $aclQueryDirector = $this->tester->createAclQueryDirector($rolesTransfer);

        // Act
        $merchantQuery = SpyMerchantQuery::create()->filterByIdMerchant_In(
            [
                $merchantInSegmentTransfer->getIdMerchantOrFail(),
                $merchantOutOfSegmentTransfer->getIdMerchantOrFail(),
            ]
        );
        $merchantQuery = $aclQueryDirector->applyAclRuleOnDeleteQuery($merchantQuery);

        $idMerchantCriteria = $merchantQuery->getCriterion(SpyMerchantTableMap::COL_ID_MERCHANT);

        // Assert
        $this->assertIsArray($idMerchantCriteria->getValue());
        $this->assertCount(1, $idMerchantCriteria->getValue());
    }
}
