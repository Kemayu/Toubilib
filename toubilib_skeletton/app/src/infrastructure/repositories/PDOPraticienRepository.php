<?php

namespace toubilib\infra\repositories;

use toubilib\core\application\ports\spi\repositoryInterfaces\PraticienRepositoryInterface;
use toubilib\core\domain\entities\praticien\Praticien;

class PDOPraticienRepository implements PraticienRepositoryInterface
{


    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    public function getAllPraticien(): array
    {

        $stmt = $this->pdo->prepare('SELECT * FROM praticien');
        $stmt->execute();


        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $praticiens = [];
        foreach ($results as $element) {
            $praticiens[] = new Praticien(
                $element['id'],
                $element['nom'],
                $element['prenom'],
                $element['ville'],
                $element['email'],
                $element['specialite_id']
            );
        }
        return $praticiens;
    }
}