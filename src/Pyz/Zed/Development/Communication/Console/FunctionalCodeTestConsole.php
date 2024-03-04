<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Development\Communication\Console;

use Spryker\Zed\Development\Communication\Console\CodeTestConsole;

/**
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 */
class FunctionalCodeTestConsole extends CodeTestConsole
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'code:test:functional';

    /**
     * @var string
     */
    protected const CODECEPTION_CONFIG_FILE_NAME = 'codeception.functional.yml';
}
