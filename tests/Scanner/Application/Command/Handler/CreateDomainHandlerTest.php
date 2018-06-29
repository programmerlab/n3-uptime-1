<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Test\Scanner\Application\Command\Handler;

use Acquia\N3\Application\Command\Handler\HandlerInterface;
use Acquia\N3\Uptime\Scanner\Application\Command\CreateDomainCommand;
use Acquia\N3\Uptime\Scanner\Application\Command\Handler\CreateDomainHandler;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Acquia\N3\Uptime\Scanner\Domain\Scanner;
use Acquia\N3\Uptime\Scanner\Domain\ScannerRepositoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * Handles an update scanner command.
 *
 */
class CreateDomainHandlerTest extends TestCase
{
    /**
     * Ensures that the handler implements the correct interface.
     *
     */
    public function testInterface(): void
    {
        $user_repo = $this->createMock(ScannerRepositoryInterface::class);
        $handler   = new CreateDomainHandler($user_repo);
        $this->assertInstanceOf(HandlerInterface::class, $handler);
    }

    /**
     * Ensures the command is handled correctly.
     *
     */
    public function testHandle(): void
    {
        $domain_name = new DomainName('example.com');
        $command     = $this->createMock(CreateDomainCommand::class);
        $scan_repo   = $this->createMock(ScannerRepositoryInterface::class);

        $command->method('getDomainName')
            ->willReturn($domain_name);
        $command->method('getExpectedStatus')
            ->willReturn(200);

        $scan_repo->method('getByDomainName')
            ->willReturn(null);

        $scan_repo->expects($this->once())
            ->method('update');
        $handler = new CreateDomainHandler($scan_repo);
        $handler->handle($command);
    }

    /**
     * Ensures the handler throws an exception if the domain is already exists.
     *
     * @expectedException \Acquia\N3\Domain\Exceptions\ValidationException
     *
     */
    public function testHandleThrowsExceptionForDomainAlreadyExists(): void
    {
        $domain_name  = new DomainName('example.com');
        $command      = $this->createMock(CreateDomainCommand::class);
        $scanner_repo = $this->createMock(ScannerRepositoryInterface::class);
        $scanner_repo->method('getByDomainName')
            ->willReturn($this->createMock(Scanner::class));

        $command->method('getDomainName')
            ->willReturn($domain_name);
        $command->method('getExpectedStatus')
            ->willReturn(200);

        $handler = new CreateDomainHandler($scanner_repo);
        $handler->handle($command);
    }
}
