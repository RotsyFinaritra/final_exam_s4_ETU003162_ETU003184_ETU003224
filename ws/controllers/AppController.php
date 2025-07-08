<?php

class AppController {
    public function home() {
        Flight::render("capital.php");
    }

    public function prets() {
        Flight::render("prets.php");
    }

    public function ajouterDemande() {
        Flight::render("demande_form.php");
    }

    public function capitalForm() {
        Flight::render("capital.php");
    }
    public function typePretForm() {
        Flight::render("typePret.php");
    }
    public function demandeNonValide() {
        Flight::render("demandes_non_valide.php");
    }

    public function montant() {
        Flight::render("montant_total_par_mois.php");
    }
}
