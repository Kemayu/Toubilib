<?php
declare(strict_types=1);

namespace toubilib\core\application\usecases;

use toubilib\core\domain\entities\praticien\Praticien;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServicePraticienInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
class ServicePraticien implements ServicePraticienInterface
{
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(PraticienRepositoryInterface $praticienRepository)
    {
        $this->praticienRepository = $praticienRepository;
    }

    /**
     * @return Praticien[] 
     */
    public function listerPraticiens(): array
    {       
        return $this->praticienRepository->getAllPraticien();
    }
}