<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Api\Infrastructure\Resource\Provider;

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
    }
}
