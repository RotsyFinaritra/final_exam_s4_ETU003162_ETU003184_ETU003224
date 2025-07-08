<?php
require_once __DIR__ . '/../db.php';

class DemandeStatutDemmandeModel {
    private $db;

    public function __construct() {
        $this->db = getDB();
    }

    public function getAll() {
        $stmt = $this->db->query("
            SELECT dsd.*, sd.nom AS statut_nom, dp.id_client
            FROM demande_statut_demmande dsd
            LEFT JOIN statut_demande sd ON dsd.id_statut_demande = sd.id
            LEFT JOIN demande_pret dp ON dsd.id_demande = dp.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByDemande($id_demande) {
        $stmt = $this->db->prepare("
            SELECT dsd.*, sd.nom AS statut_nom
            FROM demande_statut_demmande dsd
            LEFT JOIN statut_demande sd ON dsd.id_statut_demande = sd.id
            WHERE dsd.id_demande = ?
        ");
        $stmt->execute([$id_demande]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addStatutToDemande($id_demande, $id_statut) {
        $stmt = $this->db->prepare("
            INSERT INTO demande_statut_demmande (id_demande, id_statut_demande)
            VALUES (?, ?)
        ");
        $stmt->execute([$id_demande, $id_statut]);
        return $this->db->lastInsertId();
    }
}
