<?php
require_once __DIR__ . '/../controllers/CapitalController.php';

$controller = new CapitalController();

Flight::route('GET /mouvements', [$controller, 'getAll']);
Flight::route('GET /mouvements/@id', [$controller, 'getById']);
Flight::route('POST /mouvements', [$controller, 'create']);
Flight::route('PUT /mouvements/@id', [$controller, 'update']);
Flight::route('DELETE /mouvements/@id', [$controller, 'delete']);
Flight::route('GET /types-mouvement', [$controller, 'getTypes']);

