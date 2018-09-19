<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ExampleProductColorGroupWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

class ExampleProductColorSelectorWidget extends AbstractWidget
{
    /**
     * @param int $idProductAbstract
     */
    public function __construct(int $idProductAbstract)
    {
        $this->addParameter('idProductAbstract', $idProductAbstract);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ExampleProductColorSelectorWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ExampleProductColorGroupWidget/views/product-color-selector-widget/product-color-selector-widget.twig';
    }
}
