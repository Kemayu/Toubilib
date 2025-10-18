<?php
declare(strict_types=1);

namespace toubilib\core\application\services;

use toubilib\core\application\ports\api\dto\CredentialsDTO;
use toubilib\core\application\ports\api\dto\ProfileDTO;
use toubilib\core\application\ports\api\service\ToubilibAuthnServiceInterface;
use toubilib\core\application\ports\api\service\AuthenticationFailedException;
use toubilib\core\application\ports\spi\repositoryInterfaces\AuthRepositoryInterface;

class ToubilibAuthnService implements ToubilibAuthnServiceInterface
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function byCredentials(CredentialsDTO $credentials): ProfileDTO
    {
        $userData = $this->authRepository->findUserByEmail($credentials->email);

        if ($userData === null) {
            throw new AuthenticationFailedException('Invalid credentials');
        }

        if (!password_verify($credentials->password, $userData['password'])) {
            throw new AuthenticationFailedException('Invalid credentials');
        }

        return new ProfileDTO(
            $userData['id'],  
            $userData['email'],
            (int)$userData['role']
        );
    }

    public function register(CredentialsDTO $credentials, int $role): ProfileDTO
    {
        throw new \RuntimeException('Registration not yet implemented');
    }
}
