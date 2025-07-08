<?php 
require_once __DIR__ . '/../controllers/PretController.php';
require_once __DIR__ . '/../controllers/SimulationController.php';

$pretcontroller = new PretController();
$simulationController = new SimulationController();
Flight::route('GET /interets', function() {
    Flight::render('interets.php');
});
Flight::route('GET /simulation', function() {
    Flight::render('simulation.php');
});
Flight::route('POST /export-pdf', [$simulationController, 'exportPDF']);
Flight::route('GET /interets/par-mois',  [$pretcontroller, 'getInteretsParMoisJson']);

// Flight::route('GET /types-pret', [$typePretController, 'getAllTypePret']);


?>