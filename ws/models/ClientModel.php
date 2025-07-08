<?php

class ClientModel extends BaseModel {
    private $db;

    public function __construct($db = null) {
        $this->db = $db ?? getDB(); // Utilise la connexion globale
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT id, nom, prenom, email, solde FROM client ORDER BY nom ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        return $this->fetchOne("SELECT id, nom, prenom, email, solde FROM client WHERE id = ?", [$id]);
    }

    public function insert($data) {
        $stmt = $this->db->prepare("INSERT INTO client (nom, prenom, email, mdp, solde) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['nom'],
            $data['prenom'],
            $data['email'],
            password_hash($data['mdp'], PASSWORD_DEFAULT), // 🔐 hash du mot de passe
            $data['solde'] ?? 0
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $query = "UPDATE client SET nom = ?, prenom = ?, email = ?, solde = ?";
        $params = [$data['nom'], $data['prenom'], $data['email'], $data['solde'], $id];

        // Si on souhaite aussi mettre à jour le mot de passe
        if (!empty($data['mdp'])) {
            $query = "UPDATE client SET nom = ?, prenom = ?, email = ?, mdp = ?, solde = ? WHERE id = ?";
            $params = [
                $data['nom'],
                $data['prenom'],
                $data['email'],
                password_hash($data['mdp'], PASSWORD_DEFAULT),
                $data['solde'],
                $id
            ];
        } else {
            $query .= " WHERE id = ?";
        }

        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM client WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM client WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateSolde($id, $nouveauSolde) {
        $stmt = $this->db->prepare("UPDATE client SET solde = ? WHERE id = ?");
        return $stmt->execute([$nouveauSolde, $id]);
    }
}
