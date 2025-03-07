<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Shared\SspInquiryManagement;

use SprykerFeature\Shared\SspInquiryManagement\SspInquiryManagementConfig as SprykerSspInquiryConfig;

class SspInquiryManagementConfig extends SprykerSspInquiryConfig
{
    public function getSspInquiryInitialStateMap(): array
    {
        return [
            'SspInquiryDefaultStateMachine' => 'created',
        ];
    }

    public function getSspInquiryStateMachineProcessSspInquiryTypeMap(): array
    {
        return [
            'general' => 'SspInquiryDefaultStateMachine',
            'order' => 'SspInquiryDefaultStateMachine',
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
    public function getAvailableStatuses(): array
    {
        return [
            'pending',
            'in_review',
            'approved',
            'rejected',
            'canceled',
        ];
    }

    /**
     * @return string
     */
    public function getStorageName(): string
    {
        return 'ssp-inquiry';
    }
}
