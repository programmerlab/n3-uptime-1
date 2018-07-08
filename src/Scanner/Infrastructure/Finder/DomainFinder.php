<?php

declare(strict_types=1);

namespace Acquia\N3\Uptime\Scanner\Infrastructure\Finder;

use Acquia\N3\Framework\Infrastructure\Database\DatabaseAwareTrait;
use Acquia\N3\Uptime\Scanner\Application\Finder\DomainFinderInterface;
use Acquia\N3\Uptime\Scanner\Application\ReadModel\Domain;
use Acquia\N3\Uptime\Scanner\Domain\DomainName;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Implementation of a domain finder.
 *
 */
class DomainFinder implements DomainFinderInterface
{
    use DatabaseAwareTrait;
    use LoggerAwareTrait;


    /**
     * Constructor.
     *
     * @param \PDO $db
     *   A database connection.
     * @param LoggerInterface $logger
     *   A logger.
     *
     */
    public function __construct(\PDO $db, LoggerInterface $logger)
    {
        $this->db     = $db;
        $this->logger = $logger;
    }


    /**
     * {@inheritdoc}
     *
     */
    public function getByDomainName(DomainName $domain): ?Domain
    {
        $sql = '
            SELECT
                domains.domain,
                domains.status,
                domains.expected_status,
                domains.created AS created_at
            FROM
                domains
            WHERE
                domain = :domain
        ';

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':domain' => (string) $domain]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$result) {
            $this->logger->debug('Could not find domain with Domain {domain}', [
                'domain' => (string) $domain,
            ]);

            return null;
        }

        return $this->createDomainReadModel($result);
    }

    /**
     * Creates a domain read model from the provided array of domain data.
     *
     * @param array $data
     *   An array of raw domain data as returned by the database.
     *
     * @return ?Domain
     *   The domain read model if the data is valid, null otherwise.
     *
     */
    protected function createDomainReadModel(array $data)
    {
        if (!isset($data['domain'])) {
            return null;
        }

        $date               = (int) $data['created_at'];
        $data['created_at'] = date(DATE_ATOM, $date);

        return Domain::createFromArray($data);
    }
}
