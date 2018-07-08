<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Application\Finder;

use Acquia\N3\Uptime\Scanner\Application\ReadModel\Domain;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;

/**
 * Defines a read model finder for domains.
 *
 */
interface DomainFinderInterface
{
    /**
     * Retrieve a domain by domain.
     *
     * @param DomainName $domain
     *   The DomainName of the domain.
     *
     * @return ?Domain
     *   The read model of the domain if found, null otherwise.
     *
     */
    public function getByDomainName(DomainName $domain): ?Domain;
}
