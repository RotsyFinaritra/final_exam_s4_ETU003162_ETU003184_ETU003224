<?php
require_once __DIR__ . '/../models/ClientModel.php';

class ClientController {
    private $model;

    public function __construct() {
        $this->model = new ClientModel();
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
            Flight::json(['error' => 'Client non trouvé'], 404);
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
