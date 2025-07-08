<?php
require_once 'BaseModel.php';

class DemandePretModel extends BaseModel
{
    public function getAll()
    {
        $sql = "
            SELECT 
                dp.id,
                dp.date_demande,
                dp.duree_demande,
                dp.montant,
                c.id AS id_client,
                c.nom AS client_nom,
                c.prenom AS client_prenom,
                sd.id AS id_statut_demande,
                sd.nom AS statut_demande,
                tr.id AS id_type_remboursement,
                tr.nom AS type_remboursement
            FROM demande_pret dp
            JOIN client c ON dp.id_client = c.id
            JOIN statut_demande sd ON dp.id_statut_demande = sd.id
            LEFT JOIN type_remboursement tr ON dp.id_type_remboursement = tr.id
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "
            SELECT 
                dp.id,
                dp.date_demande,
                dp.duree_demande,
                dp.montant,
                c.id AS id_client,
                c.nom AS client_nom,
                c.prenom AS client_prenom,
                sd.id AS id_statut_demande,
                sd.nom AS statut_demande,
                tr.id AS id_type_remboursement,
                tr.nom AS type_remboursement
            FROM demande_pret dp
            JOIN client c ON dp.id_client = c.id
            JOIN statut_demande sd ON dp.id_statut_demande = sd.id
            LEFT JOIN type_remboursement tr ON dp.id_type_remboursement = tr.id
            WHERE dp.id = ?
        ";
        return $this->fetchOne($sql, [$id]);
    }

    public function insert($data)
    {
        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
            INSERT INTO demande_pret (
                id_client, date_demande, duree_demande, montant, id_statut_demande, id_type_remboursement,
                assurance, delai, date_debut, id_type_pret
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
            $stmt->execute([
                $data['id_client'],
                $data['date_demande'],
                $data['duree_demande'],
                $data['montant'],
                1, 
                $data['id_type_remboursement'] ?? 2,
                $data['assurance'],
                $data['delai'],
                $data['date_debut'],
                $data['id_type_pret']
            ]);

            $id_demande = $this->db->lastInsertId(); 

            $stmt2 = $this->db->prepare("
            INSERT INTO demande_statut_demmande (id_demande, id_statut_demande) VALUES (?, ?)
        ");
            $stmt2->execute([$id_demande, 1]); 

            $this->db->commit();

            return $id_demande;

        } catch (Exception $e) {
            $this->db->rollBack();
            throw new Exception("Erreur lors de l'insertion de la demande : " . $e->getMessage());
        }
    }



    public function update($id, $data)
    {
        $sql = "
            UPDATE demande_pret SET 
                id_client = ?, 
                date_demande = ?, 
                duree_demande = ?, 
                montant = ?, 
                id_statut_demande = ?, 
                id_type_remboursement = ?
            WHERE id = ?
        ";
        return $this->execute($sql, [
            $data['id_client'],
            $data['date_demande'],
            $data['duree_demande'],
            $data['montant'],
            $data['id_statut_demande'] ?? 1,
            $data['id_type_remboursement'] ?? null,
            $id
        ]);
    }

    public function delete($id)
    {
        return $this->execute("DELETE FROM demande_pret WHERE id = ?", [$id]);
    }

    public function getAllDemandeNonvalide()
    {
        $sql = "
        SELECT dp.*
        FROM demande_pret dp
        WHERE dp.id_statut_demande = 1 
    ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllDemandewithStatutFiltre($date_debut, $date_fin, $statut)
    {
        $sql = "SELECT dp.* FROM demande_pret dp WHERE 1=1";
        $params = [];
    
        // Filtre par statut
        if (!empty($statut)) {
            $sql .= " AND dp.id_statut_demande = :statut";
            $params[':statut'] = $statut;
        }
    
        // Filtre par date - gestion des cas partiels
        if (!empty($date_debut) && !empty($date_fin)) {
            // Les deux dates sont fournies
            $sql .= " AND dp.date_demande BETWEEN :date_debut AND :date_fin";
            $params[':date_debut'] = $date_debut;
            $params[':date_fin'] = $date_fin;
        } elseif (!empty($date_debut)) {
            // Seulement date de début
            $sql .= " AND dp.date_demande >= :date_debut";
            $params[':date_debut'] = $date_debut;
        } elseif (!empty($date_fin)) {
            // Seulement date de fin
            $sql .= " AND dp.date_demande <= :date_fin";
            $params[':date_fin'] = $date_fin;
        }
    
        // Optionnel : ordre par date de demande
        $sql .= " ORDER BY dp.date_demande DESC";
    
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
    
        return $stmt->fetchAll();
    }
}
?>