<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model;

/**
 * @deprecated Will be removed after MariaDB 10.4 support is dropped.
 */
trait PropelMariaDBVersionConstraintTrait
{
    /**
     * @param \Pyz\Zed\DataImport\Business\Model\PropelExecutorInterface $propelExecutor
     *
     * @throws \Pyz\Zed\DataImport\Business\Model\PropelMariaDBVersionConstraintException
     *
     * @return bool
     */
    protected function checkIsMariaDBSupportsBulkImport(PropelExecutorInterface $propelExecutor): bool
    {
        $version = $propelExecutor->execute('SELECT VERSION() AS `version`', []);

        $version = explode('-', current(current($version)));

        if ($version[1] !== 'MariaDB') {
            throw new PropelMariaDBVersionConstraintException(
                'Current database engine does not support bulk import.' .
                ' Bulk import is supported on MariaDB server version >= 10.5.'
            );
        }

        if (version_compare($version[0], '10.5') < 0) {
            throw new PropelMariaDBVersionConstraintException(
                'Current version of MariaDB does not support bulk import.' .
                ' Please update your MariaDB server to version >= 10.5.'
            );
        }

        return true;
    }
}
