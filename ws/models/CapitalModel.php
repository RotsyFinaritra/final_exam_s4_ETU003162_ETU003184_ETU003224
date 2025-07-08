<?php
require_once __DIR__ . '/../db.php';

class CapitalModel
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT m.id, m.date_mouvement, m.montant, m.id_capital,
                   t.nom AS type_mouvement
            FROM mouvement_capital m
            JOIN type_mouvement t ON m.id_type_mvt = t.id
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->db->prepare("
            SELECT m.id, m.date_mouvement, m.montant, m.id_capital,
                   m.id_type_mvt, t.nom AS type_mouvement
            FROM mouvement_capital m
            JOIN type_mouvement t ON m.id_type_mvt = t.id
            WHERE m.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($date_mouvement, $id_type_mvt, $montant, $id_capital)
    {
        $stmt = $this->db->prepare("
            INSERT INTO mouvement_capital (date_mouvement, id_type_mvt, montant, id_capital)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$date_mouvement, $id_type_mvt, $montant, $id_capital]);
    }

    public function update($id, $date_mouvement, $id_type_mvt, $montant, $id_capital)
    {
        try {
            $stmt = $this->db->prepare("
            UPDATE mouvement_capital
            SET date_mouvement = ?, id_type_mvt = ?, montant = ?, id_capital = ?
            WHERE id = ?
        ");
            return $stmt->execute([$date_mouvement, $id_type_mvt, $montant, $id_capital, $id]);
        } catch (PDOException $e) {
            error_log("Erreur SQL update(): " . $e->getMessage());
            return false;
        }
    }


    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM mouvement_capital WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAllTypes()
    {
        $stmt = $this->db->query("SELECT id, nom FROM type_mouvement");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
