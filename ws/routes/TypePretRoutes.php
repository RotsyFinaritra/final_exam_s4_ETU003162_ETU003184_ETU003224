<?php
require_once __DIR__ . '/../controllers/TypePretController.php';

$controller = new TypePretController();

Flight::route('GET /typeprets', [$controller, 'getAll']);
Flight::route('GET /typeprets/@id', [$controller, 'getById']);
Flight::route('POST /typeprets', [$controller, 'create']);
Flight::route('PUT /typeprets/@id', [$controller, 'update']);
Flight::route('DELETE /typeprets/@id', [$controller, 'delete']);
