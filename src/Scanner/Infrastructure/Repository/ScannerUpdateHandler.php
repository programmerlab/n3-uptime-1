<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Infrastructure\Repository;

use Acquia\N3\Framework\Infrastructure\Repository\AbstractUpdateHandler;
use Acquia\N3\Uptime\Scanner\Domain\Event\DomainDeletedEvent;
use Acquia\N3\Uptime\Scanner\Domain\Event\DomainDisabledEvent;
use Acquia\N3\Uptime\Scanner\Domain\Event\DomainEnabledEvent;

/**
 * Handlers for updating the Scanner repository.
 *
 */
class ScannerUpdateHandler extends AbstractUpdateHandler
{
    /**
     * Disables a domain.
     *
     * @param DomainDisableEvent $event
     *   The event recording the domain status is disable.
     *
     * @return void
     *
     */
    public function onDomainDisabled(DomainDisabledEvent $event): void
    {
        $sql = '
            UPDATE
                domains
            SET
                status = 0
            WHERE
                domain = :domain_name
        ';
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':domain_name' => (string) $event->getDomainName(),
        ]);
    }


    /**
     * Enable a domain.
     *
     * @param DomainEnableEvent $event
     *   The event recording the domain status is disable.
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


    /**
     * Delete domain from scanner.
     *
     * @param DomainDeleteEvent $event
     *   The event recording the domain status change.
     *
     * @return void
     *
     */
    public function onDomainDeleted(DomainDeletedEvent $event): void
    {
        $sql = '
            DELETE
                FROM
                domains
            WHERE
                domain = :domain_name
        ';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':domain_name' => (string) $event->getDomainName(),
        ]);
    }
}
