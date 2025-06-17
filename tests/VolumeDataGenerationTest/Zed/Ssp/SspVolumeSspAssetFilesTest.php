<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace VolumeDataGenerationTest\Zed\Ssp;

use Codeception\Test\Unit;
use Orm\Zed\FileManager\Persistence\Map\SpyFileTableMap;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;
use Orm\Zed\SelfServicePortal\Persistence\Map\SpySspAssetTableMap;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetFile;
use Orm\Zed\SelfServicePortal\Persistence\SpySspAssetQuery;

/**
 * Attaches files to ssp assets.
 *
 * Recommendation: run it after Ssp-assets and Files generation.
 *
 * @group VolumeDataGenerationTest
 * @group Ssp
 * @group SspVolumeFilesTest
 * @group SspAssetFiles
 */
class SspVolumeSspAssetFilesTest extends Unit
{
    /**
     * @var int
     */
    /**
     * Amount of files attached for each ssp asset.
     *
     * @var
     */
    protected const FILES_PER_SSP_ASSET = 15;

    /**
     * {ssp_asset amount} % (self::BATCH_SIZE * self::FILES_PER_SSP_ASSET) should be 0
     * In this case all ssp assets will have a proper amount of files.
     *
     * @var string
     */
    protected const BATCH_SIZE = 1000;

    /**
     * @return void
     */
    public function testAttachFilesToAssets(): void
    {
        $iteration = 0;
        if (isset($_SERVER['ITERATION'])) { // phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable
            $iteration = (int)$_SERVER['ITERATION']; // phpcs:ignore SlevomatCodingStandard.Variables.DisallowSuperGlobalVariable
        }

        $offset = $iteration * self::BATCH_SIZE;

        $fileIds = [];

        $fileOffset = 0;

        $sspAssetIds = SpySspAssetQuery::create()
            ->select([SpySspAssetTableMap::COL_ID_SSP_ASSET])
            ->offset($offset)
            ->limit(self::BATCH_SIZE)
            ->find()
            ->getData();

        if (!$sspAssetIds) {
            $this->assertTrue(false, SspTester::ALL_ENTITIES_GENERATED_MESSAGE);

            return;
        }

        foreach ($sspAssetIds as $sspAssetId) {
            $sspAssetFileIds = array_splice($fileIds, 0, self::FILES_PER_SSP_ASSET);

            if (!$sspAssetFileIds) {
                $fileIds = SpyFileQuery::create()
                    ->select([SpyFileTableMap::COL_ID_FILE])
                    ->offset($fileOffset * self::BATCH_SIZE * self::FILES_PER_SSP_ASSET)
                    ->limit(self::BATCH_SIZE * self::FILES_PER_SSP_ASSET)
                    ->find()
                    ->getData();

                if (!$fileIds) {
                    $fileOffset = 0;

                    $fileIds = SpyFileQuery::create()
                        ->select([SpyFileTableMap::COL_ID_FILE])
                        ->offset($fileOffset * self::BATCH_SIZE * self::FILES_PER_SSP_ASSET)
                        ->limit(self::BATCH_SIZE * self::FILES_PER_SSP_ASSET)
                        ->find()
                        ->getData();
                }

                $sspAssetFileIds = array_splice($fileIds, 0, self::FILES_PER_SSP_ASSET);
            }

            foreach ($sspAssetFileIds as $assetFileId) {
                (new SpySspAssetFile())
                    ->setFkSspAsset($sspAssetId)
                    ->setFkFile($assetFileId)
                    ->save();
            }

            $fileOffset++;
        }

        $totalSspAssetCount = SpySspAssetQuery::create()->count();

        $this->assertTrue(
            false,
            sprintf('%s %s', SspTester::GENERATION_RESULT_TEXT, sprintf('files for %d ssp assets are generated. Left to generate files for %d ssp assets', count($sspAssetIds), $totalSspAssetCount - $offset - count($sspAssetIds))),
        );
    }
}
