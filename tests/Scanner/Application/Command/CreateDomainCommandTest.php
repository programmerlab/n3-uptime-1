<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Test\Scanner\Application\Command;

use Acquia\N3\Application\Command\CommandInterface;
use Acquia\N3\Uptime\Scanner\Application\Command\CreateDomainCommand;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use PHPUnit\Framework\TestCase;

/**
 * test delete a Domain.
 *
 */
class CreateDomainCommandTest extends TestCase
{
    /**
     * Ensures the command implements the correct interface.
     *
     */
    public function testInterface(): void
    {
        $domain_name     = new DomainName('example.com');
        $expected_status = 200;
        $command         = new CreateDomainCommand($domain_name, $expected_status);
        $this->assertInstanceOf(CommandInterface::class, $command);
    }

    /**
     * Ensures the getters work as expected.
     *
     */
    public function testGetters(): void
    {
        $domain_name     = new DomainName('example.com');
        $expected_status = 200;
        $command         = new CreateDomainCommand($domain_name, $expected_status);
        $this->assertEquals($domain_name, $command->getDomainName());
        $this->assertEquals($expected_status, $command->getExpectedStatus());
    }
}
