<?php
require_once __DIR__ . '/../controllers/SimulationController.php';

$simulationController = new SimulationController();

Flight::route('POST /simulations/save', [$simulationController, 'create']);
Flight::route('GET /simulations/getAll',[$simulationController, 'getAll']);