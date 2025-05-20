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
    $services
        ->load('Pyz\\Yves\\', APPLICATION_ROOT_DIR . '/src/Pyz/Yves/');
    $services
        ->load('Pyz\\Client\\', APPLICATION_ROOT_DIR . '/src/Pyz/Client/');
};
