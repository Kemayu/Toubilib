<?php
declare(strict_types=1);

namespace toubilib\api\actions;

use jira\api\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServicePraticienInterface;


 class PraticienAction extends AbstractAction
{
       protected ServicePraticienInterface $praticienService;

    public function __construct(ServicePraticienInterface $praticienService) {
        $this->praticienService = $praticienService;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        $praticiens = $this->praticienService->listerPraticiens();
        $praticiensArray = array_map(function($praticien) {
            return [
                'id' => $praticien->getId(),
                'nom' => $praticien->getNom(),
                'prenom' => $praticien->getPrenom(),
                'ville' => $praticien->getVille(),
                'email' => $praticien->getEmail(),
                'specialite_id' => $praticien->getSpecialite(),
            ];
        }, $praticiens);

        $rs->getBody()->write(json_encode($praticiensArray));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}