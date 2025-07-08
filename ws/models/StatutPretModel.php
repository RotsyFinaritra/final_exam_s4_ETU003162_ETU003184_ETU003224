<?php
require_once 'BaseModel.php';

class StatutPretModel extends BaseModel {
    public function getAll() {
        return $this->fetchAll("SELECT * FROM statut_pret");
    }

    public function getById($id) {
        return $this->fetchOne("SELECT * FROM statut_pret WHERE id = ?", [$id]);
    }

    public function insert($nom) {
        return $this->execute("INSERT INTO statut_pret (nom) VALUES (?)", [$nom]);
    }

    public function update($id, $nom) {
        return $this->execute("UPDATE statut_pret SET nom = ? WHERE id = ?", [$nom, $id]);
    }

    public function delete($id) {
        return $this->execute("DELETE FROM statut_pret WHERE id = ?", [$id]);
    }
}
