<?php
declare(strict_types=1);

namespace toubilib\core\application\ports\api\dto;

class ProfileDTO
{
    public string $ID;
    public string $email;
    public int $role;

    public function __construct(string $id, string $email, int $role)
    {
        $this->ID = $id;
        $this->email = $email;
        $this->role = $role;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->ID,
            'email' => $this->email,
            'role' => $this->role,
            'role_label' => $this->getRoleLabel(),
        ];
    }

    private function getRoleLabel(): string
    {
        return match($this->role) {
            1 => 'patient',
            10 => 'praticien',
            100 => 'admin',
            default => 'unknown',
        };
    }
}
