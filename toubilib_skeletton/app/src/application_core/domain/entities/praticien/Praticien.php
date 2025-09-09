<?php
declare(strict_types=1);

namespace toubilib\core\domain\entities\praticien;

/**
 * Entité domaine Praticien (architecture hexagonale)
 * Champs exposés pour la fonctionnalité 1 : nom, prénom, ville, email, spécialité.
 */
 class Praticien
{
    private ?string $id;
    private string $nom;
    private string $prenom;
    private ?string $ville;
    private ?string $email;
    private ?string $specialite; 

    public function __construct(
        ?string $id,
        string $nom,
        string $prenom,
        ?string $ville = null,
        ?string $email = null,
        ?string $specialite = null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->ville = $ville;
        $this->email = $email;
        $this->specialite = $specialite;
    }

    public function getId(): ?string { return $this->id; }
    public function getNom(): string { return $this->nom; }
    public function getPrenom(): string { return $this->prenom; }
    public function getVille(): ?string { return $this->ville; }
    public function getEmail(): ?string { return $this->email; }
    public function getSpecialite(): ?string { return $this->specialite; }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'ville' => $this->ville,
            'email' => $this->email,
            'specialite' => $this->specialite,
        ];
    }

    /**
     * Crée une entité Praticien depuis un tableau (ligne DB ou DTO).
     * Accepte 'specialite' comme string ou ['libelle'=>...], ou 'specialite_libelle'.
     */
    public static function fromArray(array $data): Praticien
    {
        $spec = null;
        if (isset($data['specialite'])) {
            if (is_array($data['specialite'])) {
                $spec = $data['specialite']['libelle'] ?? null;
            } else {
                $spec = (string)$data['specialite'];
            }
        } elseif (isset($data['specialite_libelle'])) {
            $spec = (string)$data['specialite_libelle'];
        }

        return new Praticien(
            $data['id'] ?? null,
            (string)($data['nom'] ?? ''),
            (string)($data['prenom'] ?? ''),
            $data['ville'] ?? null,
            $data['email'] ?? null,
            $spec
        );
    }
}