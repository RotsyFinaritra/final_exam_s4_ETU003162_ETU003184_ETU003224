<?php
require_once('fpdf.php');  // Assure-toi d'avoir bien mis fpdf.php dans ton projet

class AmortissementPDF extends fpdf
{
    // En-tête
    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode('Tableau d\'Amortissement'), 0, 1, 'C');
        $this->Ln(5);
    }

    // Pied de page
    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo(), 0, 0, 'C');
    }

    // Fonction pour générer le tableau
    function generateAmortissementTable($tableau)
    {
        // En-têtes
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(78, 51, 102);
        $this->SetTextColor(255, 255, 255);

        $this->Cell(15, 10, 'N°', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Date', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Capital', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Intérêts', 1, 0, 'C', true);
        $this->Cell(25, 10, 'Assurance', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Mensualité', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Solde', 1, 1, 'C', true);

        // Corps
        $this->SetFont('Arial', '', 9);
        $this->SetFillColor(240, 240, 240);
        $this->SetTextColor(0, 0, 0);

        foreach ($tableau as $row) {
            $this->Cell(15, 8, $row['numero'], 1, 0, 'C');
            $this->Cell(30, 8, date('Y-m-d', strtotime($row['date'])), 1, 0, 'C');
            $this->Cell(30, 8, number_format($row['montantCapital'], 2, ',', ' '), 1, 0, 'R');
            $this->Cell(30, 8, number_format($row['montantInteret'], 2, ',', ' '), 1, 0, 'R');
            $this->Cell(25, 8, number_format($row['assurance'], 2, ',', ' '), 1, 0, 'R');
            $this->Cell(30, 8, number_format($row['mensualite'], 2, ',', ' '), 1, 0, 'R');
            $this->Cell(30, 8, number_format($row['soldeRestant'], 2, ',', ' '), 1, 1, 'R');
        }
    }
    function generateAmortissementTable2($tableau,$nomClient,$montant,$duree)
    {
        // En-têtes
        $this->SetFont('Arial', 'B', 10);
        $this->SetFillColor(78, 51, 102);
        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', '', 11);
        $this->Cell(0, 8, 'Client : ' . utf8_decode($nomClient), 0, 1);
        $this->Cell(0, 8, 'Montant Emprunté : ' . number_format($montant, 2, ',', ' ') . ' €', 0, 1);
        $this->Cell(0, 8, 'Durée : ' . $duree . ' mois', 0, 1);
        $this->Ln(5);

        $this->Cell(15, 10, 'N°', 1, 0, 'C', true);
        $this->Cell(30, 10, 'mois', 1, 0, 'C', true);
        $this->Cell(30, 10, 'annee', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Capital', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Intérêts', 1, 0, 'C', true);
        $this->Cell(25, 10, 'Assurance', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Mensualité', 1, 0, 'C', true);
        $this->Cell(30, 10, 'Solde', 1, 1, 'C', true);

        // Corps
        $this->SetFont('Arial', '', 9);
        $this->SetFillColor(240, 240, 240);
        $this->SetTextColor(0, 0, 0);

        foreach ($tableau as $row) {
            $this->Cell(15, 8, $row['numero'], 1, 0, 'C');
            // $this->Cell(30, 8, date('Y-m-d', strtotime($row['date_paiement'])), 1, 0, 'C');
            $this->Cell(30, 8, $row['mois'], 1, 0, 'R');
            $this->Cell(30, 8, $row['annee'], 1, 0, 'R');
            $this->Cell(30, 8, number_format($row['amortissement'], 2, ',', ' '), 1, 0, 'R');
            $this->Cell(30, 8, number_format($row['interet'], 2, ',', ' '), 1, 0, 'R');
            $this->Cell(25, 8, number_format($row['assurance'], 2, ',', ' '), 1, 0, 'R');
            $this->Cell(30, 8, number_format($row['annuite'], 2, ',', ' '), 1, 0, 'R');
            $this->Cell(30, 8, number_format($row['capital_restant'], 2, ',', ' '), 1, 1, 'R');
        }
    }
}
