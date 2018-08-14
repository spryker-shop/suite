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
    protected const READER_OPEN_MODE = 'r';
    protected const WRITER_OPEN_MODE = 'w+';
    protected const DELIMITER = ',';

    /**
     * @param string $path
     * @param int $offset
     * @param int $index
     *
     * @return array
     */
    public function readColumn(string $path, int $offset = 0, int $index = 2): array
    {
        $reader = $this->getReaderFromPath($path);
        $reader->setHeaderOffset($offset);

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
        $writer = $this->getWriterFromPath($path);
        $writer->setDelimiter(static::DELIMITER);
        $writer->insertOne($header);
        $writer->insertAll($rows);
    }

    /**
     * @param string $path
     *
     * @return \League\Csv\Reader
     */
    protected function getReaderFromPath($path)
    {
        return Reader::createFromPath($path, static::READER_OPEN_MODE);
    }

    /**
     * @param string $path
     *
     * @return \League\Csv\Writer
     */
    protected function getWriterFromPath($path)
    {
        return Writer::createFromPath($path, static::WRITER_OPEN_MODE);
    }
}
