<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Sales\PageObject;

class OrderDetailPage
{
    public const OMS_EVENT_TRIGGER_XPATH = '//a[@data-event="%s"]';

    public const ORDER_DETAIL_PAGE_URL = '/sales/detail?id-sales-order=%d';

    public const ORDER_DETAIL_TABLE_FIRST_ORDER_ID_XPATH = '//*[@class="dataTables_scrollBody"]/table/tbody/tr[1]/td[1]';
}
