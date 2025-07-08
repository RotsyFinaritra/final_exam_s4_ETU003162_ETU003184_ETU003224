<?php
require_once __DIR__ . '/../models/TypePretModel.php';

class TypePretController {
    private $model;

    public function __construct() {
        $this->model = new TypePretModel();
    }

    public function getAll() {
        Flight::json($this->model->getAll());
    }

    public function getById($id) {
        Flight::json($this->model->getById($id));
    }

    public function create() {
        $d = Flight::request()->data;
        $success = $this->model->create($d['nom'], $d['taux']);
        Flight::json(['success' => $success]);
    }

    public function update($id) {
        try {
            $rawData = file_get_contents('php://input');
            error_log("Raw data: " . $rawData);
            
            parse_str($rawData, $d);
            error_log("Parsed data: " . json_encode($d));
            
            if (!isset($d['nom']) || !isset($d['taux'])) {
                throw new Exception("Données manquantes");
            }
            
            $success = $this->model->update($id, $d['nom'], $d['taux']);
            Flight::json(['success' => $success]);
            
        } catch (Exception $e) {
            error_log("Erreur: " . $e->getMessage());
            Flight::json(['error' => $e->getMessage()], 500);
        }
    }

    public function delete($id) {
        $success = $this->model->delete($id);
        Flight::json(['success' => $success]);
    }
}
