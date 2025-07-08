<?php
require_once __DIR__ . '/../models/StatutPretModel.php';

class StatutPretController {
    private $model;

    public function __construct() {
        $this->model = new StatutPretModel();
    }

    public function getAll() {
        $data = $this->model->getAll();
        Flight::json($data);
    }

    public function getOne($id) {
        $data = $this->model->getById($id);
        if ($data) {
            Flight::json($data);
        } else {
            Flight::json(['error' => 'Statut Prêt non trouvé'], 404);
        }
    }

    public function create() {
        $data = Flight::request()->data->getData();
        $success = $this->model->insert($data);
        Flight::json(['success' => $success]);
    }

    public function update($id) {
        $data = Flight::request()->data->getData();
        $success = $this->model->update($id, $data);
        Flight::json(['success' => $success]);
    }

    public function delete($id) {
        $success = $this->model->delete($id);
        Flight::json(['success' => $success]);
    }
}
