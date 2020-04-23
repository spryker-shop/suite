<?php

/**
 * This is the global runtime configuration for Yves and Generated_Yves_Zed in a production environment.
 */

use Spryker\Shared\Collector\CollectorConstants;
use Spryker\Shared\Kernel\KernelConstants;
use Spryker\Shared\Search\SearchConstants;

$currentStore = strtolower(APPLICATION_STORE);

// ---------- Auto-loader
$config[KernelConstants::AUTO_LOADER_UNRESOLVABLE_CACHE_ENABLED] = true;

// ---------- Elasticsearch
$ELASTICA_INDEX_NAME = sprintf('%s_search', $currentStore);
$config[SearchConstants::ELASTICA_PARAMETER__INDEX_NAME] = $ELASTICA_INDEX_NAME;
$config[CollectorConstants::ELASTICA_PARAMETER__INDEX_NAME] = $ELASTICA_INDEX_NAME;
