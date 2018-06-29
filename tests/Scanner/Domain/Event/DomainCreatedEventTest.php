<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Domain\Event;

use Acquia\N3\Domain\EventInterface;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use PHPUnit\Framework\TestCase;

/**
 * Records that a domain deleted.
 *
 */
class DomainCreatedEventTest extends TestCase
{
    /**
     * Ensures the event implements the correct interface.
     *
     */
    public function testInterface(): void
    {
        $domain_name     = new DomainName('example.com');
        $expected_status = 200;
        $event           = new DomainCreatedEvent($domain_name, $expected_status);

        $this->assertInstanceOf(EventInterface::class, $event);
    }


    /**
     * Ensures the getters work as expected.
     *
     */
    public function testGetters(): void
    {
        $domain_name     = new DomainName('example.com');
        $expected_status = 200;
        $event           = new DomainCreatedEvent($domain_name, $expected_status);

        $this->assertEquals($domain_name, $event->getDomainName());
    }
}
