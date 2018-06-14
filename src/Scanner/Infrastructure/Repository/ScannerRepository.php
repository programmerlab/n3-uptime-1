<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Infrastructure\Repository;

use Acquia\N3\Framework\Infrastructure\Repository\AbstractDatabaseAwareRepository;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Acquia\N3\Uptime\Scanner\Domain\Scanner;
use Acquia\N3\Uptime\Scanner\Domain\ScannerRepositoryInterface;

/**
 * Repository implementation for the scanner aggregate root.
 *
 */
class ScannerRepository extends AbstractDatabaseAwareRepository implements ScannerRepositoryInterface
{
    /**
     * Get a Scanner object by domain name.
     *
     * @param DomainName $domain_name as String
     *   The event recording the domain name.
     * @return Scanner
     *
     */
    public function getByDomainName(DomainName $domain_name)
    {
        $sql = '
            SELECT
                domain,
                status
            FROM
                domains
            WHERE
                domain = :domain_name
        ';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':domain_name' => (string) $domain_name]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            return null;
        }

        $domain = [
            'domain_name' => $result['domain'],
            'status'      => (bool) $result['status'],
        ];

        return Scanner::reconstituteFromArray($domain);
    }
}
