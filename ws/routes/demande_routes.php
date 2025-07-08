<?php
require_once __DIR__ . '/../controllers/DemandePretController.php';

$demandePretController = new DemandePretController();

Flight::route('GET /demande_prets/list', [$demandePretController, 'getAll']);
Flight::route('GET /demande_prets/@id', [$demandePretController, 'getOne']);
Flight::route('POST /demande_prets/create', [$demandePretController, 'create']);
Flight::route('PUT /demande_prets/@id', [$demandePretController, 'update']);
Flight::route('DELETE /demande_prets/@id', [$demandePretController, 'delete']);


Flight::route('GET /demandes/filtrer', [$demandePretController, 'getAllDemandeWithStatutFiltre']);
Flight::route('GET /demandes/non-valide', [$demandePretController, 'getAllDemandeNonValide']);
Flight::route('POST /demandes', [$demandePretController, 'valider']);
Flight::route('POST /demandes/rejeter', [$demandePretController, 'rejeter']); // Nouvelle route

Flight::route('GET /statut_demandes', function () {
    $db = getDB(); // ou getDB() selon ta config

    $stmt = $db->query("SELECT * FROM statut_demande");
    $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);

    Flight::json($resultats);
});

Flight::route('GET /', function() {
    Flight::render('demandes_non_valide.php');
});
