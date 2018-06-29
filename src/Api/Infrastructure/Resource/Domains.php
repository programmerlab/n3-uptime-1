<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Api\Infrastructure\Resource;

use Acquia\N3\Application\Command\Bus\CommandBusInterface;
use Acquia\N3\Framework\Application\Resource\AbstractResource;
use Acquia\N3\Framework\Application\Resource\HalDocument;
use Acquia\N3\Framework\Application\Resource\ResponseFactory;
use Acquia\N3\Uptime\Scanner\Application\Command\CreateDomainCommand;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Provides endpoints for "/domains/".
 */
class Domains extends AbstractResource
{
    /**
     * The command bus.
     *
     * @var CommandBusInterface
     *
     */
    protected $command_bus;


    /**
     * The default expected status.
     *
     * @var int
     *
     */
    const DEFAULT_EXPECTED_STATUS = 200;


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
     * Create domain from scanner.
     *
     * @param ServerRequestInterface $request
     *   The incoming request.
     *
     * @throws \InvalidArgumentException
     *   This exception will validate for empty domain & throws an error message.
     *
     * @return ResponseInterface
     *   A PSR-7-compliant response.
     *
     */
    public function post(ServerRequestInterface $request): ResponseInterface
    {
        if (empty($this->getParameterValue($request, 'domain'))) {
            throw new \InvalidArgumentException('Domain name is required.');
        }

        $domain          = new DomainName($this->getParameterValue($request, 'domain'));
        $expected_status = $this->getParameterValue($request, 'expected_status', static::DEFAULT_EXPECTED_STATUS);

        $command = new CreateDomainCommand($domain, (int) $expected_status);
        $this->command_bus->handle($command);

        $doc = new HalDocument($this->createUri($request));
        $doc->addData('message', sprintf('Domain %s created successfully.', $this->getParameterValue($request, 'domain')));

        return ResponseFactory::createJson($doc);
    }
}
