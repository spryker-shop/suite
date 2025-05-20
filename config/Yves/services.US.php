<?php

// config/Shared/services.EU.php
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $config, ContainerBuilder $container) {
    // Override the service definition for TestModel for US code bucket
    $container->register(
        \Pyz\Yves\ExampleModule\Model\TestModel::class,
        \Pyz\Yves\ExampleModuleUS\Model\TestModel::class
    )->setAutowired(true);
};
