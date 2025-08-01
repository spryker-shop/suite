<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Shared\SelfServicePortal;

use SprykerFeature\Shared\SelfServicePortal\SelfServicePortalConfig as SprykerSelfServicePortalConfig;

class SelfServicePortalConfig extends SprykerSelfServicePortalConfig
{
    public function getInquiryInitialStateMachineMap(): array
    {
        return [
            'SspInquiryDefaultStateMachine' => 'created',
        ];
    }

    public function getSspInquiryStateMachineProcessInquiryTypeMap(): array
    {
        return [
            'general' => 'SspInquiryDefaultStateMachine',
            'order' => 'SspInquiryDefaultStateMachine',
            'ssp_asset' => 'SspInquiryDefaultStateMachine',
        ];
    }

    /**
     * @return string
     */
    public function getSspInquiryCancelStateMachineEventName(): string
    {
        return 'cancel';
    }

    /**
     * @return array<string>
     */
    public function getSspInquiryAvailableStatuses(): array
    {
        return [
            'pending',
            'in_review',
            'approved',
            'rejected',
            'canceled',
        ];
    }
}
