<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Scheduler\Business;

use Pyz\Zed\Scheduler\Business\PhpScheduleReader\Mapper\PhpScheduleMapper;
use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper\PhpScheduleMapperInterface;
use Spryker\Zed\Scheduler\Business\SchedulerBusinessFactory as SprykerSchedulerBusinessFactory;

/**
 * @method \Spryker\Zed\Scheduler\SchedulerConfig getConfig()
 */
class SchedulerBusinessFactory extends SprykerSchedulerBusinessFactory
{
    /**
     * @deprecated Will be removed once multi-tenancy support for the Docker SDK is disabled.
     *
     * @return \Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper\PhpScheduleMapperInterface
     */
    public function createPhpSchedulerMapper(): PhpScheduleMapperInterface
    {
        return new PhpScheduleMapper(
            $this->createJobsFilter(),
            $this->getConfig(),
        );
    }
}
