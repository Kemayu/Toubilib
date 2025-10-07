<?php
declare(strict_types=1);

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

interface RdvRepositoryInterface
{
    public function findCreneauxPraticien(string $praticienId, string $from, string $to): array;

    public function findById(string $id): ?array;

    public function saveRendezVous(array $data): ?string;

    public function existsPraticienById(string $praticienId): bool;

    public function existsPatientById(string $patientId): bool;

    public function getMotifsForPraticien(string $praticienId): array;

    public function updateRendezVous(string $id, array $data): bool;

    /**
     * Met à jour le statut d'un rendez-vous
     * 
     * @param string $id ID du rendez-vous
     * @param int $status 0=planifié, 1=annulé, 2=honoré, 3=non_honoré
     * @return bool true si la mise à jour a réussi
     */
    public function updateStatus(string $id, int $status): bool;
}
