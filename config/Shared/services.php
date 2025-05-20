<?php

// config/Shared/services.php
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $config, ContainerBuilder $container) {
    // configure services
    $services = $config->services()
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->public();
    $services->load('Pyz\\Shared\\', APPLICATION_ROOT_DIR . '/src/Pyz/Shared/');
};
