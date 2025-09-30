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
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
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

    public function saveRendezVous(array $data): ?string
    {
        $sql = 'INSERT INTO rdv
            (id, praticien_id, patient_id, date_heure_debut, date_heure_fin, duree, motif_visite, date_creation, patient_email)
            VALUES (:id, :praticien_id, :patient_id, :date_heure_debut, :date_heure_fin, :duree, :motif_visite, :date_creation, :patient_email)';

        $stmt = $this->pdo->prepare($sql);

        $params = [
            ':id' => $data['id'],
            ':praticien_id' => $data['praticien_id'],
            ':patient_id' => $data['patient_id'],
            ':date_heure_debut' => $data['date_heure_debut'],
            ':date_heure_fin' => $data['date_heure_fin'] ?? null,
            ':duree' => $data['duree'],
            ':motif_visite' => $data['motif_visite'] ?? null,
            ':date_creation' => $data['date_creation'] ?? (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            ':patient_email' => $data['patient_email'] ?? null,
        ];

        try {
            $ok = $stmt->execute($params);
            if ($this->logger) {
                $this->logger->debug('PgRdvRepository: saved rdv', ['id' => $data['id'], 'ok' => $ok]);
            }
            return $ok ? $data['id'] : null;
        } catch (\Throwable $e) {
            if ($this->logger) {
                $this->logger->error('PgRdvRepository: save failed', ['error' => $e->getMessage()]);
            }
            return null;
        }
    }

    public function existsPraticienById(string $praticienId): bool
    {
        $praticienId = trim((string)$praticienId);
        if ($praticienId === '') {
            return false;
        }
        $sql = 'SELECT 1 FROM praticien WHERE id = :id LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $praticienId]);
        return (bool)$stmt->fetchColumn();
    }

    public function existsPatientById(string $patientId): bool
    {
        $patientId = trim((string)$patientId);
        if ($patientId === '') {
            return false;
        }
        $tables = ['patient', 'patients', 'dossiers', 'dossier'];
        foreach ($tables as $t) {
            try {
                $sql = "SELECT 1 FROM {$t} WHERE id = :id LIMIT 1";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(['id' => $patientId]);
                if ($stmt->fetchColumn()) {
                    return true;
                }
            } catch (\PDOException $e) {
                continue;
            }
        }
        return false;
    }

    public function getMotifsForPraticien(string $praticienId): array
    {
        try {
            $sql = '
                SELECT mv.id AS motif_id, mv.libelle
                FROM praticien2motif p2m
                JOIN motif_visite mv ON mv.id = p2m.motif_id
                WHERE p2m.praticien_id = :pid
            ';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['pid' => $praticienId]);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $out = [];
            foreach ($rows as $r) {
                $out[] = [
                    'id' => (string)($r['motif_id'] ?? ''),
                    'libelle' => (string)($r['libelle'] ?? '')
                ];
            }
            return $out;
        } catch (\PDOException $e) {
            if ($this->logger) {
                $this->logger->warning('getMotifsForPraticien failed', ['praticienId' => $praticienId, 'err' => $e->getMessage()]);
            }
            return [];
        }
    }
}
