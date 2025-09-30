<?php
declare(strict_types=1);

namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\spi\repositoryInterfaces\ServiceRendezVousInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\application\ports\api\dto\InputRendezVousDTO;

class ServiceRendezVous implements ServiceRendezVousInterface
{
    private RdvRepositoryInterface $rdvRepository;

    public function __construct(RdvRepositoryInterface $rdvRepository)
    {
        $this->rdvRepository = $rdvRepository;
    }

    public function listerCreneauxPraticien(string $praticienId, string $from, string $to): array
    {
        return $this->rdvRepository->findCreneauxPraticien($praticienId, $from, $to);
    }

    public function getRdvById(string $id): ?array
    {
        return $this->rdvRepository->findById($id);
    }

    public function creerRendezVous(InputRendezVousDTO $dto): array
    {
        $data = $dto->toArray();

        $praticienId = trim((string)($data['praticien_id'] ?? ''));
        $patientId = trim((string)($data['patient_id'] ?? ''));
        if ($praticienId === '') {
            return ['success' => false, 'code' => 'invalid_input', 'message' => 'praticien_id manquant'];
        }
        if ($patientId === '') {
            return ['success' => false, 'code' => 'invalid_input', 'message' => 'patient_id manquant'];
        }

        try {
            $debut = new \DateTimeImmutable($data['date_heure_debut']);
            $fin = $debut->modify('+' . (int)$data['duree'] . ' minutes');
        } catch (\Throwable $e) {
            return ['success' => false, 'code' => 'invalid_datetime', 'message' => 'Date/heure invalide'];
        }

        // si praticien existe
        if (!$this->rdvRepository->existsPraticienById($data['praticien_id'] ?? '')) {
            return ['success' => false, 'code' => 'praticien_not_found', 'message' => 'Praticien introuvable'];
        }

        // si patient existes 
        if (!$this->rdvRepository->existsPatientById($data['patient_id'] ?? '')) {
            return ['success' => false, 'code' => 'patient_not_found', 'message' => 'Patient introuvable'];
        }

        // motif autorisé pour ce praticien
        $motifs = $this->rdvRepository->getMotifsForPraticien($data['praticien_id'] ?? '');
        $inputMotifRaw = trim((string)($data['motif_visite'] ?? ''));
        $normInput = mb_strtolower($inputMotifRaw, 'UTF-8');
        $found = false;
        if (is_array($motifs) && !empty($motifs)) {
            foreach ($motifs as $m) {
                if (is_array($m)) {
                    if (isset($m['id']) && (string)$m['id'] === $inputMotifRaw) {
                        $found = true;
                        break;
                    }
                    if (isset($m['libelle']) && mb_strtolower(trim((string)$m['libelle']), 'UTF-8') === $normInput) {
                        $found = true;
                        break;
                    }
                } else {
                    if (mb_strtolower(trim((string)$m), 'UTF-8') === $normInput) {
                        $found = true;
                        break;
                    }
                }
            }
            if (!$found) {
                return ['success' => false, 'code' => 'motif_not_allowed', 'message' => 'Motif non autorisé pour ce praticien'];
            }
        }

       //horraire + jour
        $dow = (int)$debut->format('N');
        if ($dow > 5) {
            return ['success' => false, 'code' => 'day_not_allowed', 'message' => 'Jour non ouvré'];
        }
        $boundStart = new \DateTimeImmutable($debut->format('Y-m-d') . ' 08:00:00');
        $boundEnd = new \DateTimeImmutable($debut->format('Y-m-d') . ' 19:00:00');
        if ($debut < $boundStart || $fin > $boundEnd) {
            return ['success' => false, 'code' => 'hour_not_allowed', 'message' => 'Horaire hors plage (08:00-19:00)'];
        }

        // disponibilité 
        $from = $debut->format('Y-m-d 00:00:00');
        $to = $fin->format('Y-m-d 23:59:59');
        $existing = $this->rdvRepository->findCreneauxPraticien($data['praticien_id'], $from, $to);
        foreach ($existing as $row) {
            $exDebut = new \DateTimeImmutable($row['date_heure_debut']);
            $exFin = !empty($row['date_heure_fin']) ? new \DateTimeImmutable($row['date_heure_fin'])
                : $exDebut->modify('+' . ((int)($row['duree'] ?? 0)) . ' minutes');
            if ($debut < $exFin && $fin > $exDebut) {
                return ['success' => false, 'code' => 'praticien_unavailable', 'message' => 'Praticien indisponible pour ce créneau'];
            }
        }

        // préparation et sauvegarde
        $data['date_heure_debut'] = $debut->format('Y-m-d H:i:s');
        $data['date_heure_fin'] = $fin->format('Y-m-d H:i:s');
        $data['date_creation'] = (new \DateTimeImmutable())->format('Y-m-d H:i:s');
        $data['id'] = bin2hex(random_bytes(16));

        $savedId = $this->rdvRepository->saveRendezVous($data);
        if ($savedId === null) {
            return ['success' => false, 'code' => 'save_failed', 'message' => 'Échec sauvegarde RDV'];
        }

        return ['success' => true, 'id' => $savedId];
    }
}
