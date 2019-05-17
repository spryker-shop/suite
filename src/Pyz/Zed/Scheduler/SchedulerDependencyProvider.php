<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Scheduler;

use Spryker\Zed\JenkinsScheduler\Communication\Plugin\Adapter\JenkinsSchedulerAdapterPlugin;
use Spryker\Zed\Scheduler\Communication\Plugin\ConfigurationReader\PhpSchedulerReaderPlugin;
use Spryker\Zed\Scheduler\SchedulerDependencyProvider as SprykerSchedulerDependencyProvider;

class SchedulerDependencyProvider extends SprykerSchedulerDependencyProvider
{
    protected const JENKINS_SCHEDULER_ID = 'jenkins';

    /**
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Plugin\SchedulerReaderPluginInterface[]
     */
    protected function getReaderPlugins(): array
    {
        return [
            new PhpSchedulerReaderPlugin(),
        ];
    }

    /**
     * @return \Spryker\Zed\SchedulerExtension\Dependency\Adapter\SchedulerAdapterPluginInterface[]
     */
    protected function getAdapterPlugins(): array
    {
        return [
            static::JENKINS_SCHEDULER_ID => new JenkinsSchedulerAdapterPlugin(),
        ];
    }
}
