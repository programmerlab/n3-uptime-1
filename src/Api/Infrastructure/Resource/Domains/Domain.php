<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Api\Infrastructure\Resource\Domains;

use Acquia\N3\Application\Command\Bus\CommandBusInterface;
use Acquia\N3\Framework\Application\Resource\AbstractResource;
use Acquia\N3\Framework\Application\Resource\HalDocument;
use Acquia\N3\Framework\Application\Resource\ResponseFactory;
use Acquia\N3\Uptime\Scanner\Application\Command\DeleteDomainCommand;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Provides endpoints for "/domain/{domain}".
 */
class Domain extends AbstractResource
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
     * @param UserFinderInterface $user_finder
     *   A user finder.
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
     * Delete domain from scanner.
     *
     * @param ServerRequestInterface $request
     *   The incoming request.
     *
     * @return ResponseInterface
     *   A PSR-7-compliant response.
     *
     */
    public function delete(ServerRequestInterface $request, string $domain): ResponseInterface
    {
        $domain  = new DomainName($domain);
        $command = new DeleteDomainCommand($domain);
        $this->command_bus->handle($command);

        $doc = new HalDocument($this->createUri($request));

        $doc->addData('message', sprintf('Domain with domain name %s deleted successfully.', $this->getParameterValue($request, 'domain')));

        $doc->addLink('Domain', $this->createUri($request, 'domain/' . (string) $domain));

        return ResponseFactory::createJson($doc);
    }
}
