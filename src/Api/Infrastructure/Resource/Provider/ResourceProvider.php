<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Api\Infrastructure\Resource\Provider;

use Acquia\N3\Uptime\Api\Infrastructure\Resource\Domains;
use Acquia\N3\Uptime\Api\Infrastructure\Resource\Domains\Disable;
use Acquia\N3\Uptime\Api\Infrastructure\Resource\Domains\Domain;
use Acquia\N3\Uptime\Api\Infrastructure\Resource\Domains\Enable;
use Acquia\N3\Uptime\Api\Infrastructure\Resource\Root;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Provides resource controllers for endpoints and related services.
 *
 */
class ResourceProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     *
     */
    protected $provides = [
        'resource.root',
        'resource.domains',
        'resource.domains.domain',
        'resource.domains.domain.disable',
        'resource.domains.domain.enable',
    ];


    /**
     * The base path of the API.
     *
     * @var string
     *
     */
    protected $base_uri;


    /**
     * Constructor.
     *
     * @param string $base_uri
     *   The base path of the API.
     *
     */
    public function __construct(string $base_uri)
    {
        $this->base_uri = $base_uri;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->share('resource.root', function () {
            return new Root($this->base_uri);
        });

        $container->share('resource.domains', function () use ($container) {
            $command_bus = $container->get('command.bus');

            return new Domains($this->base_uri, $command_bus);
        });

        $container->share('resource.domains.domain', function () use ($container) {
            $finder      = $container->get('domain.finder');
            $command_bus = $container->get('command.bus');

            return new Domain($this->base_uri, $finder, $command_bus);
        });

        $container->share('resource.domains.domain.disable', function () use ($container) {
            $command_bus = $container->get('command.bus');

            return new Disable($this->base_uri, $command_bus);
        });

        $container->share('resource.domains.domain.enable', function () use ($container) {
            $command_bus = $container->get('command.bus');

            return new Enable($this->base_uri, $command_bus);
        });
    }

    /**
     * {@inheritdoc}
     *
     */
    public function boot()
    {
        $container = $this->getContainer();

        if ($container->has('event.bus')) {
            $this->register();
            $event_bus = $container->get('event.bus');
            $event_bus->register($container->get('api.metadata.listener'));
        }
    }
}
