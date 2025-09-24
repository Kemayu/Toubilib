<?php
declare(strict_types=1);

namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\spi\repositoryInterfaces\ServiceRendezVousInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;

class ServiceRendezVous implements ServiceRendezVousInterface
{
    private RdvRepositoryInterface $rdvRepository;

    public function __construct(RdvRepositoryInterface $rdvRepository)
    {
        $this->rdvRepository = $rdvRepository;
    }

    public function listerCreneauxPraticien(string $praticienId, string $from, string $to): array
    {
        // Minimal business logic for now: delegate to repository
        return $this->rdvRepository->findCreneauxPraticien($praticienId, $from, $to);
    }

    public function getRdvById(string $id): ?array
    {
        return $this->rdvRepository->findById($id);
    }
}
