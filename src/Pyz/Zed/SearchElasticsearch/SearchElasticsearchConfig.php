<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\SearchElasticsearch;

use Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig as SprykerSearchElasticsearchConfig;

class SearchElasticsearchConfig extends SprykerSearchElasticsearchConfig
{
    /**
     * @project Only needed in internal nonsplit project, not in public split project.
     *
     * @return array<string>
     */
    public function getJsonSchemaDefinitionDirectories(): array
    {
        $directories = parent::getJsonSchemaDefinitionDirectories();

        if (glob(sprintf('%s/vendor/spryker/spryker/Bundles/*/src/*/Shared/*/Schema/', APPLICATION_ROOT_DIR), GLOB_NOSORT | GLOB_ONLYDIR)) {
            $directories[] = sprintf('%s/vendor/spryker/spryker/Bundles/*/src/*/Shared/*/Schema/', APPLICATION_ROOT_DIR);
        }

        if (glob(sprintf('%s/vendor/spryker/spryker/Features/*/src/*/Shared/*/Schema/', APPLICATION_ROOT_DIR))) {
            $directories[] = sprintf('%s/vendor/spryker/spryker/Features/*/src/*/Shared/*/Schema/', APPLICATION_ROOT_DIR);
        }

        return $directories;
    }
}
