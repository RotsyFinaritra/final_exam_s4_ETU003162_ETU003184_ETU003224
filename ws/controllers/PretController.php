<?php
require_once __DIR__ . '/../models/PretModel.php';

class PretController
{
    private $model;

    public function __construct()
    {
        $this->model = new PretModel();
    }

    public function getAll()
    {
        $data = $this->model->getAll();
        Flight::json($data);
    }

    public function getOne($id)
    {
        $data = $this->model->getById($id);
        if ($data) {
            Flight::json($data);
        } else {
            Flight::json(['error' => 'Prêt non trouvé'], 404);
        }
    }

    public function create()
    {
        $data = Flight::request()->data->getData();
        $success = $this->model->insert($data);
        Flight::json(['success' => $success]);
    }

    public function update($id)
    {
        $data = Flight::request()->data->getData();
        $success = $this->model->update($id, $data);
        Flight::json(['success' => $success]);
    }

    public function delete($id)
    {
        $success = $this->model->delete($id);
        Flight::json(['success' => $success]);
    }

    public function getPretsByCriteria()
    {
        $criteria = Flight::request()->query->getData();
        $prets = $this->model->getPretsByCriteria($criteria);
        Flight::json($prets);
    }

    public function generateAmortissementTable($id)
    {
        $pretModel = new PretModel();
        $pretModel->generateTableauAmortissement($id);
        Flight::json(["success" => true, "message" => "Tableau généré pour le prêt $id"]);
    }
    public function getInteretsParMoisJson()
    {
        // Récupérer les paramètres GET (depuis l'URL ou un formulaire)
        $moisDebut = isset($_GET['mois_debut']) ? intval($_GET['mois_debut']) : null;
        $anneeDebut = isset($_GET['annee_debut']) ? intval($_GET['annee_debut']) : null;
        $moisFin = isset($_GET['mois_fin']) ? intval($_GET['mois_fin']) : null;
        $anneeFin = isset($_GET['annee_fin']) ? intval($_GET['annee_fin']) : null;

        if (!$moisDebut || !$anneeDebut || !$moisFin || !$anneeFin) {
            Flight::json(['error' => 'Veuillez fournir mois/année de début et de fin valides'], 400);
            return;
        }

        // Appel à la méthode de calcul (celle qu'on a créée)
        $resultats = $this->model->getInteretsGagnesParMois($moisDebut, $anneeDebut, $moisFin, $anneeFin);
        // echo "ito";
        // print_r($resultats);
        // Retour JSON
        Flight::json($resultats);
    }
    // $pretModel = new PretModel();
    public function exporter(){
        $id=$_GET['id'];
        $this->model->generateTableauAmortissement2($id);
    }
}
