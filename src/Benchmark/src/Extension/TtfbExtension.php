<?php

namespace Benchmark\Spryker\Extension;

use PhpBench\DependencyInjection\Container;
use PhpBench\DependencyInjection\ExtensionInterface;
use PhpBench\Extension\CoreExtension;


class TtfbExtension implements ExtensionInterface
{
    public function getDefaultConfig()
    {
        // default configuration for this extension
        return [
            'executors' => ['ttfb'],
        ];
    }

    public function load(Container $container)
    {
        $container->register('benchmark.executor.ttfb', function (Container $container) {
            return new TtfbExecutor(
                $container->get(CoreExtension::SERVICE_REMOTE_LAUNCHER)
            );
        }, [CoreExtension::TAG_EXECUTOR => ['name' => 'ttfb']]);

    }

    // called after load() can be used to add tagged services to existing services.
    public function build(Container $container)
    {
        $container->setParameter('executors', [12,3,]);
    }
}
