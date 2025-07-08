<?php
require_once __DIR__ . '/../models/CapitalModel.php';

class CapitalController {
    private $model;

    public function __construct() {
        $this->model = new CapitalModel();
    }

    public function getAll() {
        Flight::json($this->model->getAll());
    }

    public function getById($id) {
        Flight::json($this->model->getById($id));
    }

    public function create() {
        $data = Flight::request()->data;
        $success = $this->model->create(
            $data['date_mouvement'],
            $data['id_type_mvt'],
            $data['montant'],
            $data['id_capital']
        );
        Flight::json(['success' => $success]);
    }
    public function getTypes() {
        Flight::json($this->model->getAllTypes());
    }
    
    public function update($id) {
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
    

    public function delete($id) {
        $success = $this->model->delete($id);
        Flight::json(['success' => $success]);
    }
}
