<?php
require_once __DIR__ . '/../controllers/AppController.php';

$appController = new AppController();

Flight::route('GET /', [$appController, 'home']);
Flight::route('GET /prets_view', [$appController, 'prets']);
Flight::route('GET /demande_form_view', [$appController, 'ajouterDemande']);
Flight::route('GET /capital', [$appController, 'capitalForm']);
Flight::route('GET /typePret', [$appController, 'typePretForm']);
Flight::route('GET /demandeNonValide', [$appController, 'demandeNonValide']);
Flight::route('GET /montant', [$appController, 'montant']);
