<?php
declare(strict_types=1);

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

interface RdvRepositoryInterface
{
    /**
     * Retourne les créneaux déjà réservés pour un praticien entre deux datetime (format ISO ou SQL)
     * @param string $praticienId
     * @param string $from
     * @param string $to
     * @return array<int, array<string,mixed>> liste de créneaux (clefs: id, date_heure_debut, date_heure_fin, duree, status, motif_visite)
     */
    public function findCreneauxPraticien(string $praticienId, string $from, string $to): array;
}
