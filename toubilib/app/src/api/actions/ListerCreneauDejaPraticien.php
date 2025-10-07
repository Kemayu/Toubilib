<?php
declare(strict_types=1);

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServiceRendezVousInterface;

class ListerCreneauDejaPraticien extends AbstractAction
{
    protected ServiceRendezVousInterface $serviceRendezVous;

    public function __construct(ServiceRendezVousInterface $serviceRendezVous)
    {
        $this->serviceRendezVous = $serviceRendezVous;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $praticienId = $args['praticienId'] ?? $rq->getQueryParams()['praticienId'] ?? null;
        $from = $rq->getQueryParams()['from'] ?? null;
        $to = $rq->getQueryParams()['to'] ?? null;

        if (!$praticienId) {
            $rs->getBody()->write(json_encode(['error' => 'missing parameters, require praticienId'], JSON_UNESCAPED_UNICODE));
            return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        // si from/to manquant => période = journée courante
        if (!$from || !$to) {
            $today = new \DateTimeImmutable('now');
            $from = $today->format('Y-m-d') . ' 00:00:00';
            $to = $today->format('Y-m-d') . ' 23:59:59';
        }

        $creneaux = $this->serviceRendezVous->listerCreneauxPraticien($praticienId, $from, $to);

        $rs->getBody()->write(json_encode(['data' => $creneaux], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}