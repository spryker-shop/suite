<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\FileManager;

use League\Csv\Reader;
use League\Csv\Writer;

class FileManager implements FileManagerInterface
{
    /**
     * @param string $path
     * @param int $offset
     * @param int $index
     *
     * @return array
     */
    public function readColumn(string $path, int $offset = 1, int $index = 2): array
    {
        $reader = Reader::createFromPath($path, 'r');
        $reader->setOffset($offset);

        $content = [];

        $records = $reader->fetchColumn($index);

        foreach ($records as $record) {
            $content[] = $record;
        }

        return $content;
    }

    /**
     * @param string $path
     * @param array $header
     * @param array $rows
     *
     * @return void
     */
    public function write(string $path, array $header, array $rows): void
    {
        $writer = Writer::createFromPath($path, 'w+');
        $writer->setDelimiter(',');
        $writer->insertOne($header);
        $writer->insertAll($rows);
    }
}
