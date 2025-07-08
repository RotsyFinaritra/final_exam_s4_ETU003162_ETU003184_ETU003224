<?php
require_once 'models/SimulationModel.php';
require_once 'models/PretModel.php';

class SimulationController
{

    // public function exportPDF() {
    //     // Récupérer les données envoyées par AJAX (JSON)
    //     $json = file_get_contents('php://input');
    //     $data = json_decode($json, true);

    //     if (!isset($data['tableau'])) {
    //         http_response_code(400);
    //         echo json_encode(['error' => 'Tableau manquant']);
    //         return;
    //     }

    //     $model = new SimulationModel();
    //     $model->generateAmortissementPDF($data['tableau']);
    // }
    private $model;

    public function __construct()
    {
        $this->model = new SimulationModel();
    }

    public function getAll()
    {
        $data = $this->model->getAll();
        Flight::json(
            [
                'success' => true,
                'data' => $data
            ]
        );
    }

    public function getById($id)
    {
        $data = $this->model->getById($id);
        Flight::json($data);
    }

    public function create()
    {
        $data = Flight::request()->data->getData();
        print_r($data['simulation']);
        $data = $data['simulation'];
        $data = json_decode($data, true);
        $id = $this->model->create($data);
        Flight::json(
            [
                'success' => true,
                'message' => 'Simulation créée',
                'id' => $id
            ]
        );
    }

    public function update($id)
    {
        parse_str(Flight::request()->getBody(), $data);
        $this->model->update($id, $data);
        Flight::json(['message' => 'Simulation mise à jour']);
    }
    public function exportPDF()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!isset($data['tableau'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tableau manquant']);
            return;
        }
        //generer le pret en tant que pdf
        // $pretModel = new PretModel();
        // $pretModel->generateTableauAmortissement2(5);
        // generer la simulation en tant que pdf 
        $model = new SimulationModel();
        $model->generateAmortissementPDF(
            $data['tableau'],
            $data['nomClient'] ?? 'Simulation',
            $data['montant'] ?? 0,
            $data['mensualite'] ?? 0,
            $data['totalInterets'] ?? 0,
            $data['totalAPayer'] ?? 0,
            $data['tauxTotal'] ?? 0
        );
    }
}
