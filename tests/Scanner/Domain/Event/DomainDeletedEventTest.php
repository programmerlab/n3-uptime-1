<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Test\Scanner\Domain\Event;

use Acquia\N3\Domain\EventInterface;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Acquia\N3\Uptime\Scanner\Domain\Event\DomainDeletedEvent;
use PHPUnit\Framework\TestCase;

/**
 * Records that a domain deleted.
 *
 */
class DomainDeletedEventTest extends TestCase
{
    /**
     * Ensures the event implements the correct interface.
     *
     */
    public function testInterface(): void
    {
        $domain_name = new DomainName('example.com');
        $event       = new DomainDeletedEvent($domain_name);

        $this->assertInstanceOf(EventInterface::class, $event);
    }


    /**
     * Ensures the getters work as expected.
     *
     */
    public function testGetters(): void
    {
        $domain_name = new DomainName('example.com');
        $event       = new DomainDeletedEvent($domain_name);

        $this->assertEquals($domain_name, $event->getDomainName());
    }
}
