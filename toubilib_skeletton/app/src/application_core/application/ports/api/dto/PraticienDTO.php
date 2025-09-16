<?php
declare(strict_types=1);

namespace toubilib\core\application\dto;

use toubilib\core\domain\entities\praticien\Praticien;

 class PraticienDTO
{
    private Praticien $praticien;

    public function __construct(Praticien $praticien) {
        $this->praticien = $praticien;
    }

    public function getNewStatus(): Praticien {
        return $this->praticien;
    }

}