<?php
require_once __DIR__ . '/../models/Etudiant.php';

class EtudiantController {
    private $etudiantModel;

    public function __construct() {
        $this->etudiantModel = new Etudiant();
    }

    public function getAll() {
        $data = $this->etudiantModel->getAll();
        Flight::json($data);
    }

    public function getById($id) {
        $data = $this->etudiantModel->getById($id);
        Flight::json($data);
    }

    public function create() {
        $data = Flight::request()->data;
        $id = $this->etudiantModel->create($data);
        Flight::json(['message' => 'Étudiant ajouté', 'id' => $id]);
    }

    public function update($id) {
        parse_str(Flight::request()->getBody(), $data);
        $this->etudiantModel->update($id, $data);
        Flight::json(['message' => 'Étudiant modifié']);
    }

    public function delete($id) {
        $this->etudiantModel->delete($id);
        Flight::json(['message' => 'Étudiant supprimé']);
    }
}
