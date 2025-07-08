<?php
require_once __DIR__ . '/../db.php';

class BaseModel {
    protected $db;

    public function __construct() {
        $this->db = getDB();
    }

    protected function save($query, $params = []) {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $this->db->lastInsertId();
    }


    protected function fetchAll($query, $params = []) {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function fetchOne($query, $params = []) {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    protected function execute($query, $params = []) {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
