<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Infrastructure\Repository\Provider;

use Acquia\N3\Uptime\Scanner\Infrastructure\Repository\ScannerRepository;
use Acquia\N3\Uptime\Scanner\Infrastructure\Repository\ScannerUpdateHandler;
use League\Container\ServiceProvider\AbstractServiceProvider;

/**
 * Provides repositories related to Scanner.
 *
 */
class RepositoryProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     *
     */
    protected $provides = [
        'scanner.repository',
    ];


    /**
     * {@inheritdoc}
     *
     */
    public function register()
    {
        $container = $this->getContainer();

        $container->share('scanner.repository', function () use ($container) {
            $db = $container->get('db');
            $bus = $container->get('event.bus');
            $logger = $container->get('logger');

            $handler = new ScannerUpdateHandler($db);
            $repo = new ScannerRepository($db, $bus, $handler, $logger);

            return $repo;
        });
    }
}
