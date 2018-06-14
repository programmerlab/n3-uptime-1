<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Infrastructure\Repository;

use Acquia\N3\Framework\Infrastructure\Repository\AbstractUpdateHandler;
use Acquia\N3\Uptime\Scanner\Domain\Event\DomainEnabledEvent;

/**
 * Handlers for updating the Scanner repository.
 *
 */
class ScannerUpdateHandler extends AbstractUpdateHandler
{
    /**
     * Updates a domain's status.
     *
     * @param DomainEnabledEvent $event
     *   The event recording the domain status change.
     *
     * @return void
     *
     */
    public function onDomainEnabled(DomainEnabledEvent $event): void
    {
        $sql = '
            UPDATE
                domains
            SET
                status = 1
            WHERE
                domain = :domain_name
        ';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':domain_name' => (string) $event->getDomainName(),
        ]);
    }
}
