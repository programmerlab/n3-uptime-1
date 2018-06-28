<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Domain;

use Acquia\N3\Domain\AbstractEntity;
use Acquia\N3\Domain\EntityInterface;
use Acquia\N3\Domain\Exceptions\ValidationException;
use Acquia\N3\Uptime\Scanner\Domain\Event\DomainDeletedEvent;
use Acquia\N3\Uptime\Scanner\Domain\Event\DomainDisabledEvent;
use Acquia\N3\Uptime\Scanner\Domain\Event\DomainEnabledEvent;

/**
 * Represents a Scanner.
 *
 */
class Scanner extends AbstractEntity
{
    /**
     * The domain name.
     *
     * @var DomainName
     *
     */
    protected $domain_name;


    /**
     * Whether the scanner is active.
     *
     * @var status
     *
     */
    protected $status;


    /**
     * Constructor.
     *
     * @param DomainName $domain_name
     *   The domainName of the domain.
     * @param bool $status
     *   Whether this domain is enable or disable.
     *
     */
    public function __construct(DomainName $domain_name, bool $status)
    {
        $this->domain_name = $domain_name;
        $this->status      = $status;
    }


    /**
     * Returns the domain name.
     *
     * @return DomainName
     *
     */
    public function getDomainName(): DomainName
    {
        return $this->domain_name;
    }


    /**
     * Enable the domain's name.
     *
     * @param DomainName $domain_name
     *   The domain name.
     *
     * @param DomainName $status
     *   The status of the domain.
     *
     * @return void
     *
     */
    public function enableDomain(): void
    {
        if ($this->status) {
            throw new ValidationException('This domain is already enabled.', 'domain');
        }

        $this->raise(new DomainEnabledEvent($this->domain_name));
    }


    /**
     * Disable the domain's name.
     *
     * @throws ValidationException
     *   Thrown when the domain is already disabled.
     *
     */
    public function disableDomain(): void
    {
        if (!$this->status) {
            throw new ValidationException('This domain is already disabled.', 'domain');
        }

        $this->raise(new DomainDisabledEvent($this->domain_name));
    }

    /**
     * Delete the domain.
     *
     * @param DomainName $domain_name
     *   The domain name.
     *
     * @return void
     *
     */
    public function delete(): void
    {
        $this->raise(new DomainDeletedEvent($this->domain_name));
    }

    /**
     * {@inheritdoc}
     *
     */
    public function isEqualTo(EntityInterface $entity): bool
    {
        return ($entity instanceof Scanner) && $this->domain_name === $entity->getDomainName();
    }
}
