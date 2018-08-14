<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DemoDataGenerator\Business\Model\FileManager;

interface FileManagerInterface
{
    /**
     * @param string $path
     * @param int $offset
     * @param int $index
     *
     * @return array
     */
    public function readColumn(string $path, int $offset = 0, int $index = 2): array;

    /**
     * @param string $path
     * @param array $header
     * @param array $rows
     *
     * @return void
     */
    public function write(string $path, array $header, array $rows): void;
}
