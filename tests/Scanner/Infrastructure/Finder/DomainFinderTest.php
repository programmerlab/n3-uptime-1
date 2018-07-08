<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Test\People\Infrastructure\Finder;

use Acquia\N3\Uptime\Scanner\Application\ReadModel\Domain;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Acquia\N3\Uptime\Scanner\Infrastructure\Finder\DomainFinder;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Tests the domain finder.
 *
 */
class DomainFinderTest extends TestCase
{
    /**
     * Ensures getByDomainName returns a domain read model.
     *
     */
    public function testGetByDomainName(): void
    {
        $domain_name = new DomainName('example.com');

        $domain          = $domain_name;
        $status          = true;
        $expected_status = 200;
        $created_at      = date(DATE_ATOM, 1388516401);

        $db     = $this->createMock(\PDO::class);
        $logger = $this->createMock(LoggerInterface::class);
        $stmt   = $this->createMock(\PDOStatement::class);

        $db->method('prepare')
            ->willReturn($stmt);

        $stmt->method('fetch')
            ->willReturn([
                'domain'          => (string) $domain,
                'status'          => $status,
                'expected_status' => $expected_status,
                'created_at'      => $created_at,
            ]);

        $finder = new DomainFinder($db, $logger);
        $domain = $finder->getByDomainName($domain);

        $this->assertInstanceOf(Domain::class, $domain);
    }

    /**
     * Ensures that getByDomainNameForMissingDomain() returns null if the domain cannot be found.
     *
     */
    public function testGetByDomainNameForMissingDomain(): void
    {
        $domain_name = new DomainName('example.com');

        $db     = $this->createMock(\PDO::class);
        $logger = $this->createMock(LoggerInterface::class);
        $stmt   = $this->createMock(\PDOStatement::class);

        $db->method('prepare')
            ->willReturn($stmt);

        $finder = new DomainFinder($db, $logger);

        $domain = $finder->getByDomainName($domain_name);

        $this->assertNull($domain);
    }

    /**
     * Ensures that getByDomainNameForInvalidDomain() returns null if the underlying domain is invalid.
     *
     */
    public function testGetByDomainNameForInvalidDomain(): void
    {
        $domain_name     = new DomainName('example.com');

        $status          = true;
        $expected_status = 200;
        $created_at      = date(DATE_ATOM, 1388516401);

        $db     = $this->createMock(\PDO::class);
        $logger = $this->createMock(LoggerInterface::class);
        $stmt   = $this->createMock(\PDOStatement::class);

        $db->method('prepare')
            ->willReturn($stmt);

        $stmt->method('fetch')
            ->willReturn([
                'status'          => $status,
                'expected_status' => $expected_status,
                'created_at'      => $created_at,
            ]);

        $finder = new DomainFinder($db, $logger);

        $domain = $finder->getByDomainName($domain_name);

        $this->assertNull($domain);
    }
}
