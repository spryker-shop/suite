<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SearchElasticsearch;

use Spryker\Zed\SearchElasticsearch\SearchElasticsearchConfig as SprykerSearchElasticsearchConfig;

class SearchElasticsearchConfig extends SprykerSearchElasticsearchConfig
{
    /**
     * @return array
     */
    public function getJsonIndexDefinitionDirectories(): array
    {
        $directories = parent::getJsonIndexDefinitionDirectories();
        $directories[] = sprintf('%s/vendor/spryker/spryker/Bundles/*/src/*/Shared/*/IndexMap/', APPLICATION_ROOT_DIR);

        return $directories;
    }
}
