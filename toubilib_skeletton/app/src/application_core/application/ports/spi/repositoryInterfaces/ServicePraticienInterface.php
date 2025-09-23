<?php
declare(strict_types=1);

namespace toubilib\core\application\ports\spi\repositoryInterfaces;

use toubilib\core\application\ports\api\dto\PraticienDetailDTO;
use toubilib\core\application\ports\api\dto\PraticienDTO;
use toubilib\core\domain\entities\praticien\Praticien;

interface ServicePraticienInterface
{
    /**
     * Retourne la liste complète des praticiens (sans pagination / filtres).
     *
     * @return Praticien[]
     */
    public function listerPraticiens(): array;

    public function getPraticienDetail(string $id): ?PraticienDetailDTO;
}
