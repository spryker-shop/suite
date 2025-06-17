<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace VolumeDataGenerationTest\Zed\Ssp;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\CompanyUser\Persistence\Map\SpyCompanyUserTableMap;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquiry;
use Orm\Zed\SelfServicePortal\Persistence\SpySspInquirySspAsset;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineItemStateQuery;
use Orm\Zed\StateMachine\Persistence\SpyStateMachineProcessQuery;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Ramsey\Uuid\Uuid;

/**
 * Generates inquiries for each Ssp asset.
 *
 * Recommendation: run it after ssp assets generation.
 *
 * @group VolumeDataGenerationTest
 * @group Ssp
 * @group SspVolumeSspInquiryTest
 * @group SspAssetInquiries
 */
class SspVolumeSspAssetInquiryTest extends Unit
{
    /**
     * @var int
     */
    /**
     * Amount of ssp inquiries which will be generated for each ssp asset.
     *
     * @var string
     */
    protected const SSP_INQUIRY_PER_ASSET = 15;

    /**
     * @var int
     */
    /**
     * Ssp assets and company users are fetched by batches for avoiding memory issues.
     *
     * @var int
     */
    protected const BATCH_SIZE = 1000;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected StoreTransfer $storeTransfer;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->storeTransfer = (new StoreTransfer())->setName('DE')->setIdStore(
            SpyStoreQuery::create()->findOneByName('DE')->getIdStore(),
        );
    }

    /**
     * @return void
     */
    public function testGenerateSspAssetInquiries(): void
    {
        $iteration = 0;
        if (isset($_SERVER['ITERATION'])) { // phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable
            $iteration = (int)$_SERVER['ITERATION']; // phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable
        }

        $assetOffset = $iteration * self::BATCH_SIZE;

        $sspAssetData = SpySspAssetQuery::create()
            ->useSpyCompanyBusinessUnitQuery()
                ->joinCompanyUser()
            ->endUse()
            ->select([SpySspAssetTableMap::COL_ID_SSP_ASSET, SpyCompanyUserTableMap::COL_ID_COMPANY_USER])
            ->offset($assetOffset)
            ->limit(self::BATCH_SIZE)
            ->find()
            ->getData();

        if (!$sspAssetData) {
            $this->assertTrue(false, SspTester::ALL_ENTITIES_GENERATED_MESSAGE);
        }

        $stateMachineProcessEntity = SpyStateMachineProcessQuery::create()->findOneByStateMachineName('SspInquiry');
        $stateMachineItemStateId = SpyStateMachineItemStateQuery::create()->findOneByFkStateMachineProcess($stateMachineProcessEntity->getIdStateMachineProcess())->getIdStateMachineItemState();

        foreach ($sspAssetData as $sspAssetDatum) {
            for ($i = 0; $i < self::SSP_INQUIRY_PER_ASSET; $i++) {
                $sspInquiryEntity = (new SpySspInquiry())
                    ->setFkCompanyUser($sspAssetDatum[SpyCompanyUserTableMap::COL_ID_COMPANY_USER])
                    ->setFkStore($this->storeTransfer->getIdStore())
                    ->setType('ssp_asset')
                    ->setSubject('Test subject')
                    ->setDescription('Test description')
                    ->setFkStateMachineItemState($stateMachineItemStateId)
                    ->setReference(Uuid::uuid4());

                $sspInquiryEntity->save();

                (new SpySspInquirySspAsset())
                    ->setFkSspInquiry($sspInquiryEntity->getIdSspInquiry())
                    ->setFkSspAsset($sspAssetDatum[SpySspAssetTableMap::COL_ID_SSP_ASSET])
                    ->save();
            }
        }

        $this->assertTrue(
            false,
            sprintf('%s %s', SspTester::GENERATION_RESULT_TEXT, sprintf('inquiries were generated for %d ssp assets. Left to generate inquiries for %d ssp assets.', count($sspAssetData), SpySspAssetQuery::create()->count() - $assetOffset - count($sspAssetData))),
        );
    }
}
