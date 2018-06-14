<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Application\Command\Handler;

use Acquia\N3\Application\Command\CommandInterface;
use Acquia\N3\Application\Command\Handler\HandlerInterface;
use Acquia\N3\Domain\Exceptions\NotFoundException;
use Acquia\N3\Uptime\Scanner\Application\Command\EnableDomainCommand;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Acquia\N3\Uptime\Scanner\Domain\ScannerRepositoryInterface;

/**
 * Handles an enable domain command.
 *
 */
class EnableDomainHandler implements HandlerInterface
{
    /**
     * A Scanner repository.
     *
     * @var ScannerRepositoryInterface
     *
     */
    protected $scanner_repo;


    /**
     * Constructor.
     *
     * @param ScannerRepositoryInterface $scanner_repo
     *   The Scanner repository.
     *
     */
    public function __construct(ScannerRepositoryInterface $scanner_repo)
    {
        $this->scanner_repo = $scanner_repo;
    }


    /**
     * {@inheritdoc}
     *
     */
    public function handle(CommandInterface $command)
    {
        /** @var EnableDomainCommand $command */
        /** @var DomainName $domain_name */
        $domain = $this->scanner_repo->getByDomainName($command->getDomainName());

        if (!$domain) {
            throw new NotFoundException(sprintf('The domain with domain_name %s could not be found.', $command->getDomainName()));
        }

        $domain->enableDomain($command->getDomainName());

        $this->scanner_repo->update($domain);
    }
}
