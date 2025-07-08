<?php
require('AmortissementPDF.php');

class SimulationModel
{

    // public function generateAmortissementPDF($tableau) {
    //     $pdf = new AmortissementPDF();
    //     $pdf->AddPage();
    //     $pdf->generateAmortissementTable($tableau);

    //     // Retourne le PDF en sortie
    //     $pdf->Output('D', 'Tableau_Amortissement.pdf');
    // }
    public function generateAmortissementPDF($tableau)
    {
        // Création PDF (exemple simple)
        $pdf = new AmortissementPDF();
        $pdf->AddPage();
        $pdf->generateAmortissementTable($tableau);

        // Capture le contenu PDF en mémoire
        $pdfContent = $pdf->Output('', 'S'); // S = retourne le PDF en string

        // Encode en base64
        $pdfBase64 = base64_encode($pdfContent);

        // Répond en JSON
        echo json_encode([
            'success' => true,
            'pdfBase64' => $pdfBase64
        ]);
    }
}
