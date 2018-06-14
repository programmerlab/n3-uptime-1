<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Test\Scanner\Application\Command;

use Acquia\N3\Application\Command\CommandInterface;
use Acquia\N3\Uptime\Scanner\Application\Command\EnableDomainCommand;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use PHPUnit\Framework\TestCase;

/**
 * Tests the enable domain command.
 *
 */
class EnableDomainCommandTest extends TestCase
{
    /**
     * Ensures the command implements the correct interface.
     *
     */
    public function testInterface(): void
    {
        $domain_name = new DomainName('example.com');

        $command = new EnableDomainCommand($domain_name);

        $this->assertInstanceOf(CommandInterface::class, $command);
    }

    /**
     * Ensures the getters work as expected.
     *
     */
    public function testGetters(): void
    {
        $domain_name = new DomainName('example.com');

        $command = new EnableDomainCommand($domain_name);

        $this->assertEquals($domain_name, $command->getDomainName());
    }
}
