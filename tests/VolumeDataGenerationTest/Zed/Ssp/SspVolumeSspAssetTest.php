<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace VolumeDataGenerationTest\Zed\Ssp;

use Codeception\Test\Unit;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\Map\SpyCompanyBusinessUnitTableMap;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAsset;
use Orm\Zed\SspAssetManagement\Persistence\SpySspAssetToCompanyBusinessUnit;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

/**
 * Generates Ssp assets for each company.
 *
 * Recommendation: run it after companies generation and before ssp inquiries generation.
 *
 * @group VolumeDataGenerationTest
 * @group Ssp
 * @group SspVolumeSspAssetTest
 * @group SspAssets
 */
class SspVolumeSspAssetTest extends Unit
{
    /**
     * @var int
     */
    /**
     * Amount of ssp assets which will be generated for each company.
     */
    protected const SSP_ASSET_PER_COMPANY = 15;

    /**
     * @var int
     */
    /**
     * Companyes are fetched by baches for avoiding memory issues.
     *
     * @var int
     */
    protected const COMPANY_BATCH_SIZE = 1000;

    /**
     * @var \VolumeDataGenerationTest\Zed\Ssp\SspTester
     */
    protected SspTester $tester;

    /**
     * @return void
     */
    public function testGenerateAssets(): void
    {
        $iteration = 0;
        if (isset($_SERVER['ITERATION'])) { // phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable
            $iteration = (int)$_SERVER['ITERATION']; // phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable
        }

        $companyOffset = $iteration * self::COMPANY_BATCH_SIZE;

        $businessUnits = SpyCompanyBusinessUnitQuery::create()
            ->useCompanyUserExistsQuery()
            ->endUse()
            ->addJoin(
                SpyCompanyBusinessUnitTableMap::COL_FK_COMPANY,
                SpyCompanyTableMap::COL_ID_COMPANY,
                Criteria::INNER_JOIN,
            )
            ->addGroupByColumn(SpyCompanyTableMap::COL_ID_COMPANY)
            ->select([SpyCompanyBusinessUnitTableMap::COL_ID_COMPANY_BUSINESS_UNIT])
            ->offset($companyOffset)
            ->limit(self::COMPANY_BATCH_SIZE)
            ->find()
            ->getData();

        if (!$businessUnits) {
            $this->assertTrue(false, SspTester::ALL_ENTITIES_GENERATED_MESSAGE);

            return;
        }

        foreach ($businessUnits as $businessUnitId) {
            for ($i = 0; $i < self::SSP_ASSET_PER_COMPANY; $i++) {
                $this->generateSingleAsset($businessUnitId);
            }
        }

        $totalCompanyCount = SpyCompanyBusinessUnitQuery::create()
            ->useCompanyUserExistsQuery()
            ->endUse()
            ->addJoin(
                SpyCompanyBusinessUnitTableMap::COL_FK_COMPANY,
                SpyCompanyTableMap::COL_ID_COMPANY,
                Criteria::INNER_JOIN,
            )->count();

        $this->assertTrue(false, sprintf('%s %s', SspTester::GENERATION_RESULT_TEXT, sprintf('ssp assets generated for %d companies. Left to generate ssp assets for %d companies.', count(($businessUnits)), $totalCompanyCount - count($businessUnits) - $companyOffset)));
    }

    /**
     * @param int $businessUnitId
     *
     * @return void
     */
    protected function generateSingleAsset(int $businessUnitId): void
    {
        $sspAssetEntity = (new SpySspAsset())
            ->setReference(Uuid::uuid4())
            ->setName('Test name')
            ->setSerialNumber(Uuid::uuid1()->toString())
            ->setStatus('pending')
            ->setFkCompanyBusinessUnit($businessUnitId);

        $sspAssetEntity->save();

        $sspAssetId = $sspAssetEntity->getIdSspAsset();

        (new SpySspAssetToCompanyBusinessUnit())
            ->setFkSspAsset($sspAssetId)
            ->setFkCompanyBusinessUnit($businessUnitId)
            ->save();
    }
}
