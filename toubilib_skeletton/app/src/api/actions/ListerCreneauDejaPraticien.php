<?php
declare(strict_types=1);

namespace toubilib\api\actions;

use toubilib\api\actions\AbstractAction;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\api\dto\PraticienDTO;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServicePraticienInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\RdvRepositoryInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServiceRendezVousInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Slim\Psr7\Factory\StreamFactory;


 class ListerCreneauDejaPraticien extends AbstractAction
{
    protected ServiceRendezVousInterface $serviceRendezVous;

    public function __construct(ServiceRendezVousInterface $serviceRendezVous) {
        $this->serviceRendezVous = $serviceRendezVous;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface {
        // extraction des paramètres : praticienId en route param ou query, from/to en query
        $praticienId = $args['praticienId'] ?? $rq->getQueryParams()['praticienId'] ?? null;
        $from = $rq->getQueryParams()['from'] ?? null;
        $to = $rq->getQueryParams()['to'] ?? null;

        if (!$praticienId || !$from || !$to) {
            $body = json_encode(['error' => 'missing parameters, require praticienId, from, to']);
            $stream = (new StreamFactory())->createStream($body);
            return $rs->withStatus(400)->withHeader('Content-Type', 'application/json')->withBody($stream);
        }

    $creneaux = $this->serviceRendezVous->listerCreneauxPraticien($praticienId, $from, $to);

        $body = json_encode(['data' => $creneaux]);
        $stream = (new StreamFactory())->createStream($body);

        return $rs->withHeader('Content-Type', 'application/json')->withBody($stream);
    }
}