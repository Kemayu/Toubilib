<?php
declare(strict_types=1);

namespace toubilib\core\application\actions;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubilib\core\application\ports\api\dto\CredentialsDTO;
use toubilib\core\application\ports\api\provider\AuthProviderInterface;
use toubilib\core\application\ports\api\provider\AuthProviderInvalidCredentialsException;

class SigninAction
{
    private AuthProviderInterface $authProvider;

    public function __construct(AuthProviderInterface $authProvider)
    {
        $this->authProvider = $authProvider;
    }

    public function __invoke(
        ServerRequestInterface $request,
        ResponseInterface $response
    ): ResponseInterface {
        try {
            $data = $request->getParsedBody();

            $credentials = new CredentialsDTO(
                $data['email'] ?? '',
                $data['password'] ?? ''
            );

            $authDTO = $this->authProvider->signin($credentials);

            $response->getBody()->write(json_encode($authDTO->toArray()));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (AuthProviderInvalidCredentialsException $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Invalid credentials'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        } catch (\Exception $e) {
            $response->getBody()->write(json_encode([
                'error' => 'Internal server error'
            ]));
            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}
