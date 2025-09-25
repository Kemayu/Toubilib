<?php

declare(strict_types=1);

namespace toubilib\api\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\spi\repositoryInterfaces\ServiceRendezVousInterface;
use toubilib\core\application\ports\api\dto\InputRendezVousDTO;

class CreateRdvAction extends AbstractAction
{
    private ServiceRendezVousInterface $service;

    public function __construct(ServiceRendezVousInterface $service)
    {
        $this->service = $service;
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        $body = (array)$rq->getParsedBody();
        $dto = InputRendezVousDTO::fromArray($body);

        $result = $this->service->creerRendezVous($dto);

        if (!isset($result['success']) || $result['success'] !== true) {
            $code = $result['code'] ?? 'internal_error';
            $message = $result['message'] ?? 'Erreur interne';

            $map = [
                'praticien_not_found' => 404,
                'patient_not_found' => 404,
                'motif_not_allowed' => 422,
                'invalid_datetime' => 422,
                'day_not_allowed' => 422,
                'hour_not_allowed' => 422,
                'praticien_unavailable' => 409,
                'save_failed' => 500,
            ];
            $status = $map[$code] ?? 500;

            $rs->getBody()->write(json_encode(['error' => $code, 'message' => $message], JSON_UNESCAPED_UNICODE));
            return $rs->withHeader('Content-Type', 'application/json')->withStatus($status);
        }

        $rs->getBody()->write(json_encode(['id' => $result['id']], JSON_UNESCAPED_UNICODE));
        return $rs->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}