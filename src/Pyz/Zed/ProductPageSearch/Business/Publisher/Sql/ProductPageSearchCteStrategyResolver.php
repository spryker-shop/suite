<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Business\Publisher\Sql;

use Pyz\Zed\PriceProductStorage\Business\Exception\CteNotFoundException;
use Pyz\Zed\PriceProductStorage\Business\Exception\CteNotSupportedException;
use Pyz\Zed\Propel\Business\PropelFacadeInterface;

class ProductPageSearchCteStrategyResolver implements SqlResolverInterface
{
    /**
     * @var \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface[]
     */
    protected $cteCollection;

    /**
     * @var \Pyz\Zed\Propel\Business\PropelFacadeInterface
     */
    protected $propelFacade;

    /**
     * @param \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface[] $cteCollection
     * @param \Pyz\Zed\Propel\Business\PropelFacadeInterface $propelFacade
     */
    public function __construct(array $cteCollection, PropelFacadeInterface $propelFacade)
    {
        $this->cteCollection = $cteCollection;
        $this->propelFacade = $propelFacade;
    }

    /**
     * @throws \Pyz\Zed\PriceProductStorage\Business\Exception\CteNotFoundException
     *
     * @return \Pyz\Zed\ProductPageSearch\Business\Publisher\Sql\ProductPagePublisherCteInterface
     */
    public function resolve(): ProductPagePublisherCteInterface
    {
        $this->checkCteSupport();

        $currentDB = $this->propelFacade->getCurrentDatabaseEngine();

        foreach ($this->cteCollection as $cte) {
            if (in_array($currentDB, $cte->getCompatibleEngines(), true)) {
                return $cte;
            }
        }

        throw new CteNotFoundException('Common table expression strategy not found.');
    }

    /**
     * @throws \Pyz\Zed\PriceProductStorage\Business\Exception\CteNotSupportedException
     *
     * @return void
     */
    protected function checkCteSupport(): void
    {
        if (!$this->propelFacade->checkCteSupport()) {
            throw new CteNotSupportedException('Common table expressions are not supported by your RDBMS.');
        }
    }
}
