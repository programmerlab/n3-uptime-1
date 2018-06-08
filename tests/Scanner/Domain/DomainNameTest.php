<?php

declare(strict_types=1);

namespace AcquiaLib2\Test\Domain\Model;

use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the domain name value object.
 *
 */
class DomainNameTest extends TestCase
{
    /**
     * Ensures that the domain name is returned as a string when type cast.
     *
     */
    public function testToString()
    {
        $domain = new DomainName('example.com');
        $this->assertEquals('example.com', (string) $domain);
    }


    /**
     * Ensures that toString returns a lower case version of the domain.
     *
     */
    public function testToStringIsLowerCase()
    {
        $domain = new DomainName('example.com');
        $this->assertEquals('example.com', (string) $domain);
    }


    /**
     * Ensures that getName() returns the domain in the same case it was
     * instantiated as.
     *
     */
    public function testGetName()
    {
        $domain = new DomainName('example.com');
        $this->assertEquals('example.com', $domain->getDomainName());
    }


    /**
     * Ensures that an invalid domain name throws an exception.
     *
     * @expectedException \InvalidArgumentException
     *
     */
    public function testInvalidDomainsThrowExceptions()
    {
        new DomainName('_test');
    }


    /**
     * Ensures that isEqualTo works as expected.
     *
     */
    public function testIsEqualTo()
    {
        $domain1 = new DomainName('example.com');
        $domain2 = new DomainName('example.com');
        $domain3 = new DomainName('example1.com');
        $domain4 = new DomainName('example.com');

        $this->assertTrue($domain1->isEqualTo($domain2));
        $this->assertFalse($domain1->isEqualTo($domain3));
        $this->assertTrue($domain1->isEqualTo($domain4));
    }


    /**
     * Ensures that isValid works as expected, for the most part.
     *
     */
    public function testIsValid()
    {
        $valid = [
            'example.com', 'tesT.cc', 'sub.domain.test', 'test.randomtldname',
        ];

        foreach ($valid as $domain) {
            $this->assertTrue(DomainName::isValid($domain));
        }

        $invalid = [
            '_test', 'things+with+no+dots', '*.wild.card',
        ];

        foreach ($invalid as $domain) {
            $this->assertFalse(DomainName::isValid($domain));
        }
    }


    /**
     * Ensures that isValid fails if the hostname contains an underscore.
     *
     */
    public function testIsValidFailsIfHostnameHasUnderscore()
    {
        $this->assertFalse(DomainName::isValid('shouldfail.c_om'));
    }


    /**
     * Ensures that a domain can be reconstituted from an array as expected.
     *
     */
    public function testCanBeReconstituted()
    {
        $data = [
            'domain_name' => '*.invalid.com',
        ];

        $domain = DomainName::reconstituteFromArray($data);

        $this->assertEquals($data['domain_name'], (string) $domain);
        $this->assertFalse(DomainName::isValid((string) $domain));
    }
}
