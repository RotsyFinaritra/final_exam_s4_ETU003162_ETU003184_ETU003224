<?php
require_once __DIR__ . '/../db.php';

class CapitalModel
{
    private $db;

    public function __construct()
    {
        $this->db = getDB();
    }

    public function getCapitalEnCours()
    {
        $stmt = $this->db->query("SELECT montant FROM capital LIMIT 1");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['montant'] : null; // Retourne null au lieu de 0
    }

    public function updateCapitalEnCours($montant)
    {
        try {
            $montant_actu = $this->getCapitalEnCours();
            // Démarrer une transaction
            $this->db->beginTransaction();

            // Étape 1 : Mise à jour du capital
            $stmtUpdate = $this->db->prepare("UPDATE capital SET montant = ? WHERE id = ?");
            $stmtUpdate->execute([$montant, 1]);

            // Étape 2 : Insertion dans mouvement_capital
            $stmtInsert = $this->db->prepare("
            INSERT INTO mouvement_capital (date_mouvement, id_type_mvt, montant, id_capital)
            VALUES (?, ?, ?, ?)
        ");
            $stmtInsert->execute([
                date('Y-m-d'),
                3,
                abs($montant_actu - $montant),
                1
            ]);

            // Commit si tout est bon
            $this->db->commit();
            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function insertCapitalEnCours($montant)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO capital (montant) VALUES (?)");
            return $stmt->execute([$montant]);
        } catch (Exception $e) {
            return false;
        }
    }


    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT m.id, m.date_mouvement, m.montant, m.id_capital,
                   t.id AS id_type_mouvement,
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
