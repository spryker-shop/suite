<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\Oms\Business\Calculator;

use DateInterval;
use DateTime;
use Generated\Shared\Transfer\TimeoutProcessorTimeoutRequestTransfer;
use Generated\Shared\Transfer\TimeoutProcessorTimeoutResponseTransfer;

class InitiationTimeoutCalculator implements TimeoutProcessorTimeoutCalculatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\TimeoutProcessorTimeoutRequestTransfer $timeoutProcessorTimeoutRequestTransfer
     *
     * @return \Generated\Shared\Transfer\TimeoutProcessorTimeoutResponseTransfer
     */
    public function calculateTimeout(TimeoutProcessorTimeoutRequestTransfer $timeoutProcessorTimeoutRequestTransfer): TimeoutProcessorTimeoutResponseTransfer
    {
        $omsEventTransfer = $timeoutProcessorTimeoutRequestTransfer->getOmsEvent();
        $interval = DateInterval::createFromDateString($omsEventTransfer->getTimeout());
        assert($interval !== false);
        $currentTime = (new DateTime())->setTimestamp($timeoutProcessorTimeoutRequestTransfer->getTimestamp());
        $timeout = $currentTime->add($interval);

        return (new TimeoutProcessorTimeoutResponseTransfer())->setTimeoutTimestamp($timeout->getTimestamp());
    }
}
