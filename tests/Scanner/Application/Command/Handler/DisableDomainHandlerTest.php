<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Test\Scanner\Application\Command\Handler;

use Acquia\N3\Application\Command\Handler\HandlerInterface;
use Acquia\N3\Uptime\Scanner\Application\Command\DisableDomainCommand;
use Acquia\N3\Uptime\Scanner\Application\Command\Handler\DisableDomainHandler;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Acquia\N3\Uptime\Scanner\Domain\Scanner;
use Acquia\N3\Uptime\Scanner\Domain\ScannerRepositoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the DisableDomainHandler.
 *
 */
class DisableDomainHandlerTest extends TestCase
{
    /**
     * Ensures that the handler implements the correct interface.
     *
     */
    public function testInterface(): void
    {
        $scan_repo = $this->createMock(ScannerRepositoryInterface::class);

        $handler = new DisableDomainHandler($scan_repo);

        $this->assertInstanceOf(HandlerInterface::class, $handler);
    }
    /**
     * Ensures the command is handled correctly.
     *
     */
    public function testHandle(): void
    {
        $domain_name = new DomainName('example.com');

        $command      = $this->createMock(DisableDomainCommand::class);
        $scanner      = $this->createMock(Scanner::class);
        $scanner_repo = $this->createMock(ScannerRepositoryInterface::class);

        $command->method('getDomainName')
            ->willReturn($domain_name);

        $scanner_repo->method('getByDomainName')
            ->willReturn($scanner);
        $scanner_repo->expects($this->once())
            ->method('update');

        $handler = new DisableDomainHandler($scanner_repo);

        $handler->handle($command);
    }
    /**
     * Ensures the handler throws an exception if the domain cannot be found.
     *
     * @expectedException \Acquia\N3\Domain\Exceptions\NotFoundException
     *
     */
    public function testHandleThrowsExceptionForMissingDomain(): void
    {
        $domain_name = new DomainName('example.com');

        $command   = $this->createMock(DisableDomainCommand::class);
        $scan_repo = $this->createMock(ScannerRepositoryInterface::class);

        $command->method('getDomainName')
            ->willReturn($domain_name);

        $handler = new DisableDomainHandler($scan_repo);

        $handler->handle($command);
    }
}
