<?php
declare(strict_types=1);

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServiceRendezVousInterface;

class ListerRDVbyId
{
    private ServiceRendezVousInterface $serviceRendezVous;

    public function __construct(ServiceRendezVousInterface $serviceRendezVous)
    {
        $this->serviceRendezVous = $serviceRendezVous;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $id = $args['id'] ?? null;
        if (!$id) {
            $body = json_encode(['error' => 'missing id']);
            $rs->getBody()->write($body);
            return $rs->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

    $rdv = $this->serviceRendezVous->getRdvById($id);
        if (!$rdv) {
            $rs->getBody()->write(json_encode(['error' => 'not found']));
            return $rs->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        $rs->getBody()->write(json_encode(['data' => $rdv]));
        return $rs->withHeader('Content-Type', 'application/json');
    }
}
