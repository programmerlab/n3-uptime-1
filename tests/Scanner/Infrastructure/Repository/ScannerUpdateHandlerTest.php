<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Test\Scanner\Infrastructure\Repository;

use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Acquia\N3\Uptime\Scanner\Domain\Event\DomainDeletedEvent;
use Acquia\N3\Uptime\Scanner\Domain\Event\DomainDisabledEvent;
use Acquia\N3\Uptime\Scanner\Domain\Event\DomainEnabledEvent;
use Acquia\N3\Uptime\Scanner\Infrastructure\Repository\ScannerUpdateHandler;
use PHPUnit\Framework\TestCase;

/**
 * Tests the scanner repository update handler.
 *
 */
class ScannerUpdateHandlerTest extends TestCase
{
    /**
     * Ensures that Domain enable works as expected.
     *
     */
    public function testOnDomainEnabled(): void
    {
        $domain_name = new DomainName('example.com');
        $db          = $this->createMock(\PDO::class);
        $stmt        = $this->createMock(\PDOStatement::class);
        $db->method('prepare')
            ->willReturn($stmt);
        $stmt->expects($this->once())
            ->method('execute');
        $handler = new ScannerUpdateHandler($db);
        $event   = new DomainEnabledEvent($domain_name);
        $handler->handle($event);
    }


    /**
     * Ensures that domain delete works as expected.
     *
     */
    public function testOnDomainDeleted(): void
    {
        $domain_name = new DomainName('example.com');

        $db   = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);

        $db->method('prepare')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('execute');

        $handler = new ScannerUpdateHandler($db);
        $event   = new DomainDeletedEvent($domain_name);
        $handler->handle($event);
    }


    /**
     * Ensures that domain disable works as expected.
     *
     */
    public function testOnDomainDisabled(): void
    {
        $domain_name = new DomainName('example.com');

        $db   = $this->createMock(\PDO::class);
        $stmt = $this->createMock(\PDOStatement::class);

        $db->method('prepare')
            ->willReturn($stmt);

        $stmt->expects($this->once())
            ->method('execute');

        $handler = new ScannerUpdateHandler($db);

        $event   = new DomainDisabledEvent($domain_name);

        $handler->handle($event);
    }
}
