<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Api\Infrastructure\Resource\Domains;

use Acquia\N3\Application\Command\Bus\CommandBusInterface;
use Acquia\N3\Framework\Application\Http\Error;
use Acquia\N3\Framework\Application\Resource\AbstractResource;
use Acquia\N3\Framework\Application\Resource\HalDocument;
use Acquia\N3\Framework\Application\Resource\ResponseFactory;
use Acquia\N3\Uptime\Scanner\Application\Command\DeleteDomainCommand;
use Acquia\N3\Uptime\Scanner\Application\Finder\DomainFinderInterface;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LogLevel;

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
     * The domain finder.
     *
     * @var DomainFinderInterface
     *
     */
    protected $domain_finder;

    /**
     * Constructor.
     *
     * @param string $base_uri
     *   The base path of the API.
     * @param DomainFinderInterface $domain_finder
     *   A domain finder.
     * @param CommandBusInterface $command_bus
     *   A command bus.
     *
     */
    public function __construct(string $base_uri, DomainFinderInterface $domain_finder, CommandBusInterface $command_bus)
    {
        parent::__construct($base_uri);

        $this->command_bus   = $command_bus;
        $this->domain_finder = $domain_finder;
    }

    /**
     * Retrieves a domain.
     *
     * @param ServerRequestInterface $request
     *   The incoming request.
     * @param string $domain_name
     *   The DomainName of the domain to update.
     *
     * @return ResponseInterface
     *   A PSR-7-compliant response.
     *
     */
    public function get(ServerRequestInterface $request, string $domain_name): ResponseInterface
    {
        $domain_name = new DomainName($domain_name);
        $domain      = $this->domain_finder->getByDomainName($domain_name);

        if (!$domain) {
            $message = sprintf('A domain with the domain_name %s cannot be found, or you do not have access to it.', $domain_name);

            return ResponseFactory::createError(new Error($message, 'not_found', 404, LogLevel::DEBUG));
        }
        $doc = new HalDocument($this->createUri($request), true, $domain->toArray());

        return ResponseFactory::createJson($doc);
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
