<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Application\Command;

use Acquia\N3\Application\Command\AbstractCommand;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;

/**
 * Create Domain.
 *
 */
class CreateDomainCommand extends AbstractCommand
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
     *
     * @param DomainName $domain_name
     *   The domain name.
     *
     */
    public function __construct(DomainName $domain_name, int $expected_status)
    {
        $this->domain_name      = $domain_name;
        $this->expected_status  = $expected_status;
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
     *
     */
    public function getExpectedStatus(): int
    {
        return $this->expected_status;
    }
}
