<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Domain;

use Acquia\N3\Domain\RepositoryInterface;

/**
 * Defines a repository for retrieving domains.
 *
 */
interface ScannerRepositoryInterface extends RepositoryInterface
{
    /**
     * Retrieves an entity based on its domain_name.
     *
     * @param DomainName $domain_name
     *   The DomainName of the entity to retrieve scanner.
     *
     * @return Scanner
     *
     */
    public function getByDomainName(DomainName $domain_name);
}
