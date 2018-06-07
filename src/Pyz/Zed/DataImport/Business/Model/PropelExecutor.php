<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\Model;

use Propel\Runtime\Propel;

class PropelExecutor
{
    /**
     * @param string $sql
     * @param array $parameters
     *
     * @return array|null
     */
    public static function execute(string $sql, array $parameters)
    {
        $con = Propel::getConnection();
        $stmt = $con->prepare($sql);
        $stmt->execute($parameters);

        $result = $stmt->fetchAll();

        return $result;
    }
}
