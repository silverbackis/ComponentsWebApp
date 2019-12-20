<?php

declare(strict_types=1);

namespace App\Resources\config;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return function (ContainerConfigurator $configurator) {
    $configurator->parameters()->set('locale', 'en');
    $services = $configurator->services();
    $services
        ->defaults()
        ->autowire()
        ->autoconfigure()
        ->private()
        ->bind('$projectDir', '%kernel.project_dir%')
        ->bind('$emailAddress', '%env(FROM_EMAIL_ADDRESS)%')
    ;

    $services
        ->load('App\\', '../src')
        ->exclude('../src/{DependencyInjection,Entity,Migrations,Tests,Kernel.php}');

    $services
        ->load('App\\Controller\\', '../src/Controller')
        ->tag('controller.service_arguments');

    $services
        ->load('App\\DataFixtures\\', '../src/DataFixtures')
        ->tag('doctrine.fixture.orm')
        ->exclude('../src/DataFixtures/Model');

    $services
        ->load('App\\EventListener\\', '../src/EventListener')
        ->tag('kernel.event_listener');
};
