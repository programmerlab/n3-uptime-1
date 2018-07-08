<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Test\People\Infrastructure\Finder\Provider;

use Acquia\N3\Uptime\Scanner\Application\Finder\DomainFinderInterface;
use Acquia\N3\Uptime\Scanner\Infrastructure\Finder\Provider\FinderProvider;
use League\Container\Container;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Tests the read model finder service provider.
 *
 */
class FinderProviderTest extends TestCase
{
    /**
     * Ensures the services provided are of the right types.
     *
     */
    public function testServices(): void
    {
        $container = new Container();

        $db     = $this->createMock(\PDO::class);
        $logger = $this->createMock(LoggerInterface::class);

        $container->add('db', function () use ($db) {
            return $db;
        });

        $container->add('logger', function () use ($logger) {
            return $logger;
        });

        $provider  = new FinderProvider();
        $container->addServiceProvider($provider);


        $this->assertTrue($container->has('domain.finder'));
        $this->assertInstanceOf(DomainFinderInterface::class, $container->get('domain.finder'));
    }
}
