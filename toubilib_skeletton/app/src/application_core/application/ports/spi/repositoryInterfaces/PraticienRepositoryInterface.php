<?php
declare(strict_types=1);

namespace toubilib\core\application\ports\spi\repositoryInterfaces;;

interface PraticienRepositoryInterface
{
    /**
     * Retourne la liste complète des praticiens.
     * Chaque élément doit être soit une instance de \toubilib\core\domain\entities\praticien\Praticien
     * soit un tableau compatible avec Praticien::fromArray().
     *
     * @return array
     */
}