<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Test\Scanner\Infrastructure\Repository;

use Acquia\N3\Application\Event\Bus\EventBusInterface;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Acquia\N3\Uptime\Scanner\Domain\Scanner;
use Acquia\N3\Uptime\Scanner\Infrastructure\Repository\ScannerRepository;
use Acquia\N3\Uptime\Scanner\Infrastructure\Repository\ScannerUpdateHandler;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Tests the scanner repository.
 *
 */
class ScannerRepositoryTest extends TestCase
{
    /**
     * Ensures a domain can be retrieved by domain_name.
     *
     */
    public function testGetByDomainName(): void
    {
        $domain_name = new DomainName('example.com');
        $status      = true;

        $db        = $this->createMock(\PDO::class);
        $event_bus = $this->createMock(EventBusInterface::class);
        $handler   = $this->createMock(ScannerUpdateHandler::class);
        $logger    = $this->createMock(LoggerInterface::class);
        $stmt      = $this->createMock(\PDOStatement::class);

        $db->method('prepare')
            ->willReturn($stmt);

        $stmt->method('fetch')
            ->willReturn([
                'domain' => (string) $domain_name,
                'status' => $status,
            ]);

        $scan_repo    = new ScannerRepository($db, $event_bus, $handler, $logger);
        $scanner      = $scan_repo->getByDomainName($domain_name);

        $this->assertInstanceOf(Scanner::class, $scanner);
        $this->assertEquals($domain_name, $scanner->toArray()['domain_name']);
    }
}
