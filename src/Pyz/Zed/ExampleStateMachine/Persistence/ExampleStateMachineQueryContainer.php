<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ExampleStateMachine\Persistence;

use Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItemQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\Collection;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Pyz\Zed\ExampleStateMachine\Persistence\ExampleStateMachinePersistenceFactory getFactory()
 */
class ExampleStateMachineQueryContainer extends AbstractQueryContainer implements ExampleStateMachineQueryContainerInterface
{
    /**
     * @psalm-suppress TooManyTemplateParams
     *
     * @param array<int> $stateIds
     *
     * @return \Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItemQuery<\Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItem>
     */
    public function queryStateMachineItemsByStateIds(array $stateIds = []): PyzExampleStateMachineItemQuery
    {
          return $this->getFactory()
              ->createExampleStateMachineQuery()
              ->filterByFkStateMachineItemState($stateIds, Criteria::IN);
    }

    /**
     * @psalm-suppress TooManyTemplateParams
     *
     * @return \Propel\Runtime\Collection\Collection<\Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItem>
     */
    public function queryAllStateMachineItems(): Collection
    {
        /** @var \Propel\Runtime\Collection\Collection<\Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItem> $exampleStateMachineCollection */
        $exampleStateMachineCollection = $this->getFactory()
             ->createExampleStateMachineQuery()
             ->find();

         return $exampleStateMachineCollection;
    }

    /**
     * @psalm-suppress TooManyTemplateParams
     *
     * @param int $idStateMachineItem
     *
     * @return \Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItemQuery<\Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItem>
     */
    public function queryExampleStateMachineItemByIdStateMachineItem(
        $idStateMachineItem,
    ): PyzExampleStateMachineItemQuery {
        return $this->getFactory()
            ->createExampleStateMachineQuery()
            ->filterByIdExampleStateMachineItem($idStateMachineItem);
    }
}
