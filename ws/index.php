<?php
require_once __DIR__ . '/config.php';
require 'vendor/autoload.php';
require_once __DIR__ . '/db.php';  
require 'routes/etudiant_routes.php';
require_once __DIR__ . '/routes/app_routes.php';
require_once __DIR__ . '/routes/pret_routes.php';
require_once __DIR__ . '/routes/client_routes.php';
require_once __DIR__ . '/routes/type_pret_routes.php';
require_once __DIR__ . '/routes/demande_routes.php';
require_once __DIR__ . '/routes/TypePretRoutes.php';
require_once __DIR__ . '/routes/capitalRoutes.php';
require_once __DIR__ . '/routes/Interet_route.php';
require_once __DIR__ . '/routes/Simulation_routes.php';

Flight::start();