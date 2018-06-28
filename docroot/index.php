<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Acquia\N3\Configuration\Provider\ConfigurationDefinitionProvider;
use Acquia\N3\Framework\Application\Http\JsonServerRequestFactory;
use Acquia\N3\Framework\Application\Http\Kernel;
use Acquia\N3\Framework\Infrastructure\Configuration\Provider\ConfigurationProvider;
use Acquia\N3\Framework\Infrastructure\Database\Provider\DatabaseProvider;
use Acquia\N3\Framework\Infrastructure\Event\Provider\EventBusProvider;
use Acquia\N3\Framework\Infrastructure\Http\Provider\HttpServiceProvider;
use Acquia\N3\Framework\Infrastructure\Logger\Provider\BugsnagProvider;
use Acquia\N3\Framework\Infrastructure\Logger\Provider\LoggerProvider;
use Acquia\N3\Types\Uuid;
use Acquia\N3\Uptime\Api\Infrastructure\Resource\Provider\ResourceProvider;
use Acquia\N3\Uptime\Scanner\Infrastructure\Command\Provider\CommandBusProvider;
use Acquia\N3\Uptime\Scanner\Infrastructure\Command\Provider\CommandHandlerProvider;
use Acquia\N3\Uptime\Scanner\Infrastructure\Repository\Provider\RepositoryProvider;
use League\Container\Container;
use Zend\Diactoros\Response\SapiEmitter;

$config_path = __DIR__ . '/../config';

// Create container and register services.
$container = new Container();
$container->addServiceProvider(new ConfigurationDefinitionProvider());
$container->addServiceProvider(new ConfigurationProvider(configuration_files($config_path)));

$container->addServiceProvider(new EventBusProvider());
$container->addServiceProvider(new CommandHandlerProvider());
$container->addServiceProvider(new CommandBusProvider());
$container->addServiceProvider(new RepositoryProvider());

// Retrieve configuration.
$config      = $container->get('config');
$app_name    = $config->getByKey('name')->getData();
$env_name    = $config->getByKey('environment')->getData();
$api_version = $config->getByKey('version')->getData();
$base_uri    = $config->getByKey('base_uri')->getData();
$debug       = (bool) $config->getByKey('debug')->getData();
$database    = $config->getByKey('database')->getData();

// Add service providers that depend on configuration.
if ($config->getByKey('bugsnag.enabled')->getData()) {
    $bugsnag_key = $config->getByKey('bugsnag.key')->getData();
    $container->addServiceProvider(new BugsnagProvider($env_name, $bugsnag_key));
}

$container->addServiceProvider(new DatabaseProvider($database['dsn'], $database['user'], $database['password']));
$container->addServiceProvider(new LoggerProvider($app_name, $env_name, $debug));

$container->addServiceProvider(new HttpServiceProvider($config_path, $base_uri, $api_version));
$container->addServiceProvider(new ResourceProvider($base_uri));


// Handle the request.
$request = JsonServerRequestFactory::fromGlobals();
$request = $request->withAttribute('request_id', (string) Uuid::create());

/** @var Kernel $kernel */
$kernel   = $container->get('http.kernel');
$response = $kernel->handle($request);

/** @var SapiEmitter $emitter */
$emitter = $container->get('http.emitter');
$emitter->emit($response);

/**
 * Retrieves a list of configuration files.
 *
 * @param string $config_path
 *   The path to the configuration directory.
 *
 * @return string[]
 *   A list of paths to available configuration files.
 *
 */
function configuration_files(string $config_path): array
{
    $local_config = $config_path . '/config.local.yaml';
    $paths[]      = $config_path . '/config.default.yaml';

    if (is_file($local_config)) {
        $paths[] = $local_config;
    }

    return $paths;
}
