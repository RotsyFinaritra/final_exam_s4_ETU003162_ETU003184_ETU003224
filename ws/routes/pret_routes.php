<?php
require_once __DIR__ . '/../controllers/PretController.php';

$pretController = new PretController();

Flight::route('GET /prets/list', [$pretController, 'getAll']);
Flight::route('GET /prets/@id', [$pretController, 'getOne']);
Flight::route('POST /prets/create', [$pretController, 'create']);
Flight::route('PUT /prets/@id', [$pretController, 'update']);
Flight::route('DELETE /prets/@id', [$pretController, 'delete']);
Flight::route('GET /pret/filter', [$pretController, 'getPretsByCriteria']);
Flight::route('POST /prets/generateTableauAmortissement/@id', [$pretController,'generateAmortissementTable']);

Flight::route('GET /exporter', [$pretController,'exporter']);
