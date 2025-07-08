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
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll()
    {
        $stmt = $this->db->prepare("
            SELECT sp.*, 
                   tp.nom AS nom_type_pret, 
                   tp.taux AS taux_type_pret,
                   tr.nom AS nom_type_remboursement
            FROM simulation_pret sp
            JOIN type_pret tp ON sp.id_type_pret = tp.id
            LEFT JOIN type_remboursement tr ON sp.id_type_remboursement = tr.id
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT sp.*, 
                   tp.nom AS nom_type_pret, 
                   tp.taux AS taux_type_pret,
                   tr.nom AS nom_type_remboursement
            FROM simulation_pret sp
            JOIN type_pret tp ON sp.id_type_pret = tp.id
            LEFT JOIN type_remboursement tr ON sp.id_type_remboursement = tr.id
            WHERE sp.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $stmt = $this->db->prepare("
            INSERT INTO simulation_pret (id_type_pret, date_debut, duree, date_fin, montant, id_type_remboursement, assurance)
            VALUES (:id_type_pret, :date_debut, :duree, :date_fin, :montant, :id_type_remboursement, :assurance)
        ");
        $stmt->execute([
            'id_type_pret' => $data['id_type_pret'],
            'date_debut' => $data['date_debut'],
            'duree' => $data['duree'],
            'date_fin' => $data['date_fin'],
            'montant' => $data['montant'],
            'id_type_remboursement' => $data['id_type_remboursement'] ?? null,
            'assurance' => $data['assurance'] ?? 0
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $stmt = $this->db->prepare("
            UPDATE simulation_pret
            SET id_type_pret = :id_type_pret,
                date_debut = :date_debut,
                duree = :duree,
                date_fin = :date_fin,
                montant = :montant,
                id_type_remboursement = :id_type_remboursement,
                assurance = :assurance
            WHERE id = :id
        ");
        $stmt->execute([
            'id' => $id,
            'id_type_pret' => $data['id_type_pret'],
            'date_debut' => $data['date_debut'],
            'duree' => $data['duree'],
            'date_fin' => $data['date_fin'],
            'montant' => $data['montant'],
            'id_type_remboursement' => $data['id_type_remboursement'] ?? null,
            'assurance' => $data['assurance'] ?? 0
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM simulation_pret WHERE id = ?");
        $stmt->execute([$id]);
    }
    public function generateAmortissementPDF($tableau)
    {
        $pdf = new AmortissementPDF();
        $pdf->AddPage();
        $pdf->generateAmortissementTable($tableau);
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
