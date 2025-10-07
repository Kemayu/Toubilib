<?php
declare(strict_types=1);

namespace toubilib\core\application\ports\spi\repositoryInterfaces;;

interface PraticienRepositoryInterface
{
    /**
     * Retourne la liste complète des praticiens
     *
     * @return array
     */

    public function getAllPraticien(): array;

    public function getPraticienById(string $id): ?array;
}