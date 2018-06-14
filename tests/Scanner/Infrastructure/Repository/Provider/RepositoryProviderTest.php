<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Test\Scanner\Infrastructure\Repository\Provider;

use Acquia\N3\Application\Event\Bus\EventBusInterface;
use Acquia\N3\Uptime\Scanner\Domain\ScannerRepositoryInterface;
use Acquia\N3\Uptime\Scanner\Infrastructure\Repository\Provider\RepositoryProvider;
use League\Container\Container;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Tests the repository service provider.
 *
 */
class RepositoryProviderTest extends TestCase
{
    /**
     * Ensures the services provided are of the right types.
     *
     */
    public function testServices(): void
    {
        $container = new Container();

        $db        = $this->createMock(\PDO::class);
        $event_bus = $this->createMock(EventBusInterface::class);
        $logger    = $this->createMock(LoggerInterface::class);

        $container->add('db', function () use ($db) {
            return $db;
        });

        $container->add('event.bus', function () use ($event_bus) {
            return $event_bus;
        });

        $container->add('logger', function () use ($logger) {
            return $logger;
        });

        $provider  = new RepositoryProvider();
        $container->addServiceProvider($provider);

        $this->assertTrue($container->has('scanner.repository'));
        $this->assertInstanceOf(ScannerRepositoryInterface::class, $container->get('scanner.repository'));
    }
}
