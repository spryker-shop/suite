<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Propel\Business\CTE;

use Propel\Runtime\Propel;
use Pyz\Zed\Propel\PropelConfig;

class CteSupportChecker implements CteSupportCheckerInterface
{
    protected const MARIA_DB_CTE_MINIMUM_VERSION = '10.5.0';

    /**
     * @var \Pyz\Zed\Propel\PropelConfig
     */
    protected $propelConfig;

    /**
     * @param \Pyz\Zed\Propel\PropelConfig $propelConfig
     */
    public function __construct(PropelConfig $propelConfig)
    {
        $this->propelConfig = $propelConfig;
    }

    /**
     * @return bool
     */
    public function checkCteSupport(): bool
    {
        $engine = $this->propelConfig->getCurrentDatabaseEngine();

        if ($engine === PropelConfig::DB_ENGINE_PGSQL) {
            return true;
        }

        if ($engine === PropelConfig::DB_ENGINE_MYSQL) {
            return $this->checkMariaDbCteSupport();
        }

        return false;
    }

    /**
     * @return bool
     */
    protected function checkMariaDbCteSupport(): bool
    {
        $engineVersion = $this->getEngineVersion();
        preg_match('/^([\d\.]+)-MariaDB/', $engineVersion, $matches);
        $versionString = $matches[1] ?? null;

        if ($versionString === null) {
            return false;
        }

        return version_compare(static::MARIA_DB_CTE_MINIMUM_VERSION, $versionString) < 1;
    }

    /**
     * @return string
     */
    protected function getEngineVersion(): string
    {
        $stmt = Propel::getConnection()->prepare('SELECT VERSION() AS `version`', []);
        $stmt->execute();

        return (string)$stmt->fetchColumn();
    }
}
