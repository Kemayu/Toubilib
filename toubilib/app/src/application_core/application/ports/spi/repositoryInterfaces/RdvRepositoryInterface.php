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
}
