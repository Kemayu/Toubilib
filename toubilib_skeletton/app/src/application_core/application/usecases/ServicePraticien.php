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

        $result = $this->praticienRepository->findAll();

        if (!is_array($result)) {
            return [];
        }

        $praticiens = [];
        foreach ($result as $item) {
            if ($item instanceof Praticien) {
                $praticiens[] = $item;
                continue;
            }

            if (is_array($item)) {
                $praticiens[] = Praticien::fromArray($item);
                continue;
            }

        }

        return $praticiens;
    }
}