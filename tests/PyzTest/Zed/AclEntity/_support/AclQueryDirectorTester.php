<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\AclEntity;

use Codeception\Actor;
use Codeception\Stub;
use Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer;
use Generated\Shared\Transfer\AclEntityMetadataConfigTransfer;
use Generated\Shared\Transfer\AclEntityMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentConnectionMetadataTransfer;
use Generated\Shared\Transfer\AclEntityParentMetadataTransfer;
use Generated\Shared\Transfer\AclEntityRuleTransfer;
use Generated\Shared\Transfer\AclEntitySegmentCriteriaTransfer;
use Generated\Shared\Transfer\AclEntitySegmentRequestTransfer;
use Generated\Shared\Transfer\AclRoleCriteriaTransfer;
use Generated\Shared\Transfer\MerchantProductTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\RolesTransfer;
use Generated\Shared\Transfer\RoleTransfer;
use Generated\Shared\Transfer\UserTransfer;
use Orm\Zed\Merchant\Persistence\SpyMerchant;
use Orm\Zed\Merchant\Persistence\SpyMerchantQuery;
use Orm\Zed\MerchantCategory\Persistence\SpyMerchantCategory;
use Orm\Zed\MerchantProduct\Persistence\SpyMerchantProductAbstract;
use Orm\Zed\Product\Persistence\SpyProduct;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImage;
use Orm\Zed\ProductImage\Persistence\SpyProductImageQuery;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSet;
use Orm\Zed\ProductImage\Persistence\SpyProductImageSetToProductImage;
use Orm\Zed\ProductOffer\Persistence\SpyProductOffer;
use Orm\Zed\ProductOffer\Persistence\SpyProductOfferQuery;
use PyzTest\Zed\AclEntity\Plugin\AclEntityMetadataConfigExpanderPluginMock;
use Spryker\Shared\AclEntity\AclEntityConstants;
use Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToAclFacadeBridge;
use Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToAclFacadeBridgeInterface;
use Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToUserFacadeBridge;
use Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToUserFacadeBridgeInterface;
use Spryker\Zed\AclEntity\Persistence\AclEntityPersistenceFactory;
use Spryker\Zed\AclEntity\Persistence\AclEntityRepository;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclModelDirector;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclModelDirectorInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclQueryDirector;
use Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclQueryDirectorInterface;
use Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclEntityRuleProvider;
use Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclRoleProvider;
use Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclRoleProviderInterface;
use Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityMetadataConfigExpanderPluginInterface;
use Spryker\Zed\Kernel\AbstractBundleConfig;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class AclQueryDirectorTester extends Actor
{
    use _generated\AclQueryDirectorTesterActions;

    /**
     * @var string
     */
    public const ACL_ENTITY_SEGMENT_1_NAME = 'segment 1';

    /**
     * @var string
     */
    public const ACL_ENTITY_SEGMENT_2_NAME = 'segment 2';

    /**
     * @var string
     */
    public const ACL_ENTITY_SEGMENT_1_REFERENCE = 'ref_segment_1';

    /**
     * @var string
     */
    public const ACL_ENTITY_SEGMENT_2_REFERENCE = 'ref_segment_2';

    /**
     * @var string
     */
    public const ACL_ROLE_1_NAME = 'role 1';

    /**
     * @var string
     */
    public const ACL_ROLE_2_NAME = 'role 2';

    /**
     * @var string
     */
    public const ACL_ROLE_3_NAME = 'role 3';

    /**
     * @var string
     *
     * @see \SprykerTest\Zed\Category\PageObject\Category::CATEGORY_A
     */
    public const CATEGORY_1_KEY = 'category-a';

    /**
     * @var string
     *
     * @see \SprykerTest\Zed\Category\PageObject\Category::CATEGORY_B
     */
    public const CATEGORY_2_KEY = 'category-b';

    /**
     * @return void
     */
    public function deleteTestData(): void
    {
        $this->deleteRoles(
            (new AclRoleCriteriaTransfer())->setNames(
                [static::ACL_ROLE_1_NAME, static::ACL_ROLE_2_NAME, static::ACL_ROLE_3_NAME],
            ),
        );

        $this->deleteAclEntitySegments(
            (new AclEntitySegmentCriteriaTransfer())
                ->addReference(static::ACL_ENTITY_SEGMENT_1_REFERENCE)
                ->addReference(static::ACL_ENTITY_SEGMENT_2_REFERENCE),
        );
    }

    /**
     * @return \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer
     */
    public function createProductImageProductCompositeEntityMetadataHierarchy(): AclEntityMetadataCollectionTransfer
    {
        // SpyProductImage -> SpyProductImageSet -> SpyProduct
        $aclEntityMetadataCollectionTransfer = new AclEntityMetadataCollectionTransfer();
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductImage::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductImage::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProductImageSet::class)
                        ->setConnection(
                            (new AclEntityParentConnectionMetadataTransfer())
                                ->setPivotEntityName(SpyProductImageSetToProductImage::class)
                                ->setReference('fk_product_image')
                                ->setReferencedColumn('fk_product_image_set'),
                        ),
                )
                ->setIsSubEntity(true),
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductImageSet::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductImageSet::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyProduct::class),
                )
                ->setIsSubEntity(true),
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProduct::class,
            (new AclEntityMetadataTransfer())->setEntityName(SpyProduct::class),
        );

        return $aclEntityMetadataCollectionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer
     */
    public function createProductAbstractMerchantMetadataHierarchy(): AclEntityMetadataCollectionTransfer
    {
        // SpyProductAbstract -> SpyMerchantProductAbstract -> SpyMerchant
        $aclEntityMetadataCollectionTransfer = new AclEntityMetadataCollectionTransfer();
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductAbstract::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductAbstract::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchantProductAbstract::class),
                ),
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchantProductAbstract::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantProductAbstract::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class),
                ),
        );

        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchant::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchant::class)
                ->setHasSegmentTable(true),
        );

        return $aclEntityMetadataCollectionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer
     */
    public function createProductOfferMetadataHierarchy(): AclEntityMetadataCollectionTransfer
    {
        // SpyProductOffer -> SpyMerchant
        $aclEntityMetadataCollectionTransfer = new AclEntityMetadataCollectionTransfer();
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductOffer::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductOffer::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                        ->setConnection(
                            (new AclEntityParentConnectionMetadataTransfer())
                                ->setReference('merchant_reference')
                                ->setReferencedColumn('merchant_reference'),
                        ),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchant::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchant::class),
        );

        return $aclEntityMetadataCollectionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer
     */
    public function createProductOfferMerchantHierarchy(): AclEntityMetadataCollectionTransfer
    {
        $aclEntityMetadataCollectionTransfer = new AclEntityMetadataCollectionTransfer();
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyProductOffer::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyProductOffer::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class)
                        ->setConnection(
                            (new AclEntityParentConnectionMetadataTransfer())
                                ->setReference('merchant_reference')
                                ->setReferencedColumn('merchant_reference'),
                        ),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchant::class,
            (new AclEntityMetadataTransfer())->setEntityName(SpyMerchant::class),
        );

        return $aclEntityMetadataCollectionTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer
     */
    public function createMerchantCategoryMerchantHierarchy(): AclEntityMetadataCollectionTransfer
    {
        $aclEntityMetadataCollectionTransfer = new AclEntityMetadataCollectionTransfer();
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchantCategory::class,
            (new AclEntityMetadataTransfer())
                ->setEntityName(SpyMerchantCategory::class)
                ->setParent(
                    (new AclEntityParentMetadataTransfer())
                        ->setEntityName(SpyMerchant::class),
                ),
        );
        $aclEntityMetadataCollectionTransfer->addAclEntityMetadata(
            SpyMerchant::class,
            (new AclEntityMetadataTransfer())->setEntityName(SpyMerchant::class),
        );

        return $aclEntityMetadataCollectionTransfer;
    }

    /**
     * @return \Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityMetadataConfigExpanderPluginInterface
     */
    public function getProductAbstractMerchantAclEntityMetadataConfigExpanderPlugin(): AclEntityMetadataConfigExpanderPluginInterface
    {
        return new AclEntityMetadataConfigExpanderPluginMock();
    }

    /**
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     * @param \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer|null $aclEntityMetadataCollectionTransfer
     * @param \Spryker\Zed\Kernel\AbstractBundleConfig|null $bundleConfig
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclQueryDirectorInterface
     */
    public function createAclQueryDirector(
        RolesTransfer $rolesTransfer,
        ?AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer = null,
        ?AbstractBundleConfig $bundleConfig = null
    ): AclQueryDirectorInterface {
        $factory = new AclEntityPersistenceFactory();
        if ($bundleConfig) {
            $factory->setConfig($bundleConfig);
        }
        $aclEntityMetadataCollectionTransfer = $aclEntityMetadataCollectionTransfer ?: new AclEntityMetadataCollectionTransfer();
        $aclEntityConfigTransfer = new AclEntityMetadataConfigTransfer();
        $aclEntityConfigTransfer->setAclEntityMetadataCollection($aclEntityMetadataCollectionTransfer);

        return new AclQueryDirector(
            $factory->createAclJoinDirector($aclEntityConfigTransfer),
            new AclEntityRuleProvider($this->getAclRoleProviderMock($rolesTransfer), new AclEntityRepository()),
            $factory->createAclQueryScopeResolver($aclEntityConfigTransfer),
            $factory->createAclEntityMetadataReader($aclEntityConfigTransfer),
            $factory->createAclQueryExpander($aclEntityConfigTransfer),
            $factory->createAclEntityQueryMerger(),
            $this->createAclModelDirector($rolesTransfer, $aclEntityMetadataCollectionTransfer, $bundleConfig),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     * @param \Generated\Shared\Transfer\AclEntityMetadataCollectionTransfer|null $aclEntityMetadataCollectionTransfer
     * @param \Spryker\Zed\Kernel\AbstractBundleConfig|null $bundleConfig
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\AclDirector\AclModelDirectorInterface
     */
    public function createAclModelDirector(
        RolesTransfer $rolesTransfer,
        ?AclEntityMetadataCollectionTransfer $aclEntityMetadataCollectionTransfer = null,
        ?AbstractBundleConfig $bundleConfig = null
    ): AclModelDirectorInterface {
        $factory = new AclEntityPersistenceFactory();
        if ($bundleConfig) {
            $factory->setConfig($bundleConfig);
        }
        $aclEntityMetadataCollectionTransfer = $aclEntityMetadataCollectionTransfer ?: new AclEntityMetadataCollectionTransfer();
        $aclEntityMetadataConfigTransfer = new AclEntityMetadataConfigTransfer();
        $aclEntityMetadataConfigTransfer->setAclEntityMetadataCollection($aclEntityMetadataCollectionTransfer);

        return new AclModelDirector(
            $factory->createAclEntityMetadataReader($aclEntityMetadataConfigTransfer),
            new AclEntityRuleProvider($this->getAclRoleProviderMock($rolesTransfer), new AclEntityRepository()),
            $factory->createAclModelScopeResolver($aclEntityMetadataConfigTransfer),
            $factory->createAclRelationReader($aclEntityMetadataConfigTransfer),
            $factory->getPropelServiceContainer(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return void
     */
    public function createMerchantCategoryRules(
        MerchantTransfer $merchantTransfer,
        RoleTransfer $roleTransfer
    ): void {
        $aclEntitySegmentMerchant = $this->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::NAME => static::ACL_ENTITY_SEGMENT_1_NAME,
                AclEntitySegmentRequestTransfer::REFERENCE => static::ACL_ENTITY_SEGMENT_1_REFERENCE,
                AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantTransfer->getIdMerchantOrFail()],
            ],
        );
        $this->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::ENTITY => SpyMerchantCategory::class,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $this->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $aclEntitySegmentMerchant->getIdAclEntitySegmentOrFail(),
            ],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return void
     */
    public function createProductOfferRules(
        MerchantTransfer $merchantTransfer,
        RoleTransfer $roleTransfer
    ): void {
        $aclEntitySegmentMerchant = $this->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::NAME => static::ACL_ENTITY_SEGMENT_1_NAME,
                AclEntitySegmentRequestTransfer::REFERENCE => static::ACL_ENTITY_SEGMENT_1_REFERENCE,
                AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantTransfer->getIdMerchantOrFail()],
            ],
        );
        $this->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::ENTITY => SpyProductOffer::class,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $this->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $aclEntitySegmentMerchant->getIdAclEntitySegmentOrFail(),
            ],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return void
     */
    public function createProductOfferManagerRules(MerchantTransfer $merchantTransfer, RoleTransfer $roleTransfer): void
    {
        $aclEntitySegmentMerchantTransfer = $this->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::NAME => static::ACL_ENTITY_SEGMENT_1_NAME,
                AclEntitySegmentRequestTransfer::REFERENCE => static::ACL_ENTITY_SEGMENT_1_REFERENCE,
                AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantTransfer->getIdMerchantOrFail()],
            ],
        );
        $this->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::ENTITY => SpyProductOffer::class,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_CRUD,
            ],
        );
        $this->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $aclEntitySegmentMerchantTransfer
                    ->getIdAclEntitySegmentOrFail(),
            ],
        );
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\RoleTransfer $roleTransfer
     *
     * @return void
     */
    public function createProductOfferViewerRules(MerchantTransfer $merchantTransfer, RoleTransfer $roleTransfer): void
    {
        $aclEntitySegmentMerchantTransfer = $this->haveAclEntitySegment(
            [
                AclEntitySegmentRequestTransfer::NAME => static::ACL_ENTITY_SEGMENT_2_NAME,
                AclEntitySegmentRequestTransfer::REFERENCE => static::ACL_ENTITY_SEGMENT_2_REFERENCE,
                AclEntitySegmentRequestTransfer::ENTITY => SpyMerchant::class,
                AclEntitySegmentRequestTransfer::ENTITY_IDS => [$merchantTransfer->getIdMerchantOrFail()],
            ],
        );

        $this->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::ENTITY => SpyProductOffer::class,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_INHERITED,
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
            ],
        );
        $this->haveAclEntityRule(
            [
                AclEntityRuleTransfer::ID_ACL_ROLE => $roleTransfer->getIdAclRoleOrFail(),
                AclEntityRuleTransfer::ENTITY => SpyMerchant::class,
                AclEntityRuleTransfer::SCOPE => AclEntityConstants::SCOPE_SEGMENT,
                AclEntityRuleTransfer::PERMISSION_MASK => AclEntityConstants::OPERATION_MASK_READ,
                AclEntityRuleTransfer::ID_ACL_ENTITY_SEGMENT => $aclEntitySegmentMerchantTransfer
                    ->getIdAclEntitySegmentOrFail(),
            ],
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToUserFacadeBridgeInterface
     */
    protected function getUserFacadeMock(): AclEntityToUserFacadeBridgeInterface
    {
        $this->mockFacadeMethod('hasCurrentUser', true, 'User');
        $this->mockFacadeMethod('getCurrentUser', (new UserTransfer())->setIdUser(1), 'User');

        /** @var \Spryker\Zed\User\Business\UserFacadeInterface $userFacade */
        $userFacade = $this->getFacade('User');

        return new AclEntityToUserFacadeBridge($userFacade);
    }

    /**
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Spryker\Zed\AclEntity\Dependency\Facade\AclEntityToAclFacadeBridgeInterface
     */
    protected function getAclFacadeMock(RolesTransfer $rolesTransfer): AclEntityToAclFacadeBridgeInterface
    {
        /** @var \Spryker\Zed\Acl\Business\AclFacadeInterface $aclFacade */
        $aclFacade = $this->mockFacadeMethod('getUserRoles', $rolesTransfer, 'Acl');

        return new AclEntityToAclFacadeBridge($aclFacade);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract|null
     */
    public function findProductAbstractByIdProductAbstract(int $idProductAbstract): ?SpyProductAbstract
    {
        return SpyProductAbstractQuery::create()->filterByPrimaryKey($idProductAbstract)->findOne();
    }

    /**
     * @param int $idProductImage
     *
     * @return \Orm\Zed\ProductImage\Persistence\SpyProductImage|null
     */
    public function findProductImageByIdProductImage(int $idProductImage): ?SpyProductImage
    {
        return SpyProductImageQuery::create()->filterByPrimaryKey($idProductImage)->findOne();
    }

    /**
     * @param int $idMerchant
     *
     * @return \Orm\Zed\Merchant\Persistence\SpyMerchant|null
     */
    public function findMerchantByIdMerchant(int $idMerchant): ?SpyMerchant
    {
        return SpyMerchantQuery::create()->filterByPrimaryKey($idMerchant)->findOne();
    }

    /**
     * @param int $idProduct
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct|null
     */
    public function findProductConcreteByIdProduct(int $idProduct): ?SpyProduct
    {
        return SpyProductQuery::create()->filterByPrimaryKey($idProduct)->findOne();
    }

    /**
     * @param int $idProductOffer
     *
     * @return \Orm\Zed\ProductOffer\Persistence\SpyProductOffer|null
     */
    public function findProductOfferByIdProductOffer(int $idProductOffer): ?SpyProductOffer
    {
        return SpyProductOfferQuery::create()->filterByPrimaryKey($idProductOffer)->findOne();
    }

    /**
     * @return \Generated\Shared\Transfer\MerchantProductTransfer
     */
    public function createMerchantProduct(): MerchantProductTransfer
    {
        $merchantTransfer = $this->haveMerchant();
        $productConcreteTransfer = $this->haveProduct();

        return $this->haveMerchantProduct(
            [
                MerchantProductTransfer::ID_MERCHANT => $merchantTransfer->getIdMerchantOrFail(),
                MerchantProductTransfer::ID_PRODUCT_ABSTRACT => $productConcreteTransfer->getFkProductAbstractOrFail(),
            ],
        );
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function purify(string $string): string
    {
        return str_replace(['`', '"'], '', strtolower($string));
    }

    /**
     * @param \Generated\Shared\Transfer\RolesTransfer $rolesTransfer
     *
     * @return \Spryker\Zed\AclEntity\Persistence\Propel\Provider\AclRoleProviderInterface
     */
    protected function getAclRoleProviderMock(RolesTransfer $rolesTransfer): AclRoleProviderInterface
    {
        return Stub::make(
            AclRoleProvider::class,
            [
                'userFacade' => $this->getUserFacadeMock(),
                'aclFacade' => $this->getAclFacadeMock($rolesTransfer),
                'cache' => null,
            ],
        );
    }
}
