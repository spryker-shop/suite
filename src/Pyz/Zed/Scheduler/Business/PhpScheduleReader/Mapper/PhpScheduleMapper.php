<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Scheduler\Business\PhpScheduleReader\Mapper;

use Spryker\Zed\Scheduler\Business\PhpScheduleReader\Mapper\PhpScheduleMapper as SprykerPhpScheduleMapper;

/**
 * @deprecated Will be removed once multi-tenancy support for the Docker SDK is disabled.
 */
class PhpScheduleMapper extends SprykerPhpScheduleMapper
{
    /**
     * Docker SDK multi-tenancy setup: Adds the project name to the job name if it is set.
     *
     * @param array<string> $job
     * @param string|null $currentStoreName
     *
     * @return string
     */
    protected function getJobName(array $job, ?string $currentStoreName): string
    {
        $jobName = parent::getJobName($job, $currentStoreName);
        $projectName = getenv('SPRYKER_PROJECT_NAME');

        if ($projectName === false) {
            return $jobName;
        }

        return sprintf('%s__%s', $projectName, $jobName);
    }
}
