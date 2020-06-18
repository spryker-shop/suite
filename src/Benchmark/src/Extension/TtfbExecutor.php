<?php

namespace Benchmark\Spryker\Extension;

use PhpBench\Benchmark\Remote\Launcher;
use PhpBench\Executor\Benchmark\TemplateExecutor;

class TtfbExecutor extends TemplateExecutor
{
    public function __construct(Launcher $launcher)
    {
        parent::__construct($launcher, __DIR__ . '/template/ttfb.template.php');
    }
}
