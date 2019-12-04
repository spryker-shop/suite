<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Glossary;

use Spryker\Zed\Glossary\GlossaryConfig as SprykerGlossaryConfig;

class GlossaryConfig extends SprykerGlossaryConfig
{
    /**
     * @return string[]
     */
    public function getGlossaryFilePaths()
    {
        $paths = parent::getGlossaryFilePaths();
        $paths = $this->addSprykerFilePath($paths);

        return $paths;
    }
}
