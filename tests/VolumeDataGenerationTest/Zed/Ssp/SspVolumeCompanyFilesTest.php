<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace VolumeDataGenerationTest\Zed\Ssp;

use Codeception\Test\Unit;
use Orm\Zed\Company\Persistence\Map\SpyCompanyTableMap;
use Orm\Zed\Company\Persistence\SpyCompanyQuery;
use Orm\Zed\FileManager\Persistence\Map\SpyFileTableMap;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\SspFileManagement\Persistence\SpyCompanyFile;

/**
 * Attaches files to companies.
 *
 * Recommendation: run it after companies and files generation.
 *
 * @group VolumeDataGenerationTest
 * @group Ssp
 * @group SspVolumeFilesTest
 * @group CompanyFiles
 */
class SspVolumeCompanyFilesTest extends Unit
{
    /**
     * @var int
     */
    /**
     * Amount of files attached for each company.
     *
     * @var int
     */
    protected const FILES_PER_COMPANY = 250;

    /**
     * {ssp_asset amount} % (self::BATCH_SIZE * self::FILES_PER_SSP_ASSET) should be 0
     * In this case all ssp assets will have a proper amount of files.
     *
     * @var string
     */
    protected const BATCH_SIZE_FILE = 1000;

    /**
     * {company amount} % (self::BATCH_SIZE_COMPANY * self::FILES_PER_COMPANY) should be 0
     * In this case all companies will have a proper amount of files.
     *
     * @var string
     */
    protected const BATCH_SIZE_COMPANY = 900;

    /**
     * @return void
     */
    public function testAttachFilesToCompanies(): void
    {
        $fileIds = [];

        $fileOffset = 0;

        $iteration = 0;
        if (isset($_SERVER['ITERATION'])) { // phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable
            $iteration = (int)$_SERVER['ITERATION']; // phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable
        }

        $offset = $iteration * self::BATCH_SIZE_COMPANY;

        $companyIds = SpyCompanyQuery::create()
            ->useCompanyBusinessUnitExistsQuery()
                ->useCompanyUserExistsQuery()
                ->endUse()
            ->endUse()
            ->select([SpyCompanyTableMap::COL_ID_COMPANY])
            ->offset($offset)
            ->limit(self::BATCH_SIZE_COMPANY)
            ->find()
            ->getData();

        if (!$companyIds) {
            $this->assertTrue(false, SspTester::ALL_ENTITIES_GENERATED_MESSAGE);

            return;
        }

        foreach ($companyIds as $companyId) {
            $companyFileIds = array_splice($fileIds, 0, self::FILES_PER_COMPANY);

            if (!$companyFileIds) {
                $fileIds = SpyFileQuery::create()
                    ->select([SpyFileTableMap::COL_ID_FILE])
                    ->offset($fileOffset * self::BATCH_SIZE_FILE * self::FILES_PER_COMPANY)
                    ->limit(self::BATCH_SIZE_COMPANY * self::FILES_PER_COMPANY)
                    ->find()
                    ->getData();

                if (!$fileIds) {
                    $fileOffset = 0;

                    $fileIds = SpyFileQuery::create()
                        ->select([SpyFileTableMap::COL_ID_FILE])
                        ->offset($fileOffset * self::BATCH_SIZE_COMPANY * self::FILES_PER_COMPANY)
                        ->limit(self::BATCH_SIZE_COMPANY * self::FILES_PER_COMPANY)
                        ->find()
                        ->getData();
                }

                $companyFileIds = array_splice($fileIds, 0, self::FILES_PER_COMPANY);
            }

            foreach ($companyFileIds as $companyFileId) {
                (new SpyCompanyFile())
                    ->setFkCompany($companyId)
                    ->setFkFile($companyFileId)
                    ->save();
            }

            $fileOffset++;
        }

        $totalCompaniesCount = SpyCompanyQuery::create()
            ->useCompanyBusinessUnitExistsQuery()
                ->useCompanyUserExistsQuery()
                ->endUse()
                ->endUse()
            ->count();

        $this->assertTrue(
            false,
            sprintf('%s %s', SspTester::GENERATION_RESULT_TEXT, sprintf('files generated for %d companies. Left to generate for %d companies.', count($companyIds), $totalCompaniesCount - $offset - count($companyIds))),
        );
    }
}
