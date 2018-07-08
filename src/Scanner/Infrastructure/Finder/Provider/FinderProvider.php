<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Infrastructure\Finder\Provider;

use Acquia\N3\Uptime\Scanner\Infrastructure\Finder\DomainFinder;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Provides finders as services.
 *
 */
class FinderProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     *
     */
    protected $provides = [
        'domain.finder',
    ];


    /**
     * {@inheritdoc}
     *
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->share('domain.finder', DomainFinder::class)
            ->withArguments(['db', 'logger']);
    }
}
