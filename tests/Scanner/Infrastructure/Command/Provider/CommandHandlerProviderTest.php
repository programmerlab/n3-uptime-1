<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Test\People\Infrastructure\Command\Provider;

use Acquia\N3\Uptime\Scanner\Application\Command\Handler\DeleteDomainHandler;
use Acquia\N3\Uptime\Scanner\Application\Command\Handler\DisableDomainHandler;
use Acquia\N3\Uptime\Scanner\Application\Command\Handler\EnableDomainHandler;
use Acquia\N3\Uptime\Scanner\Domain\ScannerRepositoryInterface;
use Acquia\N3\Uptime\Scanner\Infrastructure\Command\Provider\CommandHandlerProvider;
use League\Container\Container;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Tests the read model finder service provider.
 *
 */
class CommandHandlerProviderTest extends TestCase
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
        $repo   = $this->createMock(ScannerRepositoryInterface::class);

        $container->add('db', function () use ($db) {
            return $db;
        });

        $container->add('logger', function () use ($logger) {
            return $logger;
        });

        $container->add('scanner.repository', function () use ($repo) {
            return $repo;
        });

        $provider  = new CommandHandlerProvider();
        $container->addServiceProvider($provider);

        $this->assertTrue($container->has('delete.domain.handler'));


        $this->assertInstanceOf(DeleteDomainHandler::class, $container->get('delete.domain.handler'));

        $this->assertTrue($container->has('enable.domain.handler'));
        $this->assertInstanceOf(EnableDomainHandler::class, $container->get('enable.domain.handler'));

        $this->assertTrue($container->has('disable.domain.handler'));
        $this->assertInstanceOf(DisableDomainHandler::class, $container->get('disable.domain.handler'));
    }
}
