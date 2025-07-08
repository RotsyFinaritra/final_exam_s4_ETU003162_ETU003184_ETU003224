<?php
require_once __DIR__ . '/../models/DemandePretModel.php';
require_once __DIR__ . '/../models/DemandeStatutDemmandeModel.php';
require_once __DIR__ . '/../models/CapitalModel.php';


class DemandePretController
{
    private $demandePretModel;
    private $demandeStatutModel;
    private $pretModel;
    private $capitalModel;


    public function __construct()
    {
        $this->demandePretModel = new DemandePretModel();
        $this->demandeStatutModel = new DemandeStatutDemmandeModel();
        $this->pretModel = new PretModel();
        $this->capitalModel = new CapitalModel();

    }
    public function getAllDemandeWithStatutFiltre()
    {
        $request = Flight::request()->query;
        $date_debut = $request['date_debut'] ?? null;
        // echo $date_debut;

        $date_fin = $request['date_fin'] ?? null;
        // echo $date_fin;
        $statut = $request['statut'] ?? null;

        $result = $this->demandePretModel->getAllDemandewithStatutFiltre($date_debut, $date_fin, $statut);
        Flight::json($result);
    }

    public function getAllDemandeNonValide()
    {
        $result = $this->demandePretModel->getAllDemandeNonvalide();
        Flight::json($result);
    }

    public function valider()
    {
        $data = Flight::request()->data;
        $id = $data['id'];
        //quand on cree une demande on ajoude dans demande_statut_demande 
        //l'association demande-statut
        //creer un pret
        $duree = isset($data['duree_demande']) ? (int)$data['duree_demande'] : 0;
        $dateDebutStr = isset($data->date_debut) && !empty($data->date_debut) ? $data->date_debut : date('Y-m-d');
        $dateDebut = new DateTime($dateDebutStr);
        $duree = 2;
        $dateFin = $dateDebut->format('Y-m-d');

        // echo $duree;
        if ($duree > 0) {
            $interval = new DateInterval('P' . $duree . 'M');
            $dateDebut->add($interval);
        }
        $capital = $this->capitalModel->getCapitalEnCours();
        if($capital < $data['montant']){
            Flight::json([
                'message' => 'Demande non validée',
                'id' => $id,
                'capital' => $capital
            ]);
        }else{
            $pret = [
                'id_client'     => $data['id_client'],
                'id_type_pret'  => $data['id_type_pret'],
                'montant'       => $data['montant'],
                'duree'         => $data['duree_demande'],
                'id_type_remboursement'=> $data['id_type_remboursement'] ?? 0,
                'assurance'     => $data['assurance'] ?? 0,
                'date_debut'    => $data['date_debut'],
                'date_fin'      => $dateFin,
                'delai'         => $data['delai'] ?? 0
            ];
            // $pret
            $this->pretModel->insert($pret);
            $id_pret = $this->pretModel->getLastInsertId();
            $this->pretModel->generateTableauAmortissement($id_pret);
            $data['id_statut_demande'] = 2;
            $id_statut_valide = 2;
            $idStatut = $id_statut_valide;
            $this->demandePretModel->update($id, $data);
            $this->demandeStatutModel->addStatutToDemande($id, $idStatut);
            $this->capitalModel->updateCapitalEnCours($capital-$data['montant']);          
            Flight::json(['message' => 'Demande créée avec succès', 'id' => $id]);

        }
        // $pret['duree']=$data['duree_demande'];
             // Flight::json(['message' => 'Demande créée avec succès', 'id' => $id]);
    }
    public function rejeter()
    {
        $data = Flight::request()->data;
        $id = $data['id'];
        //quand on cree une demande on ajoude dans demande_statut_demande 
        //l'association demande-statut
        $data['id_statut_demande'] = 3;
        $id_statut_valide = 3;
        $idStatut = $id_statut_valide;
        $this->demandePretModel->update($id, $data);
        $this->demandeStatutModel->addStatutToDemande($id, $idStatut);
        Flight::json(['message' => 'Demande créée avec succès', 'id' => $id]);
    }

    public function getAll()
    {
        $demandes = $this->demandePretModel->getAll();
        Flight::json($demandes);
    }

    public function getOne($id)
    {
        $demande = $this->demandePretModel->getById($id);
        if ($demande) {
            Flight::json($demande);
        } else {
            Flight::json(['error' => 'Demande non trouvée'], 404);
        }
    }

    public function create()
    {
        $data = Flight::request()->data->getData();
        $requiredFields = ['id_client', 'date_demande', 'duree_demande', 'montant'];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                Flight::json(['error' => "Le champ $field est requis"], 400);
                return;
            }
        }

        $result = $this->demandePretModel->insert($data);
        if ($result) {

            Flight::json(['success' => $result], 200);
        } else {
            Flight::json(['error' => 'Erreur lors de la création de la demande'], 500);
        }
    }

    public function update($id)
    {
        $data = Flight::request()->data->getData();
        $requiredFields = ['id_client', 'date_demande', 'duree_demande', 'montant', 'id_statut_demande'];

        foreach ($requiredFields as $field) {
            if (!isset($data[$field]) || empty($data[$field])) {
                Flight::json(['error' => "Le champ $field est requis"], 400);
                return;
            }
        }

        $result = $this->demandePretModel->update($id, $data);
        if ($result) {
            Flight::json(['success' => true]);
        } else {
            Flight::json(['error' => 'Erreur lors de la mise à jour de la demande ou demande non trouvée'], 404);
        }
    }

    public function delete($id)
    {
        $result = $this->demandePretModel->delete($id);
        if ($result) {
            Flight::json(['success' => true]);
        } else {
            Flight::json(['error' => 'Erreur lors de la suppression de la demande ou demande non trouvée'], 404);
        }
    }
}
