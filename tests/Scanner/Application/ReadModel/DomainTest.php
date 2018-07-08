<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Application\ReadModel;

use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use PHPUnit\Framework\TestCase;

/**
 * Tests the domain read model.
 *
 */
class DomainTest extends TestCase
{
    /**
     * Ensures the getters work as expected.
     *
     */
    public function testGetters(): void
    {
        $domain = new DomainName('example.com');
        $data   = [
            'domain'          => (string) $domain,
            'status'          => true,
            'expected_status' => 200,
            'created_at'      => date(DATE_ATOM, 1388516401),
        ];
        $domain = Domain::createFromArray($data);

        $this->assertEquals($data['domain'], $domain->getDomainName());
        $this->assertEquals($data['status'], $domain->getStatus());
        $this->assertEquals($data['expected_status'], $domain->getExpectedStatus());
    }
}
