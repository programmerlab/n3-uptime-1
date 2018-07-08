<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Test\Api\Infrastructure\Resource\Provider;

use Acquia\N3\Application\Command\Bus\CommandBusInterface;
use Acquia\N3\Uptime\Api\Infrastructure\Resource\Provider\ResourceProvider;
use Acquia\N3\Uptime\Scanner\Application\Finder\DomainFinderInterface;
use League\Container\Container;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * Tests the endpoint resource provider.
 *
 */
class ResourceProviderTest extends TestCase
{
    /**
     * Ensures the resource provider registers services for each defined route.
     *
     * @dataProvider routeProvider
     *
     */
    public function testRouting($route_path, $route_controller): void
    {
        $container = new Container();
        $base_uri  = '/';

        $domain_finder = $this->createMock(DomainFinderInterface::class);
        $command_bus   = $this->createMock(CommandBusInterface::class);

        $container->add('domain.finder', function () use ($domain_finder) {
            return $domain_finder;
        });

        $container->add('command.bus', function () use ($command_bus) {
            return $command_bus;
        });

        $provider = new ResourceProvider($base_uri);
        $container->addServiceProvider($provider);

        [$service, $method] = explode(':', $route_controller);
        $this->assertTrue($container->has($service));
        $resource = $container->get($service);
        $this->assertTrue(method_exists($resource, $method));
    }

    /**
     * Provides the list of route controllers to test.
     *
     */
    public function routeProvider(): array
    {
        // The phpunit.xml file should have defined an N3_UPTIME_CONFIG_PATH
        // environment variable that is relative to the working directory.

        $routes_path = implode('/', [
            getcwd(),
            $_ENV['N3_UPTIME_CONFIG_PATH'],
            'routes.yaml',
        ]);

        $yaml   = new Yaml();
        $routes = $yaml->parse(file_get_contents($routes_path));

        return $routes;
    }
}
