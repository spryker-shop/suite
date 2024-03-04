<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ExampleStateMachine\Persistence;

use Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItemQuery;
use Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory;

/**
 * @method \Pyz\Zed\ExampleStateMachine\Persistence\ExampleStateMachineQueryContainerInterface getQueryContainer()
 */
class ExampleStateMachinePersistenceFactory extends AbstractPersistenceFactory
{
    /**
     * @psalm-suppress TooManyTemplateParams
     *
     * @return \Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItemQuery<\Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItem>
     */
    public function createExampleStateMachineQuery(): PyzExampleStateMachineItemQuery
    {
        return PyzExampleStateMachineItemQuery::create();
    }
}
