<?php
declare(strict_types=1);

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

interface AuthRepositoryInterface
{
    /**
     * Récupère un utilisateur par email
     * 
     * @param string $email
     * @return array|null 
     */
    public function findUserByEmail(string $email): ?array;
}