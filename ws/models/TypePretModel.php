<?php
require_once 'BaseModel.php';

class TypePretModel extends BaseModel {
    
    public function getAll() {
        return $this->fetchAll("SELECT * FROM type_pret");
    }

    public function getById($id) {
        return $this->fetchOne("SELECT * FROM type_pret WHERE id = ?", [$id]);
    }

    public function create($nom, $taux) {
        $stmt = $this->db->prepare("INSERT INTO type_pret (nom, taux) VALUES (?, ?)");
        $stmt->execute([$nom, $taux]);
        return $this->db->lastInsertId();
    }

    public function update($id, $nom, $taux) {
        $stmt = $this->db->prepare("UPDATE type_pret SET nom = ?, taux = ? WHERE id = ?");
        $stmt->execute([$nom, $taux, $id]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM type_pret WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
