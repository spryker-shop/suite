<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace VolumeDataGenerationTest\Zed\Ssp;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\FileBuilder;
use Generated\Shared\DataBuilder\FileInfoBuilder;
use Orm\Zed\FileManager\Persistence\SpyFile;
use Orm\Zed\FileManager\Persistence\SpyFileInfo;
use Orm\Zed\FileManager\Persistence\SpyFileQuery;

/**
 * Generates files entities.
 *
 * @group VolumeDataGenerationTest
 * @group Ssp
 * @group SspVolumeFilesTest
 * @group Files
 */
class SspVolumeFilesTest extends Unit
{
    /**
     * Total amount of generated files after test run.
     *
     * @var int
     */
    protected const FILE_COUNT = 225000;

    /**
     * @var string
     */
    /**
     * For avoiding storing of {self::FILE_COUNT} files in AWS S3 bucket
     * one file with predefined name is stored and saved to DB for {self::FILE_COUNT} entities.
     *
     * @var string
     */
    protected const STORAGE_FILE_NAME = '1-v.1.jpg';

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
    public function testGenerateFiles(): void
    {
        $existingFileCount = SpyFileQuery::create()->count();

        if ($existingFileCount >= self::FILE_COUNT) {
            $this->assertTrue(false, SspTester::ALL_ENTITIES_GENERATED_MESSAGE);

            return;
        }

        $fileTransfer = (new FileBuilder([]))->build();
        $fileInfoTransfer = (new FileInfoBuilder([
            'type' => 'image/jpeg',
            'extension' => 'jpg',
            'size' => 10000000,
            'storageName' => 'files',
        ]))->build();

        for ($i = 0; $i < min(self::FILE_COUNT - $existingFileCount, self::BATCH_SIZE); $i++) { // phpcs:ignore Generic.CodeAnalysis.ForLoopWithTestFunctionCall
            $fileEntity = (new SpyFile())->fromArray($fileTransfer->toArray());
            $fileEntity->save();
            (new SpyFileInfo())
                ->fromArray($fileInfoTransfer->toArray())
                ->setFkFile($fileEntity->getIdFile())
                ->save();
        }

        $this->assertTrue(true, sprintf('%s %s', SspTester::GENERATION_RESULT_TEXT, sprintf('%d files generated. Left to generate: %d', min(self::FILE_COUNT - $existingFileCount, self::BATCH_SIZE), self::FILE_COUNT - $existingFileCount - min(self::FILE_COUNT - $existingFileCount, self::BATCH_SIZE))));
    }
}
