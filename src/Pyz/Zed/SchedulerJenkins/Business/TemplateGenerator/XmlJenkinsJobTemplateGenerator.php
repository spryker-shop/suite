<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SchedulerJenkins\Business\TemplateGenerator;

use Generated\Shared\Transfer\SchedulerJobTransfer;
use Spryker\Zed\SchedulerJenkins\Business\TemplateGenerator\XmlJenkinsJobTemplateGenerator as SprykerXmlJenkinsJobTemplateGenerator;

/**
 * @deprecated Will be removed once multi-tenancy support for the Docker SDK is disabled.
 */
class XmlJenkinsJobTemplateGenerator extends SprykerXmlJenkinsJobTemplateGenerator
{
    /**
     * Docker SDK multi-tenancy setup: Adds the project name to the job template if it is set.
     *
     * @param \Generated\Shared\Transfer\SchedulerJobTransfer $jobTransfer
     *
     * @return string
     */
    public function generateJobTemplate(SchedulerJobTransfer $jobTransfer): string
    {
        $jobTransfer
            ->requireRepeatPattern()
            ->requireCommand();

        $jobTransfer = $this->extendSchedulerJobTransferWithLogRotateValue($jobTransfer);

        $projectName = (string)getenv('SPRYKER_PROJECT_NAME');

        return $this->twig->render($this->schedulerJenkinsConfig->getJenkinsTemplateName(), [
            static::KEY_JOB => $jobTransfer->toArray(),
            static::KEY_WORKING_DIR => APPLICATION_ROOT_DIR,
            'project_name' => $projectName,
        ]);
    }
}
