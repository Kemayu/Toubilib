<?php
declare(strict_types=1);

namespace toubilib\infra\repositories;

use Psr\Log\LoggerInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;

class PgRdvRepository implements RdvRepositoryInterface
{
    private \PDO $pdo;
    private ?LoggerInterface $logger;

    public function __construct(\PDO $pdo, ?LoggerInterface $logger = null)
    {
        $this->pdo = $pdo;
        $this->logger = $logger;
    }

    public function findCreneauxPraticien(string $praticienId, string $from, string $to): array
    {
        $sql = 'SELECT id, praticien_id, patient_id, patient_email, date_heure_debut, date_heure_fin, duree, status, motif_visite
                FROM rdv
                WHERE praticien_id = :praticien_id
                  AND date_heure_debut >= :from
                  AND date_heure_debut <= :to
                ORDER BY date_heure_debut ASC';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':praticien_id' => $praticienId,
            ':from' => $from,
            ':to' => $to,
        ]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if ($this->logger) {
            $this->logger->debug('PgRdvRepository: fetched creneaux', ['count' => count($rows), 'praticien' => $praticienId, 'from' => $from, 'to' => $to]);
        }

        return $rows ?: [];
    }

    public function findById(string $id): ?array
    {
        $sql = 'SELECT id, praticien_id, patient_id, patient_email, date_heure_debut, date_heure_fin, duree, status, motif_visite, date_creation
                FROM rdv
                WHERE id = :id
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($this->logger) {
            $this->logger->debug('PgRdvRepository: fetched rdv by id', ['id' => $id, 'found' => (bool)$row]);
        }

        return $row ?: null;
    }
}
