<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Application\Command;

use Acquia\N3\Application\Command\AbstractCommand;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;

/**
 * Enables a Domain.
 *
 */
class EnableDomainCommand extends AbstractCommand
{
    /**
     * The domain name.
     *
     * @var DomainName
     *
     */
    protected $domain_name;

    /**
     * Constructor.
     *
     * @param DomainName $domain_name
     *   The Domain domain name.
     *
     */
    public function __construct(DomainName $domain_name)
    {
        $this->domain_name = $domain_name;
    }

    /**
     * Retrieves the domain name.
     *
     * @return DomainName
     *
     */
    public function getDomainName(): DomainName
    {
        return $this->domain_name;
    }
}
