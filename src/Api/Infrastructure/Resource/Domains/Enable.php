<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Api\Infrastructure\Resource\Domains;

use Acquia\N3\Application\Command\Bus\CommandBusInterface;
use Acquia\N3\Framework\Application\Resource\AbstractResource;
use Acquia\N3\Framework\Application\Resource\HalDocument;
use Acquia\N3\Framework\Application\Resource\ResponseFactory;
use Acquia\N3\Uptime\Scanner\Application\Command\EnableDomainCommand;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Provides endpoints for "/domains/{domain}/actions/enable".
 *
 */
class Enable extends AbstractResource
{
    /**
     * The command bus.
     *
     * @var CommandBusInterface
     *
     */
    protected $command_bus;

    /**
     * Constructor.
     *
     * @param string $base_path
     *   The base path of the API.
     * @param CommandBusInterface $command_bus
     *   A command bus.
     *
     */
    public function __construct(string $base_uri, CommandBusInterface $command_bus)
    {
        parent::__construct($base_uri);

        $this->command_bus = $command_bus;
    }

    /**
     * Enable a domain.
     *
     * @param ServerRequestInterface $request
     *   The incoming request.
     * @param string $domain
     *   The status of the domain to enable.
     *
     * @return ResponseInterface
     *   A PSR-7-compliant response.
     *
     */
    public function post(ServerRequestInterface $request, string $domain): ResponseInterface
    {
        $domain_name = new DomainName($domain);
        $command     = new EnableDomainCommand($domain_name);

        $this->command_bus->handle($command);

        $doc = new HalDocument($this->createUri($request));
        $doc->addData('message', sprintf('Domain %s enabled.', $domain));
        $doc->addLink('domain', $this->createUri($request, 'domains/' . $domain));

        return ResponseFactory::createJson($doc);
    }
}
