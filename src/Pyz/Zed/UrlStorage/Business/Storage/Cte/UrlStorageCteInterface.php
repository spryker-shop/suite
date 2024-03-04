<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\UrlStorage\Business\Storage\Cte;

interface UrlStorageCteInterface
{
    /**
     * @param array<mixed> $data
     *
     * @return array<string>
     */
    public function buildParams(array $data): array;

    /**
     * @return string
     */
    public function getSql(): string;

    /**
     * @return array<string>
     */
    public function getCompatibleEngines(): array;
}
