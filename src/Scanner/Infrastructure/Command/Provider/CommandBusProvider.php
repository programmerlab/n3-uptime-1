<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Infrastructure\Command\Provider;

use Acquia\N3\Application\Command\Bus\CommandBus;
use Acquia\N3\Framework\Application\Command\HandlerLocator;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Provides a command bus.
 *
 */
class CommandBusProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     *
     */
    protected $provides = [
        'command.bus',
    ];


    /**
     * {@inheritdoc}
     *
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->share('command.bus', function () use ($container) {
            $handlers = [
                'CreateDomainCommand'  => $container->get('create.domain.handler'),
                'DeleteDomainCommand'  => $container->get('delete.domain.handler'),
                'EnableDomainCommand'  => $container->get('enable.domain.handler'),
                'DisableDomainCommand' => $container->get('disable.domain.handler'),
            ];

            $locator = new HandlerLocator($handlers);

            return new CommandBus($locator);
        });
    }
}
