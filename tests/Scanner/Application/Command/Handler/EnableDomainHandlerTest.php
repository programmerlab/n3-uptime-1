<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Test\Scanner\Application\Command\Handler;

use Acquia\N3\Application\Command\Handler\HandlerInterface;
use Acquia\N3\Uptime\Scanner\Application\Command\EnableDomainCommand;
use Acquia\N3\Uptime\Scanner\Application\Command\Handler\EnableDomainHandler;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Acquia\N3\Uptime\Scanner\Domain\Scanner;
use Acquia\N3\Uptime\Scanner\Domain\ScannerRepositoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Tests for an enable domain command handler.
 *
 */
class EnableDomainHandlerTest extends TestCase
{
    /**
     * Ensures that the handler implements the correct interface.
     *
     */
    public function testInterface(): void
    {
        $scan_repo = $this->createMock(ScannerRepositoryInterface::class);

        $handler = new EnableDomainHandler($scan_repo);

        $this->assertInstanceOf(HandlerInterface::class, $handler);
    }

    /**
     * Ensures the command is handled correctly.
     *
     */
    public function testHandle(): void
    {
        $doamin_name = new DomainName('example.com');

        $command     = $this->createMock(EnableDomainCommand::class);
        $scanner     = $this->createMock(Scanner::class);
        $scan_repo   = $this->createMock(ScannerRepositoryInterface::class);

        $command->method('getDomainName')
            ->willReturn($doamin_name);

        $scan_repo->method('getByDomainName')
            ->willReturn($scanner);
        $scan_repo->expects($this->once())
            ->method('update');

        $handler = new EnableDomainHandler($scan_repo);

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

        $command   = $this->createMock(EnableDomainCommand::class);
        $scan_repo = $this->createMock(ScannerRepositoryInterface::class);

        $command->method('getDomainName')
            ->willReturn($domain_name);

        $handler = new EnableDomainHandler($scan_repo);

        $handler->handle($command);
    }
}
