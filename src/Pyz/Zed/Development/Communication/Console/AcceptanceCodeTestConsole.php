<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Development\Communication\Console;

use Spryker\Zed\Development\Communication\Console\CodeTestConsole;

/**
 * @method \Spryker\Zed\Development\Communication\DevelopmentCommunicationFactory getFactory()
 * @method \Spryker\Zed\Development\Business\DevelopmentFacadeInterface getFacade()
 */
class AcceptanceCodeTestConsole extends CodeTestConsole
{
    /**
     * @var string
     */
    public const COMMAND_NAME = 'code:test:acceptance';

    /**
     * @var string
     */
    protected const CODECEPTION_CONFIG_FILE_NAME = 'codeception.acceptance.yml';
}
