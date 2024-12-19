<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Pyz\Zed\ExampleStateMachine\Business\Model;

use Generated\Shared\Transfer\StateMachineItemTransfer;
use Propel\Runtime\Collection\Collection;
use Pyz\Zed\ExampleStateMachine\Persistence\ExampleStateMachineQueryContainerInterface;

class ExampleStateMachineItemReader
{
    /**
     * @var \Pyz\Zed\ExampleStateMachine\Persistence\ExampleStateMachineQueryContainerInterface
     */
    protected $exampleStateMachineQueryContainer;

    /**
     * @param \Pyz\Zed\ExampleStateMachine\Persistence\ExampleStateMachineQueryContainerInterface $exampleStateMachineQueryContainer
     */
    public function __construct(ExampleStateMachineQueryContainerInterface $exampleStateMachineQueryContainer)
    {
        $this->exampleStateMachineQueryContainer = $exampleStateMachineQueryContainer;
    }

    /**
     * @param array<int> $stateIds
     *
     * @return array<\Generated\Shared\Transfer\StateMachineItemTransfer>
     */
    public function getStateMachineItemTransferByItemStateIds(array $stateIds = []): array
    {
        $exampleStateMachineItems = $this->exampleStateMachineQueryContainer
            ->queryStateMachineItemsByStateIds($stateIds)
            ->find();

        return $this->hydrateTransferFromPersistence($exampleStateMachineItems);
    }

    /**
     * @return array<\Generated\Shared\Transfer\StateMachineItemTransfer>
     */
    public function getStateMachineItems(): array
    {
        $exampleStateMachineItems = $this->exampleStateMachineQueryContainer
            ->queryAllStateMachineItems();

        return $this->hydrateTransferFromPersistence($exampleStateMachineItems);
    }

    /**
     * @psalm-suppress TooManyTemplateParams
     *
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\ExampleStateMachine\Persistence\PyzExampleStateMachineItem> $exampleStateMachineItems
     *
     * @return array<\Generated\Shared\Transfer\StateMachineItemTransfer>
     */
    protected function hydrateTransferFromPersistence(Collection $exampleStateMachineItems): array
    {
        $stateMachineItems = [];
        foreach ($exampleStateMachineItems as $exampleStateMachineItemEntity) {
            if (!$exampleStateMachineItemEntity->getFkStateMachineItemState()) {
                continue;
            }

            $stateMachineItemIdentifier = new StateMachineItemTransfer();
            $stateMachineItemIdentifier->setIdentifier($exampleStateMachineItemEntity->getIdExampleStateMachineItem());
            $stateMachineItemIdentifier->setIdItemState($exampleStateMachineItemEntity->getFkStateMachineItemState());

            $stateMachineItems[] = $stateMachineItemIdentifier;
        }

        return $stateMachineItems;
    }
}
