<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Infrastructure\Command\Provider;

use Acquia\N3\Uptime\Scanner\Application\Command\Handler\DeleteDomainHandler;
use Acquia\N3\Uptime\Scanner\Application\Command\Handler\EnableDomainHandler;

use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Provides handlers to commands.
 *
 */
class CommandHandlerProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     *
     */
    protected $provides = [
        'delete.domain.handler',
        'enable.domain.handler',
    ];

    /**
     * {@inheritdoc}
     *
     */
    public function register()
    {
        $container = $this->getContainer();
        $container->share('delete.domain.handler', DeleteDomainHandler::class)
            ->withArguments(['scanner.repository']);

        $container->share('enable.domain.handler', EnableDomainHandler::class)
            ->withArguments(['scanner.repository']);
    }
}
