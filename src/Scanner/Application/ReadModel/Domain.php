<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Application\ReadModel;

use Acquia\N3\Application\ReadModel\AbstractReadModel;

/**
 * A read model for a domain.
 *
 */
class Domain extends AbstractReadModel
{
    /**
     * The domain name.
     *
     * @var string
     *
     */
    protected $domain;


    /**
     * The expected status.
     *
     * @var int
     *
     */
    protected $expected_status;


    /**
     * The status.
     *
     * @var bool
     *
     */
    protected $status;


    /**
     * The ISO-8601 date/time of when the domain was created.
     *
     * @var string
     *
     */
    protected $created_at;


    /**
     * Retrieves the DomainName of the domain.
     *
     * @return string
     *
     */
    public function getDomainName(): string
    {
        return $this->domain;
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


    /**
     * Retrieves the domain status.
     *
     * @return bool
     *
     */
    public function getStatus(): bool
    {
        return $this->status;
    }


    /**
     * Returns the ISO-8601 date/time of when the domain was created.
     *
     * @return string
     *
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
}
