<?php
/**
 * Created by PhpStorm.
 * User: kravchenko
 * Date: 2020-03-02
 * Time: 02:28
 */

namespace Pyz\Zed\ProductRelationGui;

use Spryker\Zed\Kernel\Communication\Form\FormTypeInterface;
use Spryker\Zed\ProductRelationGui\ProductRelationGuiDependencyProvider as SprykerProductRelationGuiDependencyProvider;
use Spryker\Zed\Store\Communication\Plugin\Form\StoreRelationToggleFormTypePlugin;

class ProductRelationGuiDependencyProvider extends SprykerProductRelationGuiDependencyProvider
{
    /**
     * @return \Spryker\Zed\Kernel\Communication\Form\FormTypeInterface
     */
    protected function getStoreRelationFormTypePlugin(): FormTypeInterface
    {
        return new StoreRelationToggleFormTypePlugin();
    }
}
