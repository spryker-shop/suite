<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Shared\Publisher;

class PublisherConstants
{
    /**
     * Specification:
     *  - If value is true, P&S will use CTEs for data handling.
     *
     * @api
     *
     * @var string
     */
    public const IS_CTE_ENABLED = 'PUBLISHER:IS_CTE_ENABLED';
}
