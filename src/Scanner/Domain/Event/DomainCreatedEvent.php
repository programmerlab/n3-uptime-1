<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Domain\Event;

use Acquia\N3\Domain\AbstractEvent;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;

/**
 * Records that a domain created.
 *
 */
class DomainCreatedEvent extends AbstractEvent
{
    /**
     * The domain name.
     *
     * @var DomainName
     *
     */
    protected $domain_name;


    /**
     * The expected status.
     *
     * @var int expected_status
     *
     */
    protected $expected_status;


    /**
     * Constructor.
     * @param DomainName $domain_name
     *   The Domain name.
     *
     */
    public function __construct(DomainName $domain_name, int $expected_status)
    {
        parent::__construct();
        $this->domain_name     = $domain_name;
        $this->expected_status = $expected_status;
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


    /**
     * Retrieves the expected status.
     *
     * @return int
     *   The expected status.
     *
     */
    public function getExpectedStatus(): int
    {
        return $this->expected_status;
    }
}
