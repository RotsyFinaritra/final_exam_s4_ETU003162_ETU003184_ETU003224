<?php
require_once __DIR__ . '/../controllers/PretController.php';

$pretController = new PretController();

Flight::route('GET /clients/list', [$pretController, 'getAll']);
Flight::route('GET /clients/@id', [$pretController, 'getOne']);
Flight::route('POST /clients/create', [$pretController, 'create']);
Flight::route('PUT /clients/@id', [$pretController, 'update']);
Flight::route('DELETE /clients/@id', [$pretController, 'delete']);

