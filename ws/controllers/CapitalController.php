<?php
require_once __DIR__ . '/../models/CapitalModel.php';

class CapitalController
{
    private $model;

    public function __construct()
    {
        $this->model = new CapitalModel();
    }

    public function getAll()
    {
        Flight::json($this->model->getAll());
    }

    public function getById($id)
    {
        Flight::json($this->model->getById($id));
    }

    public function create()
    {
        $data = Flight::request()->data;

        // Vérifier si le capital existe déjà
        $capital = $this->model->getCapitalEnCours();

        if ($data['id_type_mvt'] == 1) {
            // Type 1: Ajout au capital
            if ($capital !== null && $capital !== 0) {
                // Update si le capital existe
                $nouveauCapital = $capital + $data['montant'];
                $this->model->updateCapitalEnCours($nouveauCapital);
            } else {
                // Insert si le capital n'existe pas
                $this->model->insertCapitalEnCours($data['montant']);
            }

            // Créer le mouvement
            $success = $this->model->create(
                $data['date_mouvement'],
                $data['id_type_mvt'],
                $data['montant'],
                1
            );

            Flight::json(['success' => $success]);

        } else {
            // Type différent de 1: Soustraction du capital
            if ($capital === null || $capital === 0) {
                // Pas de capital existant
                Flight::json([
                    'success' => false,
                    'error' => 'Fond inexistant'
                ]);
                return;
            }

            // Vérifier si le capital est suffisant
            if ($capital < $data['montant']) {
                Flight::json([
                    'success' => false,
                    'error' => 'Solde insuffisant'
                ]);
                return;
            }

            // Procéder à la soustraction
            $nouveauCapital = $capital - $data['montant'];
            $this->model->updateCapitalEnCours($nouveauCapital);

            // Créer le mouvement
            $success = $this->model->create(
                $data['date_mouvement'],
                $data['id_type_mvt'],
                $data['montant'],
                1
            );

            Flight::json(['success' => $success]);
        }
    }
    public function getCapitalMontant()
    {
        $capital = $this->model->getCapitalEnCours();

        // Debug du résultat
        error_log("Capital récupéré: " . var_export($capital, true));

        if ($capital !== null) {
            Flight::json(['montant' => $capital]);
        } else {
            Flight::json(['montant' => 0]);
        }
    }
    public function getTypes()
    {
        Flight::json($this->model->getAllTypes());
    }

    public function update($id)
    {
        try {
            // Récupérer les données brutes
            $rawData = file_get_contents('php://input');
            error_log("Raw data: " . $rawData);

            // Parser les données
            parse_str($rawData, $data);
            error_log("Parsed data: " . json_encode($data));

            // Vérifier les champs requis
            $requiredFields = ['date_mouvement', 'id_type_mvt', 'montant', 'id_capital'];
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || $data[$field] === '') {
                    throw new Exception("Données manquantes ou vides: $field");
                }
            }

            $success = $this->model->update(
                $id,
                $data['date_mouvement'],
                $data['id_type_mvt'],
                $data['montant'],
                $data['id_capital']
            );

            Flight::json(['success' => $success]);

        } catch (Exception $e) {
            error_log("Erreur update mouvement: " . $e->getMessage());
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }


    public function delete($id)
    {
        $success = $this->model->delete($id);
        Flight::json(['success' => $success]);
    }
}
