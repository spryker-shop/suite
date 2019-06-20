<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\CommentWidget;

use SprykerShop\Yves\CommentWidget\CommentWidgetConfig as SprykerShopCommentConfig;

class CommentWidgetConfig extends SprykerShopCommentConfig
{
    /**
     * @return string[]
     */
    public function getCommentAvailableTags(): array
    {
        return [
            'attached',
            '2017-01 Release',
            'TODO: Johny@email.com',
            'Case-sensitive tag',
        ];
    }
}
