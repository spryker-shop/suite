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
     * @see \Spryker\Shared\Comment\CommentConfig::getCommentAvailableTags()
     *
     * @return string[]
     */
    public function getCommentAvailableTags(): array
    {
        return [
            'attached',
            '2019',
            'spryker2019',
        ];
    }
}
