<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Domain\Event;

use Acquia\N3\Domain\AbstractEvent;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;

/**
 * Records that a host status was disable.
 *
 */
class DomainDisabledEvent extends AbstractEvent
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
     * @param DomainName $domain_name
     *   The Domain name.
     *
     */
    public function __construct(DomainName $domain_name)
    {
        parent::__construct();

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
