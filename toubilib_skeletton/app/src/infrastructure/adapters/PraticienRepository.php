<?php
declare(strict_types=1);

namespace toubilib\infra\adapters;

use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;

/**
 * Entité domaine Praticien (architecture hexagonale)
 * Champs exposés pour la fonctionnalité 1 : nom, prénom, ville, email, spécialité.
 */
 class PraticienRepository implements PraticienRepositoryInterface
{
    public function findAll(): array{
        
    }
}