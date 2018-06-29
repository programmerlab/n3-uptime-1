<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Application\Command\Handler;

use Acquia\N3\Application\Command\CommandInterface;
use Acquia\N3\Application\Command\Handler\HandlerInterface;
use Acquia\N3\Domain\Exceptions\ValidationException;
use Acquia\N3\Uptime\Scanner\Domain\Scanner;
use Acquia\N3\Uptime\Scanner\Domain\ScannerRepositoryInterface;

/**
 * Handles Create domain.
 *
 */
class CreateDomainHandler implements HandlerInterface
{
    /**
     * A scanner repository.
     *
     * @var ScannerRepositoryInterface
     *
     */
    protected $scanner_repo;


    /**
     * Constructor.
     *
     * @param ScannerRepositoryInterface $scanner_repo
     *   The scanner repository.
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
    public function handle(CommandInterface $command): void
    {
        $domain_exists = $this->scanner_repo->getByDomainName($command->getDomainName());

        if ($domain_exists) {
            throw new ValidationException(sprintf('The domain name %s is already exists.', $command->getDomainName()), 'domain');
        }

        $domain = Scanner::addDomain($command->getDomainName(), $command->getExpectedStatus());

        $this->scanner_repo->update($domain);
    }
}
