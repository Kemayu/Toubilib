<?php
declare(strict_types=1);

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

interface RdvRepositoryInterface
{
   
    public function findCreneauxPraticien(string $praticienId, string $from, string $to): array;

    
    public function findById(string $id): ?array;
}
