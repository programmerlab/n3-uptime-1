<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Test\Scanner\Domain;

use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Acquia\N3\Uptime\Scanner\Domain\Event\DomainDisabledEvent;
use Acquia\N3\Uptime\Scanner\Domain\Event\DomainEnabledEvent;
use Acquia\N3\Uptime\Scanner\Domain\Scanner;
use PHPUnit\Framework\TestCase;

/**
 * Test for the Scanner class.
 *
 */
class ScannerTest extends TestCase
{
    /**
     * Ensures that Getters works as expected.
     *
     */
    public function testGetters()
    {
        $domain_name = new DomainName('acquia.com');
        $scanner     = new Scanner($domain_name, true);

        $this->assertEquals($domain_name, $scanner->getDomainName());
    }


    /**
     * Ensures that EnableDomain works as expected.
     *
     */
    public function testEnableDomain()
    {
        $domain_name = new DomainName('acquia.com');
        $scanner     = new Scanner($domain_name, false);

        $scanner->enableDomain();

        $events = $scanner->getEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(DomainEnabledEvent::class, $events[0]);
    }


    /**
     * Ensures that EnabledDomainWhenAlreadyEnabled() throws an exception when the domain is already enabled.
     *
     * @expectedException \Acquia\N3\Domain\Exceptions\ValidationException
     */
    public function testEnabledDomainWhenAlreadyEnabled()
    {
        $domain_name = new DomainName('acquia.com');
        $scanner     = new Scanner($domain_name, true);

        $scanner->enableDomain();
    }

    /**
     * Ensures that DisableDomain works as expected.
     *
     */
    public function testDisableDomain()
    {
        $domain_name = new DomainName('acquia.com');
        $scanner     = new Scanner($domain_name, true);

        $scanner->disableDomain();

        $events = $scanner->getEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(DomainDisabledEvent::class, $events[0]);
    }


    /**
     * Ensures that DisableDomainThrowsExceptionWhenDisabled() throws an exception when the domain is already disabled.
     *
     * @expectedException \Acquia\N3\Domain\Exceptions\ValidationException
     */
    public function testDisableDomainThrowsExceptionWhenDisabled()
    {
        $domain_name = new DomainName('acquia.com');
        $scanner     = new Scanner($domain_name, false);

        $scanner->disableDomain();
    }


    /**
     * Ensures that isEqualTo works as expected.
     *
     */
    public function testIsEqualTo()
    {
        $domain_name = new DomainName('acquia.com');
        $scanner     = new Scanner($domain_name, true);

        $other_scanner = clone $scanner;

        $this->assertTrue($scanner->isEqualTo($other_scanner));
    }


    /**
     * Ensures that IsEqualToReturnsFalseWhenDifferentDomains return false when domain is Different.
     *
     */
    public function testIsEqualToReturnsFalseWhenDifferentDomains()
    {
        $domain_name   = new DomainName('example.com');
        $scanner       = new Scanner($domain_name, true);

        $domain_name   = new DomainName('example.org');
        $other_scanner = new Scanner($domain_name, true);


        $this->assertFalse($scanner->isEqualTo($other_scanner));
    }
}
