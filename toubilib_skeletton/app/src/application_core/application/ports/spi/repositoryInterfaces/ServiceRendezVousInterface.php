<?php
declare(strict_types=1);

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

interface ServiceRendezVousInterface
{
    /**
     * @return array<int,array<string,mixed>>
     */
    public function listerCreneauxPraticien(string $praticienId, string $from, string $to): array;

    /**
     * @return array<string,mixed>|null
     */
    public function getRdvById(string $id): ?array;
}
