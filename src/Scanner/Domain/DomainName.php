<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Domain;

use Acquia\N3\Domain\AbstractValueObject;

/**
 * Represents a domain name.
 *
 */
class DomainName extends AbstractValueObject
{
    /**
     * The domain name.
     *
     * @var string
     *
     */
    protected $domain_name;


    /**
     * Constructor
     *
     * @param string $name
     *   The domain name.
     *
     * @throws \InvalidArgumentException
     *
     */
    public function __construct(string $domain_name)
    {
        if (!static::isValid($domain_name)) {
            throw new \InvalidArgumentException(sprintf('The domain name "%s" is not valid.', $domain_name));
        }

        $this->domain_name = $domain_name;
    }


    /**
     * Determines if the domain name is valid. This is a very basic check.
     *
     * @param string $domain_name
     *   The domain name to check.
     *
     * @return bool
     *
     */
    public static function isValid(string $domain_name): bool
    {
        return (bool) filter_var($domain_name, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
    }


    /**
     * Returns the domain name as a string.
     *
     * @return string
     *
     */
    public function getDomainName(): string
    {
        return $this->domain_name;
    }


    /**
     * Returns the string representation of the domain name. Unlike getDomainName(),
     * this is a lower cased version for comparison purposes.
     *
     * @return string
     *
     */
    public function __toString(): string
    {
        return strtolower($this->domain_name);
    }
}
