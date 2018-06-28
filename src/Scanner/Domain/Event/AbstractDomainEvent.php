<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Domain\Event;

use Acquia\N3\Domain\AbstractEvent;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;

/**
 * Abstract class for the domain event.
 *
 */
abstract class AbstractDomainEvent extends AbstractEvent
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
    public function __construct($domain_name)
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
    public function getDomainName()
    {
        return $this->domain_name;
    }
}
