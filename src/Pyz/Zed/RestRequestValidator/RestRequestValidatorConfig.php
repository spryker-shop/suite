<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\RestRequestValidator;

use Spryker\Zed\RestRequestValidator\RestRequestValidatorConfig as CoreRestRequestValidatorConfig;

class RestRequestValidatorConfig extends CoreRestRequestValidatorConfig
{
    /**
     * @project Only needed in internal nonsplit project, not in public split project.
     */
    protected const PATH_PATTERN_CORE_VALIDATION = '/*/*/*/*/*/*/Glue/*/Validation';
}
