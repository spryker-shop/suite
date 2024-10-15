<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SchedulerJenkins\Business;

use Pyz\Zed\SchedulerJenkins\Business\TemplateGenerator\XmlJenkinsJobTemplateGenerator;
use Spryker\Zed\SchedulerJenkins\Business\SchedulerJenkinsBusinessFactory as SprykerSchedulerJenkinsBusinessFactory;
use Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface;

/**
 * @method \Spryker\Zed\SchedulerJenkins\SchedulerJenkinsConfig getConfig()
 */
class SchedulerJenkinsBusinessFactory extends SprykerSchedulerJenkinsBusinessFactory
{
    /**
     * @deprecated Will be removed once multi-tenancy support for the Docker SDK is disabled.
     *
     * @return \Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\JenkinsJobTemplateGeneratorInterface
     */
    public function createXmkJenkinsJobTemplateGenerator(): JenkinsJobTemplateGeneratorInterface
    {
        return new XmlJenkinsJobTemplateGenerator(
            $this->getTwigEnvironment(),
            $this->getConfig(),
        );
    }
}
