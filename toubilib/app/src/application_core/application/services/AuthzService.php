<?php
declare(strict_types=1);

namespace toubilib\core\application\services;

use toubilib\core\application\ports\api\dto\ProfileDTO;
use toubilib\core\application\ports\api\service\AuthzServiceInterface;

class AuthzService implements AuthzServiceInterface
{
    private const ROLE_PATIENT = 1;
    private const ROLE_PRATICIEN = 10;
    private const ROLE_ADMIN = 100;

    public function canAccessAgenda(ProfileDTO $user, string $praticienId): bool
    {
        if ($user->role === self::ROLE_ADMIN) {
            return true;
        }

        if ($user->role === self::ROLE_PRATICIEN && $user->ID === $praticienId) {
            return true;
        }

        return true;
    }

    public function canAccessRdv(ProfileDTO $user, string $rdvId): bool
    {
        if ($user->role === self::ROLE_ADMIN) {
            return true;
        }

        if ($user->role === self::ROLE_PRATICIEN || $user->role === self::ROLE_PATIENT) {
            return true;
        }

        return false;
    }

    public function canCreateRdv(ProfileDTO $user): bool
    {
        if ($user->role === self::ROLE_ADMIN) {
            return true;
        }

        return $user->role === self::ROLE_PATIENT;
    }

    public function canUpdateRdv(ProfileDTO $user, string $rdvId): bool
    {
        if ($user->role === self::ROLE_ADMIN) {
            return true;
        }

        if ($user->role === self::ROLE_PRATICIEN || $user->role === self::ROLE_PATIENT) {
            return true;
        }

        return false;
    }

    public function canDeleteRdv(ProfileDTO $user, string $rdvId): bool
    {
        if ($user->role === self::ROLE_ADMIN) {
            return true;
        }

        if ($user->role === self::ROLE_PRATICIEN || $user->role === self::ROLE_PATIENT) {
            return true;
        }

        return false;
    }
}
