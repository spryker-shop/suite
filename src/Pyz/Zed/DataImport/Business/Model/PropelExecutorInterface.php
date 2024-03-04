<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model;

interface PropelExecutorInterface
{
    /**
     * @param string $sql
     * @param array<mixed> $parameters
     * @param bool $fetch
     *
     * @return array<mixed>|null
     */
    public function execute(string $sql, array $parameters, bool $fetch = true): ?array;
}
